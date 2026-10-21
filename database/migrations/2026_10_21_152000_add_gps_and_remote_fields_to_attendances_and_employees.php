<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('check_in_lat', 10, 7)->nullable()->after('check_in_time');
            $table->decimal('check_in_lng', 10, 7)->nullable()->after('check_in_lat');
            $table->decimal('check_out_lat', 10, 7)->nullable()->after('check_out_time');
            $table->decimal('check_out_lng', 10, 7)->nullable()->after('check_out_lat');
            $table->integer('distance_meters')->nullable()->after('check_out_lng');
            $table->boolean('is_remote')->default(false)->after('distance_meters');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('is_remote')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_lat',
                'check_in_lng',
                'check_out_lat',
                'check_out_lng',
                'distance_meters',
                'is_remote',
            ]);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['is_remote']);
        });
    }
};
