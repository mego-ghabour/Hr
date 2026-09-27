<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->text('success_message')->nullable()->after('description');
            $table->string('brand_color')->nullable()->after('success_message');
            $table->string('form_layout')->default('single_page')->after('brand_color'); // 'single_page' or 'wizard'
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['success_message', 'brand_color', 'form_layout']);
        });
    }
};
