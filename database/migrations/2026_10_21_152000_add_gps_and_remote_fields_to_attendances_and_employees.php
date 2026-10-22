<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'check_in_lat')) {
                $table->decimal('check_in_lat', 10, 7)->nullable()->after('check_in_time');
            }
            if (!Schema::hasColumn('attendances', 'check_in_lng')) {
                $table->decimal('check_in_lng', 10, 7)->nullable()->after('check_in_lat');
            }
            if (!Schema::hasColumn('attendances', 'check_out_lat')) {
                $table->decimal('check_out_lat', 10, 7)->nullable()->after('check_out_time');
            }
            if (!Schema::hasColumn('attendances', 'check_out_lng')) {
                $table->decimal('check_out_lng', 10, 7)->nullable()->after('check_out_lat');
            }
            if (!Schema::hasColumn('attendances', 'distance_meters')) {
                $table->integer('distance_meters')->nullable()->after('check_out_lng');
            }
            if (!Schema::hasColumn('attendances', 'is_remote')) {
                $table->boolean('is_remote')->default(false)->after('distance_meters');
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'is_remote')) {
                $table->boolean('is_remote')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $cols = [];
            foreach (['check_in_lat', 'check_in_lng', 'check_out_lat', 'check_out_lng', 'distance_meters', 'is_remote'] as $col) {
                if (Schema::hasColumn('attendances', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'is_remote')) {
                $table->dropColumn(['is_remote']);
            }
        });
    }
};
