<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
    public function createApplication(array $data)
    {
        try {
            DB::beginTransaction();
            
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
                'monthly_expected_salary' => $data['monthly_expected_salary'],
                'availability_id' => $data['availability_id'],
                'application_status_id' => 1,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'work_modality_id' => $data['work_modality_id']
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
            throw $e;
        }
    }
}