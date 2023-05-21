<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use App\Http\Resources\CodeResource;
use Illuminate\Support\Facades\DB;
use App\Events\CodeRegisteredEvent;
use App\Http\Requests\CodeStoreRequest;

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
        $data = CodeResource::collection(Code::all());
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
            Code::create([
                'code' => mb_strtoupper($request->input('code')),
            ]);
            DB::commit();
            event(new CodeRegisteredEvent('Code Registered'));
            if(!$request->header('Authorization'))
                return redirect('/code');
            return response()->json('Code registered',200);
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
    public function update(CodeStoreRequest $request, Code $code)
    {
        DB::beginTransaction();
        try {
            $code->update(['code' => mb_strtoupper($request->input('code'))]);
            DB::commit();
            event(new CodeRegisteredEvent('Code Updated'));
            if(!$request->header('Authorization'))
                return redirect('/code');
            return response()->json('Code updated',200);
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
            event(new CodeRegisteredEvent('Code Deleted'));
            return redirect('/code');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
