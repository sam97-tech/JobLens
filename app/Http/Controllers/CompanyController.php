<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();

        return response()->json($companies); //
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        $company = Company::create($validated);

        return response()->json($company, 201); //
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }
    
    

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        $company->update($validated);

        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully'
        ]);
    }
}
