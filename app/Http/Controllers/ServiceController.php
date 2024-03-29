<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\DB;
use App\Events\ServiceRegisteredEvent;
use App\Http\Requests\ServiceStoreRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('service.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = ServiceResource::collection(Service::all());
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Service::create([
                'service' => mb_strtoupper($request->input('service')),
            ]);
            DB::commit();
            event(new ServiceRegisteredEvent('Service Registered'));
            if(!$request->header('Authorization'))
                return redirect('/service');
            return response()->json('Service registered',200);
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
    public function show(Service $service)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($service)
    {
        $service = Service::where('service',$service)->get()->first();
        return view('service.edit',['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceStoreRequest $request, Service $service)
    {
        DB::beginTransaction();
        try {
            $service->update(['service' => mb_strtoupper($request->input('service'))]);
            DB::commit();
            event(new ServiceRegisteredEvent('Service Updated'));
            if(!$request->header('Authorization'))
                return redirect('/service');
            return response()->json('Service updated',200);
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
    public function destroy( $service)
    {
        DB::beginTransaction();
        try {
            $service = Service::where('service',$service)->get()->first()->delete();
            DB::commit();
            event(new ServiceRegisteredEvent('Service Deleted'));
            return redirect('/service');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
