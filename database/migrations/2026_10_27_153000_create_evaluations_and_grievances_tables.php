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
        Schema::create('evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('manager_to_employee'); // manager_to_employee, employee_to_manager, general_survey
            $table->string('target_type')->default('all'); // all, department, specific_role
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('target_role')->nullable();
            $table->text('description')->nullable();
            $table->json('questions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
            $table->index('target_type');
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_form_id')->constrained('evaluation_forms')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluated_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->boolean('is_anonymous')->default(false);
            $table->string('period')->nullable(); // e.g., "أكتوبر 2026", "الربع الثالث"
            $table->json('answers')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('action_plan')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('submitted'); // draft, submitted, reviewed
            $table->timestamps();
            $table->softDeletes();

            $table->index(['evaluation_form_id', 'status']);
            $table->index(['evaluated_employee_id', 'period']);
            $table->index('evaluator_id');
        });

        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('submitter_name')->nullable();
            $table->string('submitter_contact')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('type')->default('administrative'); // administrative, workplace, behavioral, suggestion, other
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('title');
            $table->text('content');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->boolean('against_manager')->default(false);
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('new'); // new, in_progress, resolved, rejected
            $table->text('hr_response')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type']);
            $table->index('ticket_number');
            $table->index(['employee_id', 'is_anonymous']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grievances');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('evaluation_forms');
    }
};
