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
        Schema::table('activity_log', function (Blueprint $table) {
            try {
                if (!$this->hasIndex('activity_log', 'activity_log_event_index') && !$this->hasIndex('activity_log', 'event')) {
                    $table->index('event');
                }
            } catch (\Throwable) {}

            try {
                if (!$this->hasIndex('activity_log', 'activity_log_created_at_index') && !$this->hasIndex('activity_log', 'created_at')) {
                    $table->index('created_at');
                }
            } catch (\Throwable) {}
        });
    }

    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            try {
                $table->dropIndex(['event']);
            } catch (\Throwable) {}

            try {
                $table->dropIndex(['created_at']);
            } catch (\Throwable) {}
        });
    }
};
