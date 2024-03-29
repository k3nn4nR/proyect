<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CompanyResource;
use App\Events\CompanyRegisteredEvent;
use App\Http\Requests\CompanyStoreRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('company.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        $data = CompanyResource::collection(Company::all());
        return compact('data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Company::create([
                'company' => mb_strtoupper($request->input('company')),
            ]);
            DB::commit();
            event(new CompanyRegisteredEvent('Company Registered'));
            if(!$request->header('Authorization'))
                return redirect('/company');
            return response()->json('Company registered',200);
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
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($company)
    {
        $company = Company::where('company',$company)->get()->first();
        return view('company.edit',['company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyStoreRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $company->update(['company' => mb_strtoupper($request->input('company'))]);
            DB::commit();
            event(new CompanyRegisteredEvent('Company Updated'));
            if(!$request->header('Authorization'))
                return redirect('/company');
            return response()->json('Company updated',200);
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
    public function destroy($company)
    {
        DB::beginTransaction();
        try {
            $company = Company::where('company',$company)->get()->first()->delete();
            DB::commit();
            event(new CompanyRegisteredEvent('Company Deleted'));
            return redirect('/company');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
