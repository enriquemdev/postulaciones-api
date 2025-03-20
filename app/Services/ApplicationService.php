<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class ApplicationService
{
    public function listApplications(int $perPage): LengthAwarePaginator
    {
        $applications = Application::with([
            'employmentType',
            'availability',
            'applicationStatus',
            'workModality',
            'educations',
            'experiences',
        ])->paginate($perPage);

        // Add a download cv url to each application
        $applications->getCollection()->transform(function ($application) {
            $application->cv_download_url = route('applications.cv.download', ['application' => $application->id]);
            return $application;
        });

        return $applications;
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
                'applicant_linkedin' => $data['applicant_linkedin'],
                'applicant_portfolio_link' => $data['applicant_portfolio_link'],
                'applicant_country' => $data['applicant_country'],
                'applicant_city' => $data['applicant_city'],
                'applicant_address' => $data['applicant_address'],
                'cv_path' => $cvPath,
                'cv_pages_count' => $pages_qty,
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