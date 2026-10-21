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
        Schema::table('employees', function (Blueprint $table) {
            if (!$this->hasIndex('employees', 'emp_status_idx')) {
                $table->index('status', 'emp_status_idx');
            }
            if (!$this->hasIndex('employees', 'emp_contract_end_idx')) {
                $table->index('contract_end_date', 'emp_contract_end_idx');
            }
            if (!$this->hasIndex('employees', 'emp_hire_date_idx')) {
                $table->index('hire_date', 'emp_hire_date_idx');
            }
            if (!$this->hasIndex('employees', 'emp_dept_status_idx')) {
                $table->index(['department_id', 'status'], 'emp_dept_status_idx');
            }
        });

        Schema::table('attendances', function (Blueprint $table) {
            if (!$this->hasIndex('attendances', 'att_emp_date_idx')) {
                $table->index(['employee_id', 'date'], 'att_emp_date_idx');
            }
            if (!$this->hasIndex('attendances', 'att_date_idx')) {
                $table->index('date', 'att_date_idx');
            }
            if (!$this->hasIndex('attendances', 'att_status_idx')) {
                $table->index('status', 'att_status_idx');
            }
        });

        Schema::table('leaves', function (Blueprint $table) {
            if (!$this->hasIndex('leaves', 'leaves_status_idx')) {
                $table->index('status', 'leaves_status_idx');
            }
            if (!$this->hasIndex('leaves', 'leaves_emp_status_idx')) {
                $table->index(['employee_id', 'status'], 'leaves_emp_status_idx');
            }
            if (!$this->hasIndex('leaves', 'leaves_dates_idx')) {
                $table->index(['start_date', 'end_date'], 'leaves_dates_idx');
            }
        });

        Schema::table('payrolls', function (Blueprint $table) {
            if (!$this->hasIndex('payrolls', 'pay_status_idx')) {
                $table->index('status', 'pay_status_idx');
            }
            if (!$this->hasIndex('payrolls', 'pay_year_month_idx')) {
                $table->index(['year', 'month'], 'pay_year_month_idx');
            }
        });

        if (Schema::hasTable('excuses')) {
            Schema::table('excuses', function (Blueprint $table) {
                if (!$this->hasIndex('excuses', 'excuses_status_idx')) {
                    $table->index('status', 'excuses_status_idx');
                }
                if (!$this->hasIndex('excuses', 'excuses_emp_date_idx')) {
                    $table->index(['employee_id', 'date'], 'excuses_emp_date_idx');
                }
            });
        }

        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                if (!$this->hasIndex('announcements', 'ann_active_created_idx')) {
                    $table->index(['is_active', 'created_at'], 'ann_active_created_idx');
                }
            });
        }

        if (Schema::hasTable('job_postings')) {
            Schema::table('job_postings', function (Blueprint $table) {
                if (!$this->hasIndex('job_postings', 'jobs_active_created_idx')) {
                    $table->index(['is_active', 'created_at'], 'jobs_active_created_idx');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if ($this->hasIndex('employees', 'emp_status_idx')) $table->dropIndex('emp_status_idx');
            if ($this->hasIndex('employees', 'emp_contract_end_idx')) $table->dropIndex('emp_contract_end_idx');
            if ($this->hasIndex('employees', 'emp_hire_date_idx')) $table->dropIndex('emp_hire_date_idx');
            if ($this->hasIndex('employees', 'emp_dept_status_idx')) $table->dropIndex('emp_dept_status_idx');
        });

        Schema::table('attendances', function (Blueprint $table) {
            if ($this->hasIndex('attendances', 'att_emp_date_idx')) $table->dropIndex('att_emp_date_idx');
            if ($this->hasIndex('attendances', 'att_date_idx')) $table->dropIndex('att_date_idx');
            if ($this->hasIndex('attendances', 'att_status_idx')) $table->dropIndex('att_status_idx');
        });

        Schema::table('leaves', function (Blueprint $table) {
            if ($this->hasIndex('leaves', 'leaves_status_idx')) $table->dropIndex('leaves_status_idx');
            if ($this->hasIndex('leaves', 'leaves_emp_status_idx')) $table->dropIndex('leaves_emp_status_idx');
            if ($this->hasIndex('leaves', 'leaves_dates_idx')) $table->dropIndex('leaves_dates_idx');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            if ($this->hasIndex('payrolls', 'pay_status_idx')) $table->dropIndex('pay_status_idx');
            if ($this->hasIndex('payrolls', 'pay_year_month_idx')) $table->dropIndex('pay_year_month_idx');
        });

        if (Schema::hasTable('excuses')) {
            Schema::table('excuses', function (Blueprint $table) {
                if ($this->hasIndex('excuses', 'excuses_status_idx')) $table->dropIndex('excuses_status_idx');
                if ($this->hasIndex('excuses', 'excuses_emp_date_idx')) $table->dropIndex('excuses_emp_date_idx');
            });
        }

        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                if ($this->hasIndex('announcements', 'ann_active_created_idx')) $table->dropIndex('ann_active_created_idx');
            });
        }

        if (Schema::hasTable('job_postings')) {
            Schema::table('job_postings', function (Blueprint $table) {
                if ($this->hasIndex('job_postings', 'jobs_active_created_idx')) $table->dropIndex('jobs_active_created_idx');
            });
        }
    }
};
