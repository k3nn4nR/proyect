<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\DB;
use App\Events\TagRegisteredEvent;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\StoreTagTagsRequest;
use Carbon\Carbon;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tag.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = TagResource::collection(Tag::all()->sortBy('tag'));
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Tag::create([
                'tag' => mb_strtoupper($request->input('tag')),
            ]);
            DB::commit();
            event(new TagRegisteredEvent(_('Tag Registered')));
            if(!$request->header('Authorization'))
                return redirect('/tag');
            return response()->json(_('Tag registered',200));
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
    public function show(Tag $tag)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tag)
    {
        $tag = Tag::where('tag',$tag)->get()->first();
        return view('tag.edit',['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagStoreRequest $request, Tag $tag)
    {
        DB::beginTransaction();
        try {
            $tag->update(['tag' => mb_strtoupper($request->input('tag'))]);
            DB::commit();
            event(new TagRegisteredEvent(_('Tag Updated')));
            if(!$request->header('Authorization'))
                return redirect('/tag');
            return response()->json(_('Tag updated',200));
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
    public function destroy($tag)
    {
        DB::beginTransaction();
        try {
            $tag = Tag::where('tag',$tag)->get()->first()->delete();
            DB::commit();
            event(new TagRegisteredEvent(_('Tag Deleted')));
            return redirect('/tag');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    
    public function store_tags(StoreTagTagsRequest $request, Tag $tag)
    {
        DB::beginTransaction();
        try {
            if(!$request->input('tags')){
                $tag->tags()->syncWithPivotValues($tag->tags->pluck('id')->toArray(),['deleted_at'=>Carbon::now()],false);
                DB::commit();
                return redirect()->back();
            }
            if($tag->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
                $tag->tags()->syncWithPivotValues($tag->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
            if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($tag->tags->pluck('id')->isNotEmpty()))
                $tag->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($tag->tags->pluck('id')),false);
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function api_current_tag_goods()
    {
        /**
         * $inventories = InventoryResource::collection(Warehouse::all());
         * suatoria de bienes totales del inventario
        */
        $tags = Tag::with('items.inventories')->get();
        $data = $tags->map(function ($tag) {
            if($tag->items->isNotEmpty())
                return ['x'=>$tag->tag,'y'=>
                    $tag->items->reduce(function (?int $carry, $item) {
                        return $carry + $item->inventories->sum('amount');
                    })];
            return ['x'=>$tag->tag,'y'=>0];
        });
        return compact('data');
    }

    public function api_tag_items($tag)
    {
        $checked = Tag::where('tag',$tag)->get()->first()->items->map(function ($item) {
            return ['id'=>$item->id,'item'=>$item->item];
        });
        dd($checked);
        $existings = Item::orderBy('item')->get();
        dd($existings);

        // if($tag->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id'))->isNotEmpty())
        //     $tag->tags()->syncWithPivotValues($tag->tags->pluck('id')->diff(Tag::wherein('tag',$request->input('tags'))->pluck('id')),['deleted_at'=>Carbon::now()],false);
        // if(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($tag->tags->pluck('id')->isNotEmpty()))
        //     $tag->tags()->sync(Tag::wherein('tag',$request->input('tags'))->pluck('id')->diff($tag->tags->pluck('id')),false);
        return compact('data');
    }
}
