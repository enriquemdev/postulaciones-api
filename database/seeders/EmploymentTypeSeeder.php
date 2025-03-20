<?php

namespace Database\Seeders;

use App\Models\EmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_time = now();

        $employment_types = [
            [
                'employment_type_name' => 'Tiempo completo permanente',
                'employment_type_code' => 'full_time_permanent',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Tiempo completo indefinido',
                'employment_type_code' => 'full_time_indefinite',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Tiempo parcial regular',
                'employment_type_code' => 'part_time_regular',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Tiempo parcial flexible',
                'employment_type_code' => 'part_time_flexible',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Contrato a plazo fijo',
                'employment_type_code' => 'fixed_term_contract',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Contrato por proyecto',
                'employment_type_code' => 'project_based_contract',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Pasantía remunerada',
                'employment_type_code' => 'paid_internship',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Pasantía no remunerada',
                'employment_type_code' => 'unpaid_internship',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Trabajo independiente',
                'employment_type_code' => 'freelance',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'employment_type_name' => 'Trabajo de temporada',
                'employment_type_code' => 'seasonal_work',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
        ];

        EmploymentType::insert($employment_types);
    }
}
