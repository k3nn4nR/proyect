<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Brand;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TypeResource;
use Illuminate\Support\Facades\DB;
use App\Events\TypeRegisteredEvent;
use App\Http\Requests\TypeStoreRequest;
use App\Http\Requests\StoreTypeTagsRequest;
use Carbon\Carbon;

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
        $data = TypeResource::collection(Type::all()->sortBy('type'));
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
            event(new TypeRegisteredEvent(_('Type Registered')));
            if(!$request->header('Authorization'))
                return redirect('/type');
            return response()->json(_('Type registered',200));
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
            $brand = Brand::where('brand',$request->input('brand'))->get()->first();
            $type->update([
                'type' => mb_strtoupper($request->input('type')),
                'brand_id' => $brand->id
            ]);
            DB::commit();
            event(new TypeRegisteredEvent(_('Type Updated')));
            if(!$request->header('Authorization'))
                return redirect('/type');
            return response()->json(_('Type updated',200));
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
            event(new TypeRegisteredEvent(_('Type Deleted')));
            return redirect('/type');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function store_tags(StoreTypeTagsRequest $request, Type $Type)
    {
        DB::beginTransaction();
        try {
            if(!$request->input('tags')){
                $Type->tags()->syncWithPivotValues($Type->tags->pluck('id')->toArray(),['deleted_at'=>Carbon::now()],false);
                DB::commit();
                return redirect()->back();
            }
            if($Type->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
                $Type->tags()->syncWithPivotValues($Type->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
            if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($Type->tags->pluck('id')->isNotEmpty()))
                $Type->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($Type->tags->pluck('id')),false);
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
