<?php

namespace Database\Seeders;

use App\Models\Availability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_time = now();

        $availabilities = [
            [
                'availability_name' => 'Inmediata',
                'availability_code' => 'immediate',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'availability_name' => '1 Semana',
                'availability_code' => 'one_week',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'availability_name' => '2 Semanas',
                'availability_code' => 'two_weeks',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'availability_name' => '1 Mes',
                'availability_code' => 'one_month',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'availability_name' => 'Más de un mes',
                'availability_code' => 'more_than_one_month',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
        ];

        Availability::insert($availabilities);
    }
}
