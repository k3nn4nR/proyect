<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\TypeResource;
use Illuminate\Support\Facades\DB;
use App\Events\TypeRegisteredEvent;
use App\Http\Requests\TypeStoreRequest;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('type.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = TypeResource::collection(Type::all());
        return compact('data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $brand = Brand::where('brand',$request->input('brand'))->get()->first();
            Type::create([
                'type' => mb_strtoupper($request->input('type')),
                'brand_id' => $brand->id
            ]);
            DB::commit();
            event(new TypeRegisteredEvent('Type Registered'));
            if(!$request->header('Authorization'))
                return redirect('/type');
            return response()->json('Type registered',200);
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
        return view('type.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($type)
    {
        $type = Type::where('type',$type)->get()->first();
        return view('type.edit',['type' => $type]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeStoreRequest $request, Type $type)
    {
        DB::beginTransaction();
        try {
            $type->update(['type' => mb_strtoupper($request->input('type'))]);
            DB::commit();
            event(new TypeRegisteredEvent('Tag Updated'));
            if(!$request->header('Authorization'))
                return redirect('/tag');
            return response()->json('Tag updated',200);
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
    public function destroy( $type)
    {
        DB::beginTransaction();
        try {
            $type = Type::where('type',$type)->get()->first()->delete();
            DB::commit();
            event(new TypeRegisteredEvent('Type Deleted'));
            return redirect('/type');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
