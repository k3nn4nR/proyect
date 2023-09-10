<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CurrencyResource;
use App\Events\CurrencyRegisteredEvent;
use App\Http\Requests\CurrencyStoreRequest;
use App\Http\Requests\StoreCurrencyTagsRequest;
use Carbon\Carbon;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('currency.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = CurrencyResource::collection(Currency::all()->sortBy('currency'));
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currency.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Currency::create([
                'currency' => mb_strtoupper($request->input('currency')),
            ]);
            DB::commit();
            event(new CurrencyRegisteredEvent(_('Currency Registered')));
            if(!$request->header('Authorization'))
                return redirect('/currency');
            return response()->json(_('Currency registered',200));
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
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($currency)
    {
        $currency = Currency::where('currency',$currency)->get()->first();
        return view('currency.edit',['currency' => $currency]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CurrencyStoreRequest $request, Currency $currency)
    {
        DB::beginTransaction();
        try {
            $currency->update(['currency' => mb_strtoupper($request->input('currency'))]);
            DB::commit();
            event(new CurrencyRegisteredEvent(_('Currency Updated')));
            if(!$request->header('Authorization'))
                return redirect('/currency');
            return response()->json(_('Currency updated',200));
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
    public function destroy( $currency)
    {
        DB::beginTransaction();
        try {
            $currency = Currency::where('currency',$currency)->get()->first()->delete();
            DB::commit();
            event(new CurrencyRegisteredEvent(_('Currency Deleted')));
            return redirect('/currency');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function store_tags(StoreCurrencyTagsRequest $request, Currency $currency)
    {
        DB::beginTransaction();
        try {
            if(!$request->input('tags')){
                $currency->tags()->syncWithPivotValues($currency->tags->pluck('id')->toArray(),['deleted_at'=>Carbon::now()],false);
                DB::commit();
                return redirect()->back();
            }
            if($currency->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
                $currency->tags()->syncWithPivotValues($currency->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
            if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($currency->tags->pluck('id')->isNotEmpty()))
                $currency->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($currency->tags->pluck('id')),false);
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
