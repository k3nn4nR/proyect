<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\Type;
use App\Models\Code;
use Illuminate\Http\Request;
use App\Http\Resources\InventoryResource;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventory.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = InventoryResource::collection(Warehouse::all());
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function consume($warehouse, $search)
    {
        dd(Warehouse::where('warehouse',$warehouse)->get()->first());
        dd(Item::where('item',$search)->get()->first());
        dd(Type::where('type',$search)->get()->first());
    }

    public function api_current_goods()
    {
        $items = Item::with('inventories')->get();
        $data = $items->map(function ($item) {
            return ['x'=>$item->item,'y'=>$item->inventories->sum('amount')];
        });
        return compact('data');
    }
}
