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
        Schema::table('employees', function (Blueprint $table) {
            // Personal Info
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('address')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Contract & Insurance
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('social_insurance_number')->nullable();
            $table->date('contract_end_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'marital_status',
                'address',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'manager_id',
                'social_insurance_number',
                'contract_end_date',
            ]);
        });
    }
};
