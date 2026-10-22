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
        if (Schema::hasTable('leaves')) {
            Schema::table('leaves', function (Blueprint $table) {
                if (!Schema::hasColumn('leaves', 'manager_approved_at')) {
                    $table->timestamp('manager_approved_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('leaves', 'manager_approved_by')) {
                    $table->unsignedBigInteger('manager_approved_by')->nullable()->after('manager_approved_at');
                }
                if (!Schema::hasColumn('leaves', 'manager_notes')) {
                    $table->text('manager_notes')->nullable()->after('manager_approved_by');
                }
                if (!Schema::hasColumn('leaves', 'hr_approved_at')) {
                    $table->timestamp('hr_approved_at')->nullable()->after('manager_notes');
                }
                if (!Schema::hasColumn('leaves', 'hr_approved_by')) {
                    $table->unsignedBigInteger('hr_approved_by')->nullable()->after('hr_approved_at');
                }
            });
        }

        if (Schema::hasTable('excuses')) {
            Schema::table('excuses', function (Blueprint $table) {
                if (!Schema::hasColumn('excuses', 'manager_approved_at')) {
                    $table->timestamp('manager_approved_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('excuses', 'manager_approved_by')) {
                    $table->unsignedBigInteger('manager_approved_by')->nullable()->after('manager_approved_at');
                }
                if (!Schema::hasColumn('excuses', 'manager_notes')) {
                    $table->text('manager_notes')->nullable()->after('manager_approved_by');
                }
                if (!Schema::hasColumn('excuses', 'hr_approved_at')) {
                    $table->timestamp('hr_approved_at')->nullable()->after('manager_notes');
                }
                if (!Schema::hasColumn('excuses', 'hr_approved_by')) {
                    $table->unsignedBigInteger('hr_approved_by')->nullable()->after('hr_approved_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leaves')) {
            Schema::table('leaves', function (Blueprint $table) {
                $columns = ['manager_approved_at', 'manager_approved_by', 'manager_notes', 'hr_approved_at', 'hr_approved_by'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('leaves', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('excuses')) {
            Schema::table('excuses', function (Blueprint $table) {
                $columns = ['manager_approved_at', 'manager_approved_by', 'manager_notes', 'hr_approved_at', 'hr_approved_by'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('excuses', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
