<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PaymentResource;
use App\Events\PaymentRegisteredEvent;
use App\Http\Requests\PaymentStoreRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('payment.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = PaymentResource::collection(Payment::all());
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        DB::beginTransaction();
        try {
            Payment::create([
                'company_id' => $request->input('company_id'),
                'currency_id' => $request->input('currency_id'),
            ]);
            DB::commit();
            event(new PaymentRegisteredEvent('Payment Registered'));
            if(!$request->header('Authorization'))
                return redirect('/payment');
            return response()->json('Payment registered',200);
        } catch(\Exception $e) {
            DB::rollBack();
            if(!$request->header('Authorization'))
                return redirect()->back()->with('message', $e->getMessage());
            return response()->json($e->getMessage(),200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment = Payment::where('payment',$payment)->get()->first();
        return view('payment.edit',['payment' => $payment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentStoreRequest $request, Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->update([
                'company_id' => $request->input('company_id'),
                'currency_id' => $request->input('currency_id')
            ]);
            DB::commit();
            event(new PaymentRegisteredEvent('Payment Updated'));
            if(!$request->header('Authorization'))
                return redirect('/payment');
            return response()->json('Payment updated',200);
        } catch(\Exception $e) {
            DB::rollBack();
            if(!$request->header('Authorization'))
                return redirect()->back()->with('message', $e->getMessage());
            return response()->json($e->getMessage(),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($payment)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($payment)->delete();
            DB::commit();
            event(new PaymentRegisteredEvent('Payment Deleted'));
            return redirect('/payment');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
