<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Item;
use App\Models\Service;
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
        $payments = Payment::with('company','currency','services','items')->get();
        return view('payment.create',compact('payments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentStoreRequest $request)
    {
        $services_insert = collect([]);
        $items_insert = collect([]);
        $sum_items = 0;
        $sum_services = 0;
        if($request->input('services')){
            foreach($request->input('services') as $service){
                $services_insert->push(Service::where('service',$service)->get()->first());
            }
        }
        if($request->input('items')){
            foreach($request->input('items') as $item){
                $items_insert->push(Item::where('item',$item)->get()->first());
            }
        }
        dd($items_insert);

        // foreach($shop->products as $product) {
        //     $event = Event::create([]);
        //     $service = Service::create([]);
          
        //     $product->productable()->saveMany([
        //       $event, $service
        //     ]);
        //   }
          
        DB::beginTransaction();
        try {
            $company = Company::where('company',$request->input('company'))->get()->first();
            $currency = Currency::where('currency',$request->input('currency'))->get()->first();
            $payment = Payment::create([
                'company_id' => $company->id,
                'currency_id' => $currency->id,
            ]);
            $payment->
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
    public function edit($payment)
    {
        $payment = Payment::find($payment);
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
