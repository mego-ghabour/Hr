<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talents', function (Blueprint $table) {
            $table->id();
            $table->string('talent_id')->unique();

            // Personal Information
            $table->string('full_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // Professional Details
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->decimal('years_of_experience', 4, 1)->unsigned()->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();

            // Enums (stored as strings, cast in Model)
            $table->string('seniority_level')->nullable();
            $table->string('work_preference')->nullable();
            $table->string('availability')->nullable();

            // Lookup Foreign Keys
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('talent_statuses')->nullOnDelete();

            // Users Foreign Keys
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_recruiter_id')->nullable()->constrained('users')->nullOnDelete();

            // Dates & Notes
            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('last_profile_review_at')->nullable();
            $table->text('general_notes')->nullable();
            $table->boolean('is_potential_duplicate')->default(false);

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes for search & filtering performance
            $table->index('full_name');
            $table->index('current_job_title');
            $table->index('current_company');
            $table->index('seniority_level');
            $table->index('last_profile_review_at');
            $table->index(['department_id', 'created_at']);
            $table->index(['status_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talents');
    }
};
