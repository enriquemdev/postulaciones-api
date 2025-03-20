<?php

namespace Database\Seeders;

use App\Models\ApplicationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_time = now();

        $application_statuses = [
            [
                'application_status_name' => 'Enviada',
                'application_status_code' => 'sent',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'application_status_name' => 'Vista',
                'application_status_code' => 'seen',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
        ];

        ApplicationStatus::insert($application_statuses);
    }
}
