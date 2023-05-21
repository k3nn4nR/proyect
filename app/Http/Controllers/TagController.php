<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\DB;
use App\Events\TagRegisteredEvent;
use App\Http\Requests\TagStoreRequest;

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
        $data = TagResource::collection(Tag::all());
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
            event(new TagRegisteredEvent('Tag Registered'));
            if(!$request->header('Authorization'))
                return redirect('/tag');
            return response()->json('Tag registered',200);
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
            event(new TagRegisteredEvent('Tag Updated'));
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
    public function destroy($tag)
    {
        DB::beginTransaction();
        try {
            $tag = Tag::where('tag',$tag)->get()->first()->delete();
            DB::commit();
            event(new TagRegisteredEvent('Tag Deleted'));
            return redirect('/tag');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
