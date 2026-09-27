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
        if (Schema::hasColumn('talents', 'job_posting_id')) {
            Schema::table('talents', function (Blueprint $table) {
                $table->dropColumn('job_posting_id');
            });
        }
        if (Schema::hasColumn('talents', 'custom_answers')) {
            Schema::table('talents', function (Blueprint $table) {
                $table->dropColumn('custom_answers');
            });
        }
        
        Schema::table('talents', function (Blueprint $table) {
            $table->foreignId('job_posting_id')->nullable()->after('department_id')->constrained('job_postings')->nullOnDelete();
            $table->json('custom_answers')->nullable()->after('general_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->dropColumn(['job_id', 'custom_answers']);
        });
    }
};
