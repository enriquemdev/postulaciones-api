<?php

namespace Database\Seeders;

use App\Models\WorkModality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_time = now();

        $work_modalities = [
            [
                'work_modality_name' => 'Presencial',
                'work_modality_code' => 'onsite',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'work_modality_name' => 'Híbrida',
                'work_modality_code' => 'hybrid',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
            [
                'work_modality_name' => 'En remoto',
                'work_modality_code' => 'remote',
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ],
        ];

        WorkModality::insert($work_modalities);
    }
}
