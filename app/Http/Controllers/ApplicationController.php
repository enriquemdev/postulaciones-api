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
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationService $applicationService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Pagination parameters
        $page = (int) $request->query('page', 1); // Default to page 1, cast to int
        $perPage = (int) $request->query('page_size', 10); // Default to 10 items per page, cast to int

        // Filter parameters
        $filters = [];
        $filterInputs = $request->query('filters', []);
        foreach ($filterInputs as $index => $filter) {
            if (isset($filter['field'], $filter['operator'], $filter['value'])) {
                $filters[] = [
                    'field' => $filter['field'],
                    'operator' => $filter['operator'],
                    'value' => $filter['value'],
                ];
            }
        }

        // Sort parameters
        $sorts = [];
        $sortInputs = $request->query('sort', []);
        foreach ($sortInputs as $index => $sort) {
            if (isset($sort['field'], $sort['direction'])) {
                $sorts[] = [
                    'field' => $sort['field'],
                    'direction' => $sort['direction'],
                ];
            }
        }

        // Fetch applications with pagination, filters, and sorting
        try {
            $applications = $this->applicationService->listApplications($page, $perPage, $filters, $sorts);
        } catch (\Exception) {
            return response()->json(['message' => 'An error ocurred while trying to get the data'], 500);
        }

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
        return EmploymentType::select('id', 'employment_type_name as name', 'employment_type_code as code')->get();
    }

    public function getApplicationStatuses () {
        return ApplicationStatus::select('id', 'application_status_name as name', 'application_status_code as code')->get();
    }

    public function getWorkModalities () {
        return WorkModality::select('id', 'work_modality_name as name', 'work_modality_code as code')->get();
    }

    public function getAvailabilities () {
        return Availability::select('id', 'availability_name as name', 'availability_code as code')->get();
    }

    public function markAsSeen($id)
    {
        $application = Application::find($id);

        if (!$application) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }

        if ($application->applicationStatus->application_status_code !== 'sent') {
            return response()->json(['message' => 'La solicitud ya ha sido vista o no está en estado enviado'], 400);
        }

        $application_status_code = ApplicationStatus::where('application_status_code', 'seen')->first();

        $application->application_status_id = $application_status_code->id;
        $application->save();

        return response()->json([
            'message' => 'Solicitud marcada como vista',
            'application' => $application
        ], 200);
    }
}
