<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Services\ApplicationService;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationService $applicationService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated;

        try {
            $application = $this->applicationService->createApplication($validated, $request->file('cv'));
            return response()->json($application, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: could not create application record'], 500);
        }
    }
}
