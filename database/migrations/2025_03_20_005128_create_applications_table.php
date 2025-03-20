<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('company_name');
            $table->foreignId('employment_type_id')->constrained('employment_types');

            $table->string('applicant_names');
            $table->string('applicant_last_names');
            $table->string('applicant_email');
            $table->string('applicant_phone');
            $table->string('applicant_linkedin')->nullable();
            $table->string('applicant_portfolio_link')->nullable();
            $table->string('applicant_country');
            $table->string('applicant_city');
            $table->string('applicant_address');
            $table->string('cv_path');
            $table->integer('cv_pages_count');

            $table->decimal('monthly_expected_salary', 12, 2);
            $table->foreignId('availability_id')->constrained('availabilities');

            $table->foreignId('application_status_id')->constrained('application_statuses');

            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
