<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class EmployeeClockWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    protected string $view = 'filament.admin.widgets.employee-clock-widget';

    public static function canView(): bool
    {
        return auth()->check();
    }

    public function clockIn()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            Notification::make()->title('غير مصرح - حسابك غير مرتبط بملف موظف')->danger()->send();
            return;
        }

        $today = Carbon::today()->format('Y-m-d');
        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();

        if ($attendance) {
            Notification::make()->title('تم تسجيل حضورك مسبقاً اليوم')->warning()->send();
            return;
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'check_in_time' => Carbon::now()->format('H:i:s'),
            'status' => 'present',
        ]);

        Notification::make()
            ->title('تمت البصمة الإلكترونية بنجاح!')
            ->body('تم تسجيل حضورك اليوم في تمام الساعة ' . Carbon::now()->format('h:i A'))
            ->success()
            ->send();
    }

    public function clockOut()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return;
        }

        $today = Carbon::today()->format('Y-m-d');
        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();

        if (!$attendance) {
            Notification::make()->title('يجب تسجيل الحضور أولاً قبل الانصراف')->warning()->send();
            return;
        }

        if ($attendance->check_out_time) {
            Notification::make()->title('تم تسجيل الانصراف مسبقاً اليوم')->warning()->send();
            return;
        }

        $attendance->update([
            'check_out_time' => Carbon::now()->format('H:i:s'),
        ]);

        Notification::make()
            ->title('تم تسجيل الانصراف بنجاح!')
            ->body('نتمنى لك يوماً سعيداً. تم توثيق موعد انصرافك في تمام ' . Carbon::now()->format('h:i A'))
            ->success()
            ->send();
    }

    public function getAttendanceProperty()
    {
        $employee = Auth::user()?->employee;
        if (!$employee) return null;

        return Attendance::where('employee_id', $employee->id)
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->first();
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $employee = $user?->employee;
        $attendance = $this->attendance;

        $workedHoursText = null;
        if ($attendance && $attendance->check_in_time) {
            $in = Carbon::parse($attendance->check_in_time);
            $out = $attendance->check_out_time ? Carbon::parse($attendance->check_out_time) : Carbon::now();
            $totalMins = max(0, $in->diffInMinutes($out));
            $h = intdiv($totalMins, 60);
            $m = $totalMins % 60;
            $workedHoursText = "{$h} ساعة و {$m} دقيقة";
        }

        $monthlyAttendances = 0;
        if ($employee) {
            $monthlyAttendances = Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', Carbon::today()->month)
                ->whereYear('date', Carbon::today()->year)
                ->where('status', 'present')
                ->count();
        }

        // Stats for admin fallback
        $todayPresentCount = Attendance::where('date', Carbon::today()->format('Y-m-d'))->where('status', 'present')->count();
        $totalEmployeesCount = Employee::where('status', 'active')->count();

        return [
            'user' => $user,
            'employee' => $employee,
            'attendance' => $attendance,
            'workedHoursText' => $workedHoursText,
            'monthlyAttendances' => $monthlyAttendances,
            'todayPresentCount' => $todayPresentCount,
            'totalEmployeesCount' => $totalEmployeesCount,
        ];
    }
}
