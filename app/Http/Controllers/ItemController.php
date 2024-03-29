<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\DB;
use App\Events\ItemRegisteredEvent;
use App\Http\Requests\ItemStoreRequest;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('item.index');
    }
        
    public function api_index()
    {   
        $data = ItemResource::collection(Item::all());
        return compact('data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Item::create([
                'item' => mb_strtoupper($request->input('item')),
            ]);
            DB::commit();
            event(new ItemRegisteredEvent('Item Registered'));
            if(!$request->header('Authorization'))
                return redirect('/item');
            return response()->json('Item registered',200);
        } catch(\Exception $e) {
            DB::rollBack();
            if(!$request->header('Authorization'))
                return redirect()->back()->with('message', $e->getMessage());
            return response()->json($e->getMessage(),200);
        }
    }

    /**
     * Display the create form
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($item)
    {
        $item = Item::where('item',$item)->get()->first();
        return view('item.edit',['item' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemStoreRequest $request, Item $item)
    {
        DB::beginTransaction();
        try {
            $item->update(['item' => mb_strtoupper($request->input('item'))]);
            DB::commit();
            event(new ItemRegisteredEvent('Item Updated'));
            if(!$request->header('Authorization'))
                return redirect('/item');
            return response()->json('Item updated',200);
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
    public function destroy($item)
    {
        DB::beginTransaction();
        try {
            $item = Item::where('item',$item)->get()->first()->delete();
            DB::commit();
            event(new ItemRegisteredEvent('Item Deleted'));
            return redirect('/item');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
