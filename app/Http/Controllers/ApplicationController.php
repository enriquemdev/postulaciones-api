<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
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
        $perPage = request()->query('per_page', 10); // Número de elementos por página (por defecto: 10)

        $applications = Application::with([
            'employmentType',
            'availability',
            'applicationStatus',
            'workModality',
            'educations',
            'experiences',
        ])->paginate($perPage);

        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated();

        try {
            $application = $this->applicationService->createApplication($validated, $request->file('cv'));
            return response()->json($application, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: could not create application record'], 500);
        }
    }
}
