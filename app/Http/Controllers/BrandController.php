<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use Illuminate\Support\Facades\DB;
use App\Events\BrandRegisteredEvent;
use App\Http\Requests\BrandStoreRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('brand.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = BrandResource::collection(Brand::all());
        return compact('data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Brand::create([
                'brand' => mb_strtoupper($request->input('brand')),
            ]);
            DB::commit();
            event(new BrandRegisteredEvent('Brand Registered'));
            if(!$request->header('Authorization'))
                return redirect('/brand');
            return response()->json('Brand registered',200);
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
        return view('brand.create');
    }

    /**
     * Display the specified resource.
     */
    public function edit($brand)
    {
        $brand = Brand::where('brand',$brand)->get()->first();
        return view('brand.edit',['brand' => $brand]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandStoreRequest $request, Brand $brand)
    {
        DB::beginTransaction();
        try {
            $brand->update(['brand' => mb_strtoupper($request->input('brand'))]);
            DB::commit();
            event(new BrandRegisteredEvent('Brand Updated'));
            if(!$request->header('Authorization'))
                return redirect('/brand');
            return response()->json('Brand updated',200);
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
    public function destroy($brand)
    {
        DB::beginTransaction();
        try {
            $brand = Brand::where('brand',$brand)->get()->first()->delete();
            DB::commit();
            event(new BrandRegisteredEvent('Brand Deleted'));
            return redirect('/brand');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
