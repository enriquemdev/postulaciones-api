<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\ApplicationStatus;
use App\Models\Availability;
use App\Models\EmploymentType;
use App\Models\WorkModality;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\Storage;

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

        $applications = $this->applicationService->listApplications($perPage);

        return response()->json($applications, 200);
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

    public function downloadCv(Application $application)
    {
        // Get the file's absolute path
        $path = Storage::path($application->cv_path);

        // Verify file's existance
        if (!file_exists($path)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        $file_name = "CV " . $application->applicant_names . ' ' . $application->applicant_last_names . '.pdf';

        // Download the file
        return response()->download($path, $file_name);
    }

    public function getEmploymentTypes () {
        return EmploymentType::select('id', 'employment_type_name', 'employment_type_code')->get();
    }

    public function getApplicationStatuses () {
        return ApplicationStatus::select('id', 'application_status_name', 'application_status_code')->get();
    }

    public function getWorkModalities () {
        return WorkModality::select('id', 'work_mdality_name', 'work_modality_code')->get();
    }

    public function getAvailabilities () {
        return Availability::select('id', 'availability_name', 'availability_code')->get();
    }
}
