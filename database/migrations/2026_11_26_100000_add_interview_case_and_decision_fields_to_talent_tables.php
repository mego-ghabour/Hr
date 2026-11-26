<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            if (! Schema::hasColumn('talents', 'decision_outcome')) {
                $table->string('decision_outcome')->nullable()->after('general_notes');
                $table->index('decision_outcome');
            }

            if (! Schema::hasColumn('talents', 'decision_reason')) {
                $table->string('decision_reason')->nullable()->after('decision_outcome');
            }

            if (! Schema::hasColumn('talents', 'decision_summary')) {
                $table->text('decision_summary')->nullable()->after('decision_reason');
            }

            if (! Schema::hasColumn('talents', 'reapply_eligibility')) {
                $table->string('reapply_eligibility')->nullable()->after('decision_summary');
            }

            if (! Schema::hasColumn('talents', 'interview_salary_expectation')) {
                $table->decimal('interview_salary_expectation', 10, 2)->nullable()->after('reapply_eligibility');
            }

            if (! Schema::hasColumn('talents', 'recommended_job_posting_id')) {
                $table->foreignId('recommended_job_posting_id')
                    ->nullable()
                    ->after('interview_salary_expectation')
                    ->constrained('job_postings')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('talents', 'decision_by')) {
                $table->foreignId('decision_by')
                    ->nullable()
                    ->after('recommended_job_posting_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('talents', 'decision_at')) {
                $table->timestamp('decision_at')->nullable()->after('decision_by');
            }
        });

        Schema::table('talent_reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('talent_reviews', 'interview_stage')) {
                $table->string('interview_stage')->nullable()->after('reviewed_at');
            }

            if (! Schema::hasColumn('talent_reviews', 'attendance_status')) {
                $table->string('attendance_status')->nullable()->after('interview_stage');
            }

            if (! Schema::hasColumn('talent_reviews', 'recommendation')) {
                $table->string('recommendation')->nullable()->after('rating');
            }

            if (! Schema::hasColumn('talent_reviews', 'decision_reason')) {
                $table->string('decision_reason')->nullable()->after('recommendation');
            }

            if (! Schema::hasColumn('talent_reviews', 'strengths')) {
                $table->text('strengths')->nullable()->after('decision_reason');
            }

            if (! Schema::hasColumn('talent_reviews', 'weaknesses')) {
                $table->text('weaknesses')->nullable()->after('strengths');
            }

            if (! Schema::hasColumn('talent_reviews', 'interview_salary_expectation')) {
                $table->decimal('interview_salary_expectation', 10, 2)->nullable()->after('weaknesses');
            }

            if (! Schema::hasColumn('talent_reviews', 'notice_period')) {
                $table->string('notice_period')->nullable()->after('interview_salary_expectation');
            }
        });

        Schema::table('talent_notes', function (Blueprint $table) {
            if (! Schema::hasColumn('talent_notes', 'category')) {
                $table->string('category')->nullable()->default('general')->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('talent_notes', function (Blueprint $table) {
            if (Schema::hasColumn('talent_notes', 'category')) {
                $table->dropColumn('category');
            }
        });

        Schema::table('talent_reviews', function (Blueprint $table) {
            $columns = array_filter([
                'interview_stage',
                'attendance_status',
                'recommendation',
                'decision_reason',
                'strengths',
                'weaknesses',
                'interview_salary_expectation',
                'notice_period',
            ], fn (string $col) => Schema::hasColumn('talent_reviews', $col));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('talents', function (Blueprint $table) {
            if (Schema::hasColumn('talents', 'recommended_job_posting_id')) {
                $table->dropConstrainedForeignId('recommended_job_posting_id');
            }

            if (Schema::hasColumn('talents', 'decision_by')) {
                $table->dropConstrainedForeignId('decision_by');
            }

            $columns = array_filter([
                'decision_outcome',
                'decision_reason',
                'decision_summary',
                'reapply_eligibility',
                'interview_salary_expectation',
                'decision_at',
            ], fn (string $col) => Schema::hasColumn('talents', $col));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
