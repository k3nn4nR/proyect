<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Item;
use App\Models\Type;
use App\Models\Tag;
use App\Models\Warehouse;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\TotalDayPayments;
use App\Events\PaymentRegisteredEvent;
use App\Events\InventoryLoadEvent;
use App\Events\InventoryLoadTypeEvent;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\StorePaymentTagsRequest;
use Carbon\Carbon;

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
        $data = PaymentResource::collection(Payment::orderByDesc('created_at')->get());
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
        DB::beginTransaction();
        try {
            if(!$request->header('Authorization') && Warehouse::all()->isEmpty())
                return redirect()->back()->withErrors(['message' => 'No Warehouse created']);
            if($request->header('Authorization') && Warehouse::all()->isEmpty())
                return response()->json('No Warehouse to store',200);
            $company = Company::where('company',$request->input('company'))->get()->first();
            $currency = Currency::where('currency',$request->input('currency'))->get()->first();
            $payment = Payment::create([
                'company_id' => $company->id,
                'currency_id' => $currency->id,
                'total' => $request->input('total'),
                'created_at' => ($request->input('created_at')) ? Carbon::create($request->input('created_at'))->toDateTimeString() : Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $position = 0;
            if($request->input('services')){
                foreach($request->input('services') as $service){
                    $service_insert = Service::where('service',$service)->get()->first();
                    $payment->services()->save($service_insert, [
                        'amount' => $request->input('services_amount')[$position],
                        'price' => $request->input('services_price')[$position],
                        'subtotal' => $request->input('services_subtotal')[$position],
                    ]);
                    $position++;
                }
                $position = 0;
            }
            if($request->input('items')){
                foreach($request->input('items') as $item){
                    $item_insert = Item::where('item',$item)->get()->first();
                    $payment->items()->save($item_insert, [
                        'amount' => $request->input('items_amount')[$position],
                        'price' => $request->input('items_price')[$position],
                        'subtotal' => $request->input('items_subtotal')[$position],
                    ]);
                    $position++;
                }
            }
            if($request->input('types')){
                foreach($request->input('types') as $type){
                    $type_insert = Type::where('type',$type)->get()->first();
                    $payment->types()->save($type_insert, [
                        'amount' => $request->input('types_amount')[$position],
                        'price' => $request->input('types_price')[$position],
                        'subtotal' => $request->input('types_subtotal')[$position],
                    ]);
                    $position++;
                }
            }
            DB::commit();
            event(new PaymentRegisteredEvent('Payment Registered'));
            event(new InventoryLoadEvent($payment));
            if(!$request->header('Authorization'))
                return redirect('/payment');
            return response()->json(_('Payment registered',200));
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
            $company = Company::where('company',$request->input('company'))->get()->first();
            $currency = Currency::where('currency',$request->input('currency'))->get()->first();
            $payment->update([
                'company_id' => $company->id,
                'currency_id' => $currency->id,
                'total' => $request->input('total'),
            ]);
            DB::commit();
            event(new PaymentRegisteredEvent(_('Payment Updated')));
            if(!$request->header('Authorization'))
                return redirect('/payment');
            return response()->json(_('Payment updated',200));
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
            event(new PaymentRegisteredEvent(_('Payment Deleted')));
            return redirect('/payment');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function store_tags(StorePaymentTagsRequest $request, Payment $payment)
    {
        DB::beginTransaction();
        try {
            if(!$request->input('tags')){
                $payment->tags()->syncWithPivotValues($payment->tags->pluck('id')->toArray(),['deleted_at'=>Carbon::now()],false);
                DB::commit();
                return redirect()->back();
            }
            if($payment->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
                $payment->tags()->syncWithPivotValues($payment->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
            if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($payment->tags->pluck('id')->isNotEmpty()))
                $payment->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($payment->tags->pluck('id')),false);
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function api_payments_last_month()
    {
        $data = TotalDayPayments::collection(
            Payment::whereBetween('created_at',[Carbon::now()->startOfMonth()->toDateTimeString(),Carbon::now()->endOfDay()->toDateTimeString()])->get()
        );
        return compact('data');
    }

    /*
    * Gets the payments within the last 6 monts for a line graph, x = created_at (dates), y = total (total per payment)
    */
    public function api_payments_last_six_months()
    {
        /**
         * while using apexcharts with zoomable layout, series is expecting an array of objects {x=>value,y=?value}
         * nesbot/Carbon timestam attribute returs timestamp upto second, while ApexCharts is expectin a milisecond timestamp
         * thats why we are multiplying by 1000
         */
        $data = TotalDayPayments::collection(
            Payment::whereBetween('created_at',[Carbon::now()->subMonths(6)->toDateTimeString(),Carbon::now()->endOfDay()->toDateTimeString()])->get()
        )->sortBy('created_at');
        $six_months_x_axis = $data->pluck('created_at');
        $six_months_y_axis = $data->pluck('total');

        $data = collect();
        for($i=0;$i<count($six_months_x_axis);$i++){
            //pushing the object {x=>value,y=>value} to the array, while formating the timestamp from second to milisecond
            $data->push(['x'=>$six_months_x_axis[$i]->timestamp*1000,'y'=>$six_months_y_axis[$i]]);
        }
        return compact('data','six_months_x_axis','six_months_y_axis');
    }

    public function type_code(Payment $payment, Request $request)
    {
        DB::beginTransaction();
        try {
            $payment->codes_paid()->create(['code' => mb_strtoupper($request->input('code')),]);
            event(new InventoryLoadTypeEvent($request->input('code')));
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}