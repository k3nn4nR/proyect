<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Item;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Resources\CodeResource;
use Illuminate\Support\Facades\DB;
use App\Events\CodeRegisteredEvent;

use App\Http\Requests\CodeStoreRequest;
use App\Http\Requests\StoreCodeTagsRequest;
use App\Http\Requests\CodeUpdateRequest;
use Carbon\Carbon;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('code.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = CodeResource::collection(Code::all()->sortBy('code'));
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('code.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CodeStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $store = ($request->input('brand')) ? Brand::where('brand',$request->input('brand'))->get()->first() : 
                     ($request->input('type') ? Type::where('type',$request->input('type'))->get()->first() : 
                     ($request->input('currency') ? Currency::where('currency',$request->input('currency'))->get()->first() : 
                     Item::where('item',$request->input('item'))->get()->first()));
            $store->codes()->create(['code' => mb_strtoupper($request->input('code')),]);
            DB::commit();
            event(new CodeRegisteredEvent(_('Code Registered')));
            if(!$request->header('Authorization'))
                return redirect('/code');
            return response()->json(_('Code registered',200));
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
    public function show(Code $code)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code)
    {
        $code = Code::where('code',$code)->get()->first();
        return view('code.edit',['code' => $code]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CodeUpdateRequest $request, Code $code)
    {
        DB::beginTransaction();
        try {
            $code->update(['code' => mb_strtoupper($request->input('code'))]);
            DB::commit();
            event(new CodeRegisteredEvent(_('Code Updated')));
            if(!$request->header('Authorization'))
                return redirect('/code');
            return response()->json(_('Code updated',200));
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
    public function destroy($code)
    {
        DB::beginTransaction();
        try {
            $code = Code::where('code',$code)->get()->first()->delete();
            DB::commit();
            event(new CodeRegisteredEvent(_('Code Deleted')));
            return redirect('/code');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    
    public function store_tags(StoreCodeTagsRequest $request, Code $code)
    {
        DB::beginTransaction();
        try {
            if(!$request->input('tags')){
                $code->tags()->syncWithPivotValues($code->tags->pluck('id')->toArray(),['deleted_at'=>Carbon::now()],false);
                DB::commit();
                return redirect()->back();
            }
            if($code->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
                $code->tags()->syncWithPivotValues($code->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
            if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($code->tags->pluck('id')->isNotEmpty()))
                $code->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($code->tags->pluck('id')),false);
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
