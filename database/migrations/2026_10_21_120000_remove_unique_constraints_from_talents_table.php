<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function hasIndex(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE `Key_name` = ?", [$indexName]);
            return !empty($indexes);
        } catch (\Throwable) {
            return false;
        }
    }

    public function up(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            try {
                if ($this->hasIndex('talents', 'talents_email_unique')) {
                    $table->dropUnique('talents_email_unique');
                }
            } catch (\Throwable) {}

            try {
                if ($this->hasIndex('talents', 'talents_phone_unique')) {
                    $table->dropUnique('talents_phone_unique');
                }
            } catch (\Throwable) {}

            try {
                if (!$this->hasIndex('talents', 'talents_email_index') && !$this->hasIndex('talents', 'email')) {
                    $table->index('email');
                }
            } catch (\Throwable) {}

            try {
                if (!$this->hasIndex('talents', 'talents_phone_index') && !$this->hasIndex('talents', 'phone')) {
                    $table->index('phone');
                }
            } catch (\Throwable) {}
        });
    }

    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            try {
                $table->dropIndex(['email']);
            } catch (\Throwable) {}

            try {
                $table->dropIndex(['phone']);
            } catch (\Throwable) {}

            try {
                $table->unique('email', 'talents_email_unique');
            } catch (\Throwable) {}

            try {
                $table->unique('phone', 'talents_phone_unique');
            } catch (\Throwable) {}
        });
    }
};
