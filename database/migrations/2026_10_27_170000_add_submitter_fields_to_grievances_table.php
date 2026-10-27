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
        if (Schema::hasTable('grievances')) {
            Schema::table('grievances', function (Blueprint $table) {
                if (! Schema::hasColumn('grievances', 'submitter_name')) {
                    $table->string('submitter_name')->nullable()->after('employee_id');
                }
                if (! Schema::hasColumn('grievances', 'submitter_contact')) {
                    $table->string('submitter_contact')->nullable()->after('submitter_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('grievances')) {
            Schema::table('grievances', function (Blueprint $table) {
                if (Schema::hasColumn('grievances', 'submitter_contact')) {
                    $table->dropColumn('submitter_contact');
                }
                if (Schema::hasColumn('grievances', 'submitter_name')) {
                    $table->dropColumn('submitter_name');
                }
            });
        }
    }
};
