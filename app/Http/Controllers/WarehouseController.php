<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\WarehouseResource;
use Illuminate\Support\Facades\DB;
use App\Events\WarehouseRegisteredEvent;
use App\Http\Requests\WarehouseStoreRequest;
use App\Http\Requests\StoreWarehouseTagsRequest;
use Carbon\Carbon;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('warehouse.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = WarehouseResource::collection(Warehouse::all());
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Warehouse::create([
                'warehouse' => mb_strtoupper($request->input('warehouse')),
            ]);
            DB::commit();
            event(new WarehouseRegisteredEvent(_('Warehouse Registered')));
            if(!$request->header('Authorization'))
                return redirect('/warehouse');
            return response()->json(_('Warehouse registered',200));
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
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $warehouse)
    {
        $warehouse = Warehouse::where('warehouse',$warehouse)->get()->first();
        return view('warehouse.edit',['warehouse' => $warehouse]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseStoreRequest $request, Warehouse $warehouse)
    {
        DB::beginTransaction();
        try {
            $warehouse->update(['warehouse' => mb_strtoupper($request->input('warehouse'))]);
            DB::commit();
            event(new WarehouseRegisteredEvent(_('Warehouse Updated')));
            if(!$request->header('Authorization'))
                return redirect('/warehouse');
            return response()->json(_('Warehouse updated',200));
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
    public function destroy($warehouse)
    {
        DB::beginTransaction();
        try {
            $warehouse = Warehouse::where('warehouse',$warehouse)->get()->first()->delete();
            DB::commit();
            event(new WarehouseRegisteredEvent(_('Warehouse Deleted')));
            return redirect('/warehouse');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    
    public function store_tags(StoreWarehouseTagsRequest $request, Warehouse $warehouse)
    {
        DB::beginTransaction();
        try {
            if(!$request->input('tags')){
                $warehouse->tags()->syncWithPivotValues($warehouse->tags->pluck('id')->toArray(),['deleted_at'=>Carbon::now()],false);
                DB::commit();
                return redirect()->back();
            }
            if($warehouse->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
                $warehouse->tags()->syncWithPivotValues($warehouse->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
            if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($warehouse->tags->pluck('id')->isNotEmpty()))
                $warehouse->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($warehouse->tags->pluck('id')),false);
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
