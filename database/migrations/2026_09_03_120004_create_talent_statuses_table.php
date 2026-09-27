<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('gray');
            $table->boolean('is_default')->default(false);
            $table->integer('order_column')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_statuses');
    }
};
