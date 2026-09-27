<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // الاسم بالعربي
            $table->string('name_en')->nullable(); // الاسم بالانجليزي
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // إضافة القيم الافتراضية
        DB::table('availability_options')->insert([
            ['name' => 'متاح فوراً',          'name_en' => 'immediately',    'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'بعد أسبوعين',          'name_en' => 'two_weeks',      'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'بعد شهر',             'name_en' => 'one_month',      'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'بعد شهرين',            'name_en' => 'two_months',     'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'غير متاح حالياً',      'name_en' => 'not_available',  'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // إضافة عمود availability_option_id لجدول talents
        Schema::table('talents', function (Blueprint $table) {
            $table->foreignId('availability_option_id')->nullable()->after('availability')->constrained('availability_options')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\AvailabilityOption::class);
            $table->dropColumn('availability_option_id');
        });

        Schema::dropIfExists('availability_options');
    }
};
