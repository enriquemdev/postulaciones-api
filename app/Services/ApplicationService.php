<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\EmploymentType;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class ApplicationService
{
    public function listApplications(int $page, int $perPage, array $filters = [], array $sorts = []): array
    {
        // Start building the query
        $query = Application::with([
            'employmentType',
            'availability',
            'applicationStatus',
            'workModality',
            'educations',
            'experiences',
        ]);

        // Define the columns to search in for the quick filter
        $quickFilterColumns = [
            'applicant_names' => 'applications.applicant_names',
            'applicant_last_names' => 'applications.applicant_last_names',
            'job_title' => 'applications.job_title',
            'company_name' => 'applications.company_name',
            'applicant_email' => 'applications.applicant_email',
            'applicant_phone' => 'applications.applicant_phone',
            'employment_type.employment_type_name' => 'employmentType.employment_type_name', // Updated to use relationship
            'application_status.application_status_name' => 'applicationStatus.application_status_name', // Updated to use relationship
        ];

        // Log the filters for debugging
        Log::info('Filters received:', $filters);

        // Separate quick filters (from quick filter bar) and regular filters (from column filters)
        $quickFilters = array_filter($filters, fn($filter) => $filter['operator'] === 'contains' && in_array($filter['field'], array_keys($quickFilterColumns)));
        $regularFilters = array_filter($filters, fn($filter) => !($filter['operator'] === 'contains' && in_array($filter['field'], array_keys($quickFilterColumns))));

        // Apply quick filters with OR condition
        if (!empty($quickFilters)) {
            $query->where(function ($q) use ($quickFilters, $quickFilterColumns) {
                foreach ($quickFilters as $filter) {
                    $field = $filter['field'];
                    $value = $filter['value'];
                    $column = $quickFilterColumns[$field];

                    if ($field === 'employment_type.employment_type_name') {
                        $q->orWhereHas('employmentType', function ($q2) use ($value) {
                            $q2->where('employment_type_name', 'LIKE', "%{$value}%");
                        });
                    } elseif ($field === 'application_status.application_status_name') {
                        $q->orWhereHas('applicationStatus', function ($q2) use ($value) {
                            $q2->where('application_status_name', 'LIKE', "%{$value}%");
                        });
                    } else {
                        $q->orWhere($column, 'LIKE', "%{$value}%");
                    }
                }
            });
        }

        // Apply regular filters with AND condition
        foreach ($regularFilters as $filter) {
            $field = $filter['field'];
            $operator = $filter['operator'];
            $value = $filter['value'];

            // Map frontend field names to database columns
            $fieldMapping = [
                'applicant_names' => 'applications.applicant_names',
                'applicant_last_names' => 'applications.applicant_last_names',
                'job_title' => 'applications.job_title',
                'company_name' => 'applications.company_name',
                'applicant_email' => 'applications.applicant_email',
                'applicant_phone' => 'applications.applicant_phone',
                'employment_type.employment_type_name' => 'employmentType.employment_type_name',
                'application_status.application_status_name' => 'applicationStatus.application_status_name',
            ];

            $column = $fieldMapping[$field] ?? $field;

            if ($field === 'employment_type.employment_type_name') {
                $query->whereHas('employmentType', function ($q) use ($operator, $value) {
                    switch ($operator) {
                        case 'contains':
                            $q->where('employment_type_name', 'LIKE', "%{$value}%");
                            break;
                        case 'equals':
                            $q->where('employment_type_name', '=', $value);
                            break;
                        case 'startsWith':
                            $q->where('employment_type_name', 'LIKE', "{$value}%");
                            break;
                        case 'endsWith':
                            $q->where('employment_type_name', 'LIKE', "%{$value}");
                            break;
                        case 'isEmpty':
                            $q->whereNull('employment_type_name');
                            break;
                        case 'isNotEmpty':
                            $q->whereNotNull('employment_type_name');
                            break;
                        case 'isAnyOf':
                            $q->whereIn('employment_type_name', (array) $value);
                            break;
                        default:
                            throw new Exception('Unsupported Operator');
                    }
                });
            } elseif ($field === 'application_status.application_status_name') {
                $query->whereHas('applicationStatus', function ($q) use ($operator, $value) {
                    switch ($operator) {
                        case 'contains':
                            $q->where('application_status_name', 'LIKE', "%{$value}%");
                            break;
                        case 'equals':
                            $q->where('application_status_name', '=', $value);
                            break;
                        case 'startsWith':
                            $q->where('application_status_name', 'LIKE', "{$value}%");
                            break;
                        case 'endsWith':
                            $q->where('application_status_name', 'LIKE', "%{$value}");
                            break;
                        case 'isEmpty':
                            $q->whereNull('application_status_name');
                            break;
                        case 'isNotEmpty':
                            $q->whereNotNull('application_status_name');
                            break;
                        case 'isAnyOf':
                            $q->whereIn('application_status_name', (array) $value);
                            break;
                        default:
                            throw new Exception('Unsupported Operator');
                    }
                });
            } else {
                // Apply the filter based on the operator
                switch ($operator) {
                    case 'contains':
                        $query->where($column, 'LIKE', "%{$value}%");
                        break;
                    case 'equals':
                        $query->where($column, '=', $value);
                        break;
                    case 'startsWith':
                        $query->where($column, 'LIKE', "{$value}%");
                        break;
                    case 'endsWith':
                        $query->where($column, 'LIKE', "%{$value}");
                        break;
                    case 'isEmpty':
                        $query->whereNull($column);
                        break;
                    case 'isNotEmpty':
                        $query->whereNotNull($column);
                        break;
                    case 'isAnyOf':
                        $query->whereIn($column, (array) $value);
                        break;
                    default:
                        throw new Exception('Unsupported Operator');
                }
            }
        }

        // Apply sorting
        foreach ($sorts as $sort) {
            $field = $sort['field'];
            $direction = $sort['direction'];

            // Map frontend field names to database columns or relationships
            if ($field === 'employment_type.employment_type_name') {
                $query->orderBy(
                    EmploymentType::select('employment_type_name')
                        ->whereColumn('employment_types.id', 'applications.employment_type_id')
                        ->take(1),
                    $direction
                );
            } elseif ($field === 'application_status.application_status_name') {
                $query->orderBy(
                    ApplicationStatus::select('application_status_name')
                        ->whereColumn('application_statuses.id', 'applications.application_status_id')
                        ->take(1),
                    $direction
                );
            } else {
                $fieldMapping = [
                    'applicant_names' => 'applications.applicant_names',
                    'applicant_last_names' => 'applications.applicant_last_names',
                    'job_title' => 'applications.job_title',
                    'company_name' => 'applications.company_name',
                    'applicant_email' => 'applications.applicant_email',
                    'applicant_phone' => 'applications.applicant_phone',
                ];

                $column = $fieldMapping[$field] ?? $field;

                // Ensure the direction is valid
                $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';

                $query->orderBy($column, $direction);
            }
        }

        // Log the generated SQL query for debugging
        Log::info('Generated SQL Query:', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings(),
        ]);

        // Fetch paginated results
        $paginatedApplications = $query->paginate($perPage, ['*'], 'page', $page);

        // Add a download cv url to each application
        $paginatedApplications->getCollection()->transform(function ($application) {
            $application->cv_download_url = route('applications.cv.download', ['application' => $application->id]);
            return $application;
        });

        // Log the results for debugging
        Log::info('Paginated Results:', [
            'total' => $paginatedApplications->total(),
            'items' => $paginatedApplications->items(),
        ]);

        // Transform the paginated result into the format expected by the frontend
        return [
            'data' => $paginatedApplications->items(),
            'total' => $paginatedApplications->total(),
            'current_page' => $paginatedApplications->currentPage(),
            'per_page' => $paginatedApplications->perPage(),
        ];
    }

    public function createApplication(array $data, $cvFile): Application
    {
        try {
            DB::beginTransaction();

            $cvPath = $cvFile->store('cvs');
            // $file = request()->file('file');
            // $fileName = time() . '_' . $file->getClientOriginalName();
            // $cvPath = Storage::disk('cvs')->putFileAs('', $file, $fileName);

            // Log::info($fileName);

            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile(Storage::path($cvPath));
            $pages_qty = count($pdf->getPages());

            $application = Application::create([
                'job_title' => $data['job_title'],
                'company_name' => $data['company_name'],
                'employment_type_id' => $data['employment_type_id'],
                'applicant_names' => $data['applicant_names'],
                'applicant_last_names' => $data['applicant_last_names'],
                'applicant_email' => $data['applicant_email'],
                'applicant_phone' => $data['applicant_phone'],
                'applicant_linkedin' => $data['applicant_linkedin'] ?? null,
                'applicant_portfolio_link' => $data['applicant_portfolio_link'] ?? null,
                'applicant_country' => $data['applicant_country'],
                'applicant_city' => $data['applicant_city'],
                'applicant_address' => $data['applicant_address'],
                'cv_path' => $cvPath ?? null,
                'cv_pages_count' => $pages_qty ?? null,
                'monthly_expected_salary' => $data['monthly_expected_salary'],
                'availability_id' => $data['availability_id'],
                'application_status_id' => 1,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'work_modality_id' => $data['work_modality_id'],
            ]);

            // Create education records
            foreach ($data['educations'] as $educationData) {
                $application->educations()->create($educationData);
            }

            // Create experience records
            foreach ($data['experiences'] as $experienceData) {
                $application->experiences()->create($experienceData);
            }

            DB::commit();

            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating application: ' . $e->getMessage());
            throw $e;
        }
    }
}
