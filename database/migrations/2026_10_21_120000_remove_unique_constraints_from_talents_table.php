<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropUnique('talents_email_unique');
            $table->dropUnique('talents_phone_unique');

            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);

            $table->unique('email', 'talents_email_unique');
            $table->unique('phone', 'talents_phone_unique');
        });
    }
};
