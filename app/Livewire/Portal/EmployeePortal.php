<?php

namespace App\Livewire\Portal;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationForm;
use App\Models\Excuse;
use App\Models\Grievance;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
class EmployeePortal extends Component
{
    use WithFileUploads;

    public string $activeTab = 'overview';

    // Grievance form state
    public bool $showGrievanceModal = false;
    public string $grievance_type = 'administrative';
    public string $grievance_priority = 'normal';
    public string $grievance_title = '';
    public string $grievance_content = '';
    public bool $grievance_is_anonymous = false;
    public bool $grievance_against_manager = false;
    public $grievance_attachment = null;
    public string $grievanceFilter = 'all'; // all, in_progress, resolved, suggestion

    // Evaluation form state
    public bool $showEvaluationModal = false;
    public ?int $selectedFormId = null;
    public ?int $evaluatingEmployeeId = null;
    public bool $evaluation_is_anonymous = false;
    public array $evaluation_answers = [];
    public string $evaluation_strengths = '';
    public string $evaluation_improvements = '';

    // Leave request form
    public ?int $leave_type_id = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public string $leave_reason = '';

    // Excuse request form
    public ?string $excuse_date = null;
    public ?string $excuse_start_time = null;
    public ?string $excuse_end_time = null;
    public string $excuse_reason = '';

    // Payslip modal state
    public bool $showPayslipModal = false;
    public ?int $selectedPayrollId = null;

    // Toast message state
    public ?string $flashMessage = null;
    public string $flashType = 'success'; // success, error, warning

    // Admin testing employee switch (for admin users without employee profile)
    public ?int $previewEmployeeId = null;

    public function mount()
    {
        $this->start_date = Carbon::today()->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
        $this->excuse_date = Carbon::today()->format('Y-m-d');
        $this->excuse_start_time = Carbon::now()->format('H:i');
        $this->excuse_end_time = Carbon::now()->addHour()->format('H:i');

        // Preselect first leave type if available
        $firstType = LeaveType::first();
        if ($firstType) {
            $this->leave_type_id = $firstType->id;
        }

        // If admin without employee, select first available employee for preview
        $user = Auth::user();
        if ($user && !$user->employee) {
            $firstEmp = Employee::first();
            if ($firstEmp) {
                $this->previewEmployeeId = $firstEmp->id;
            }
        }

        $requestedTab = request()->query('tab');
        if ($requestedTab && in_array($requestedTab, ['overview', 'attendance', 'leaves', 'excuses', 'payrolls', 'profile', 'grievances', 'evaluations'])) {
            $this->activeTab = $requestedTab;
        }
    }

    public function getEmployeeProperty(): ?Employee
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($user->employee) {
            return $user->employee;
        }

        if ($this->previewEmployeeId) {
            return Employee::with(['department', 'manager', 'user'])->find($this->previewEmployeeId);
        }

        return null;
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public static function calculateDistanceInMeters(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius of Earth in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }

    public function clockIn($lat = null, $lng = null)
    {
        $employee = $this->employee;
        if (!$employee) {
            $this->notify('حسابك غير مرتبط بملف موظف لتسجيل الحضور.', 'error');
            return;
        }

        $today = Carbon::today()->format('Y-m-d');
        $existing = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();

        if ($existing) {
            $this->notify('تم تسجيل حضورك مسبقاً اليوم في تمام الساعة ' . Carbon::parse($existing->check_in_time)->format('h:i A'), 'warning');
            return;
        }

        $isGpsRequired = (bool) Setting::get('enable_gps_clockin', false);
        $isRemoteEmployee = (bool) ($employee->is_remote ?? false);

        $distanceMeters = null;
        $isRemote = false;

        // التحقق من الموقع الجغرافي إذا كان مفعلاً في الإعدادات والموظف غير معفى
        if ($isGpsRequired && !$isRemoteEmployee) {
            if ($lat === null || $lng === null) {
                $this->notify('يتطلب تسجيل الحضور تفعيل خدمة الموقع الجغرافي (GPS) للتأكد من تواجدك داخل مقر العمل.', 'error');
                return;
            }

            $companyLat = (float) Setting::get('company_latitude', 24.713600);
            $companyLng = (float) Setting::get('company_longitude', 46.675300);
            $allowedRadius = (int) Setting::get('company_geofence_radius', 200);

            $distanceMeters = static::calculateDistanceInMeters((float) $lat, (float) $lng, $companyLat, $companyLng);

            if ($distanceMeters > $allowedRadius) {
                $this->notify("أنت خارج نطاق مقر الشركة! المسافة الحالية {$distanceMeters} متر، والنطاق المسموح به {$allowedRadius} متر.", 'warning');
                return;
            }
        } else {
            // العمل عن بُعد مفعل أو الموظف معفى
            $isRemote = $isRemoteEmployee || !$isGpsRequired;
            if ($lat !== null && $lng !== null) {
                $companyLat = (float) Setting::get('company_latitude', 24.713600);
                $companyLng = (float) Setting::get('company_longitude', 46.675300);
                $distanceMeters = static::calculateDistanceInMeters((float) $lat, (float) $lng, $companyLat, $companyLng);
            }
        }

        $nowTime = Carbon::now()->format('H:i:s');
        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'check_in_time' => $nowTime,
            'check_in_lat' => $lat,
            'check_in_lng' => $lng,
            'distance_meters' => $distanceMeters,
            'is_remote' => $isRemote,
            'status' => 'present',
        ]);

        $successMsg = $isRemote
            ? 'تم تسجيل حضورك بنجاح (العمل عن بُعد) في تمام الساعة ' . Carbon::now()->format('h:i A')
            : 'تم تسجيل حضورك بنجاح من مقر الشركة (المسافة: ' . round($distanceMeters ?? 0) . ' م) في تمام الساعة ' . Carbon::now()->format('h:i A');

        $this->notify($successMsg, 'success');
    }

    public function clockOut($lat = null, $lng = null)
    {
        $employee = $this->employee;
        if (!$employee) {
            return;
        }

        $today = Carbon::today()->format('Y-m-d');
        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();

        if (!$attendance) {
            $this->notify('يجب تسجيل الحضور أولاً قبل الانصراف.', 'warning');
            return;
        }

        if ($attendance->check_out_time) {
            $this->notify('تم تسجيل انصرافك مسبقاً اليوم في تمام الساعة ' . Carbon::parse($attendance->check_out_time)->format('h:i A'), 'warning');
            return;
        }

        $nowTime = Carbon::now()->format('H:i:s');
        $attendance->update([
            'check_out_time' => $nowTime,
            'check_out_lat' => $lat,
            'check_out_lng' => $lng,
        ]);

        $this->notify('تم تسجيل انصرافك بنجاح في تمام الساعة ' . Carbon::now()->format('h:i A') . '. نتمنى لك يوماً سعيداً!', 'success');
    }

    public function submitLeaveRequest()
    {
        $employee = $this->employee;
        if (!$employee) {
            $this->notify('غير مصرح لك بتقديم طلب إجازة.', 'error');
            return;
        }

        $this->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_reason' => 'required|string|min:3',
        ], [
            'leave_type_id.required' => 'يرجى اختيار نوع الإجازة',
            'start_date.required' => 'يرجى تحديد تاريخ البداية',
            'end_date.required' => 'يرجى تحديد تاريخ النهاية',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون مساوياً أو بعد تاريخ البداية',
            'leave_reason.required' => 'يرجى كتابة سبب الإجازة',
            'leave_reason.min' => 'سبب الإجازة يجب ألا يقل عن 3 أحرف',
        ]);

        // Calculate days
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $daysCount = $start->diffInDays($end) + 1;

        Leave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $this->leave_type_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->leave_reason,
            'status' => 'pending',
        ]);

        $this->leave_reason = '';
        $this->notify("تم إرسال طلب الإجازة بنجاح ({$daysCount} أيام). الطلب الآن قيد المراجعة الإدارية.", 'success');
    }

    public function submitExcuseRequest()
    {
        $employee = $this->employee;
        if (!$employee) {
            $this->notify('غير مصرح لك بتقديم طلب استئذان.', 'error');
            return;
        }

        $this->validate([
            'excuse_date' => 'required|date',
            'excuse_start_time' => 'required',
            'excuse_end_time' => 'required',
            'excuse_reason' => 'required|string|min:3',
        ], [
            'excuse_date.required' => 'يرجى تحديد تاريخ الاستئذان',
            'excuse_start_time.required' => 'يرجى تحديد وقت البدء',
            'excuse_end_time.required' => 'يرجى تحديد وقت الانتهاء',
            'excuse_reason.required' => 'يرجى كتابة سبب الاستئذان',
            'excuse_reason.min' => 'سبب الاستئذان يجب ألا يقل عن 3 أحرف',
        ]);

        try {
            $start = Carbon::parse($this->excuse_date . ' ' . $this->excuse_start_time);
            $end = Carbon::parse($this->excuse_date . ' ' . $this->excuse_end_time);
            $durationMinutes = max(1, $start->diffInMinutes($end));
        } catch (\Throwable) {
            $durationMinutes = 60;
        }

        Excuse::create([
            'employee_id' => $employee->id,
            'date' => $this->excuse_date,
            'start_time' => $this->excuse_start_time,
            'end_time' => $this->excuse_end_time,
            'duration_minutes' => $durationMinutes,
            'reason' => $this->excuse_reason,
            'status' => 'pending',
        ]);

        $this->excuse_reason = '';
        $this->notify("تم إرسال طلب الاستئذان بنجاح ({$durationMinutes} دقيقة). الطلب الآن قيد المراجعة.", 'success');
    }

    public function openPayslip(int $payrollId)
    {
        $this->selectedPayrollId = $payrollId;
        $this->showPayslipModal = true;
    }

    public function closePayslipModal()
    {
        $this->showPayslipModal = false;
        $this->selectedPayrollId = null;
    }

    public function notify(string $message, string $type = 'success')
    {
        $this->flashMessage = $message;
        $this->flashType = $type;
    }

    public function clearNotification()
    {
        $this->flashMessage = null;
    }

    public function openGrievanceModal()
    {
        $this->resetGrievanceForm();
        $this->showGrievanceModal = true;
    }

    public function closeGrievanceModal()
    {
        $this->showGrievanceModal = false;
    }

    public function resetGrievanceForm()
    {
        $this->grievance_type = 'administrative';
        $this->grievance_priority = 'normal';
        $this->grievance_title = '';
        $this->grievance_content = '';
        $this->grievance_is_anonymous = false;
        $this->grievance_against_manager = false;
        $this->grievance_attachment = null;
    }

    public function setGrievanceFilter(string $filter)
    {
        $this->grievanceFilter = $filter;
    }

    public function selectGrievanceType(string $type)
    {
        $this->grievance_type = $type;
    }

    public function selectGrievancePriority(string $priority)
    {
        $this->grievance_priority = $priority;
    }

    public function submitGrievance()
    {
        $user = Auth::user();
        if (!$user) {
            $this->notify('يجب تسجيل الدخول أولاً.', 'error');
            return;
        }

        $this->validate([
            'grievance_type' => 'required|string',
            'grievance_priority' => 'required|string',
            'grievance_title' => 'required|string|min:4|max:255',
            'grievance_content' => 'required|string|min:10',
        ], [
            'grievance_title.required' => 'يرجى كتابة عنوان أو موضوع الشكوى',
            'grievance_title.min' => 'عنوان الشكوى يجب ألا يقل عن 4 أحرف',
            'grievance_content.required' => 'يرجى كتابة تفاصيل الشكوى بالكامل',
            'grievance_content.min' => 'تفاصيل الشكوى يجب ألا تقل عن 10 أحرف',
        ]);

        $employee = $this->employee;

        $attachmentPath = null;
        if ($this->grievance_attachment) {
            $attachmentPath = $this->grievance_attachment->store('grievances', 'public');
        }

        $grievance = Grievance::create([
            'user_id' => $user->id,
            'employee_id' => $employee?->id,
            'is_anonymous' => $this->grievance_is_anonymous,
            'type' => $this->grievance_type,
            'priority' => $this->grievance_priority,
            'title' => $this->grievance_title,
            'content' => $this->grievance_content,
            'against_manager' => $this->grievance_against_manager,
            'department_id' => $employee?->department_id,
            'attachment_path' => $attachmentPath,
            'status' => 'new',
        ]);

        $this->resetGrievanceForm();
        $this->showGrievanceModal = false;

        $msg = $this->grievance_is_anonymous
            ? "تم تسجيل شكواك برقم مرجعي ({$grievance->ticket_number}) بسرية تامة دون ذكر هويتك."
            : "تم تسجيل الشكوى برقم مرجعي ({$grievance->ticket_number}) وستتم مراجعتها من إدارة الموارد البشرية.";

        $this->notify($msg, 'success');
    }

    public function openEvaluationModal(int $formId)
    {
        $this->selectedFormId = $formId;
        $this->evaluation_answers = [];
        $this->evaluation_strengths = '';
        $this->evaluation_improvements = '';
        $this->evaluation_is_anonymous = false;

        $form = EvaluationForm::find($formId);
        if ($form) {
            if ($form->type === 'employee_to_manager') {
                $this->evaluation_is_anonymous = true;
                $this->evaluatingEmployeeId = $this->employee?->manager_id;
            } elseif ($form->type === 'manager_to_employee') {
                $this->evaluation_is_anonymous = false;
                $this->evaluatingEmployeeId = null;
            }
            if (is_array($form->questions)) {
                foreach ($form->questions as $idx => $q) {
                    $this->evaluation_answers['q_' . $idx] = ($q['type'] ?? '') === 'rating_5' ? 5 : null;
                }
            }
        }

        $this->showEvaluationModal = true;
    }

    public function closeEvaluationModal()
    {
        $this->showEvaluationModal = false;
        $this->selectedFormId = null;
    }

    public function submitPortalEvaluation()
    {
        $user = Auth::user();
        if (!$user) {
            $this->notify('يجب تسجيل الدخول أولاً.', 'error');
            return;
        }

        $form = EvaluationForm::find($this->selectedFormId);
        if (!$form) {
            $this->notify('نموذج التقييم غير موجود.', 'error');
            return;
        }

        $score = $form->calculateScore($this->evaluation_answers);

        Evaluation::create([
            'evaluation_form_id' => $form->id,
            'evaluator_id' => $user->id,
            'evaluated_employee_id' => $this->evaluatingEmployeeId,
            'is_anonymous' => $this->evaluation_is_anonymous,
            'period' => Carbon::now()->translatedFormat('F Y'),
            'answers' => $this->evaluation_answers,
            'score' => $score,
            'strengths' => $this->evaluation_strengths,
            'areas_for_improvement' => $this->evaluation_improvements,
            'status' => 'submitted',
        ]);

        $this->closeEvaluationModal();
        $this->notify('شكراً لك! تم إرسال تقييمك بنجاح واحتساب النتائج.', 'success');
    }

    public function render()
    {
        $user = Auth::user();
        $employee = $this->employee;
        $today = Carbon::today()->format('Y-m-d');
        $currency = Setting::get('currency', 'SAR');
        $brandName = Setting::get('brand_name', 'نظام الموارد البشرية');
        $logo = Setting::get('logo');

        // Today Attendance
        $todayAttendance = null;
        $workedHoursText = null;
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();
            if ($todayAttendance && $todayAttendance->check_in_time) {
                $in = Carbon::parse($todayAttendance->check_in_time);
                $out = $todayAttendance->check_out_time ? Carbon::parse($todayAttendance->check_out_time) : Carbon::now();
                $diffMinutes = max(0, $in->diffInMinutes($out));
                $hours = intdiv($diffMinutes, 60);
                $minutes = $diffMinutes % 60;
                $workedHoursText = $hours > 0 ? "{$hours} س و {$minutes} د" : "{$minutes} دقيقة";
            }
        }

        // Leave Balances (ensure balances exist for current year)
        $leaveBalancesData = [];
        $leaveTypes = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('leave_types')) {
                $leaveTypes = LeaveType::all();
                if ($employee && \Illuminate\Support\Facades\Schema::hasTable('leave_balances')) {
                    $currentYear = Carbon::today()->year;
                    foreach ($leaveTypes as $type) {
                        $balance = LeaveBalance::firstOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'leave_type_id' => $type->id,
                                'year' => $currentYear,
                            ],
                            [
                                'total_days' => $type->default_days_per_year ?? 21,
                                'used_days' => 0,
                            ]
                        );
                        $remaining = max(0, $balance->total_days - $balance->used_days);
                        $percent = $balance->total_days > 0 ? min(100, round(($balance->used_days / $balance->total_days) * 100)) : 0;
                        
                        $leaveBalancesData[] = [
                            'type_id' => $type->id,
                            'name' => $type->name,
                            'total' => $balance->total_days,
                            'used' => $balance->used_days,
                            'remaining' => $remaining,
                            'percent' => $percent,
                        ];
                    }
                }
            }
        } catch (\Throwable) {
            $leaveBalancesData = [];
            $leaveTypes = collect();
        }

        // Attendance history this month
        $attendances = collect();
        $presentDaysCount = 0;
        try {
            if ($employee && \Illuminate\Support\Facades\Schema::hasTable('attendances')) {
                $attendances = Attendance::where('employee_id', $employee->id)
                    ->whereMonth('date', Carbon::today()->month)
                    ->whereYear('date', Carbon::today()->year)
                    ->orderBy('date', 'desc')
                    ->get();
                $presentDaysCount = $attendances->where('status', 'present')->count();
            }
        } catch (\Throwable) {
            $attendances = collect();
            $presentDaysCount = 0;
        }

        // Employee Leaves
        $leaves = collect();
        try {
            if ($employee && \Illuminate\Support\Facades\Schema::hasTable('leaves')) {
                $leaves = Leave::with('leaveType')
                    ->where('employee_id', $employee->id)
                    ->orderBy('created_at', 'desc')
                    ->take(15)
                    ->get();
            }
        } catch (\Throwable) {
            $leaves = collect();
        }

        // Employee Excuses
        $excuses = collect();
        $approvedExcusesCount = 0;
        try {
            if ($employee && \Illuminate\Support\Facades\Schema::hasTable('excuses')) {
                $excuses = Excuse::where('employee_id', $employee->id)
                    ->orderBy('date', 'desc')
                    ->take(15)
                    ->get();
                $approvedExcusesCount = Excuse::where('employee_id', $employee->id)
                    ->whereMonth('date', Carbon::today()->month)
                    ->where('status', 'approved')
                    ->count();
            }
        } catch (\Throwable) {
            $excuses = collect();
            $approvedExcusesCount = 0;
        }

        // Employee Payrolls
        $payrolls = collect();
        $latestPayroll = null;
        try {
            if ($employee && \Illuminate\Support\Facades\Schema::hasTable('payrolls')) {
                $payrolls = Payroll::where('employee_id', $employee->id)
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get();
                $latestPayroll = $payrolls->first();
            }
        } catch (\Throwable) {
            $payrolls = collect();
            $latestPayroll = null;
        }

        // Selected Payroll for Payslip Modal
        $selectedPayroll = null;
        try {
            if ($this->selectedPayrollId && $employee && \Illuminate\Support\Facades\Schema::hasTable('payrolls')) {
                $selectedPayroll = Payroll::with('employee.department')->where('employee_id', $employee->id)->find($this->selectedPayrollId);
            }
        } catch (\Throwable) {
            $selectedPayroll = null;
        }

        // Active Announcements
        $announcements = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('announcements')) {
                $announcements = Announcement::where('is_active', true)
                    ->latest()
                    ->take(3)
                    ->get();
            }
        } catch (\Throwable) {
            $announcements = collect();
        }

        // Available employees for admin preview switch
        $allEmployees = collect();
        if ($user && ($user->hasRole(['admin', 'Super Admin', 'مسؤول النظام', 'المدير العام']) || $user->can('view_any_employee'))) {
            $allEmployees = Employee::orderBy('first_name')->get();
        }

        // Grievances
        $allMyGrievances = collect();
        $myGrievances = collect();
        try {
            if ($user && \Illuminate\Support\Facades\Schema::hasTable('grievances')) {
                $baseQuery = Grievance::where('user_id', $user->id)
                    ->orWhere(function ($q) use ($employee) {
                        if ($employee) {
                            $q->where('employee_id', $employee->id);
                        }
                    })
                    ->latest();

                $allMyGrievances = (clone $baseQuery)->get();

                if ($this->grievanceFilter === 'in_progress') {
                    $baseQuery->whereIn('status', ['new', 'in_progress']);
                } elseif ($this->grievanceFilter === 'resolved') {
                    $baseQuery->where('status', 'resolved');
                } elseif ($this->grievanceFilter === 'suggestion') {
                    $baseQuery->where('type', 'suggestion');
                }

                $myGrievances = $baseQuery->get();
            }
        } catch (\Throwable) {
            $allMyGrievances = collect();
            $myGrievances = collect();
        }

        // Available Evaluation Forms
        $availableForms = collect();
        $selectedForm = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('evaluation_forms')) {
                $availableForms = EvaluationForm::where('is_active', true)->latest()->get();
                if ($this->selectedFormId) {
                    $selectedForm = EvaluationForm::find($this->selectedFormId);
                }
            }
        } catch (\Throwable) {
            $availableForms = collect();
        }

        // Employee Evaluations (received)
        $myEvaluations = collect();
        try {
            if ($employee && \Illuminate\Support\Facades\Schema::hasTable('evaluations')) {
                $myEvaluations = Evaluation::with('form')
                    ->where('evaluated_employee_id', $employee->id)
                    ->where('status', '!=', 'draft')
                    ->latest()
                    ->get();
            }
        } catch (\Throwable) {
            $myEvaluations = collect();
        }

        $isGpsRequired = (bool) Setting::get('enable_gps_clockin', false);
        $companyRadius = (int) Setting::get('company_geofence_radius', 200);
        $isRemoteEmployee = (bool) ($employee->is_remote ?? false);

        return view('livewire.portal.employee-portal', [
            'user' => $user,
            'employee' => $employee,
            'brandName' => $brandName,
            'logo' => $logo,
            'currency' => $currency,
            'isGpsRequired' => $isGpsRequired,
            'companyRadius' => $companyRadius,
            'isRemoteEmployee' => $isRemoteEmployee,
            'todayAttendance' => $todayAttendance,
            'workedHoursText' => $workedHoursText,
            'leaveBalancesData' => $leaveBalancesData,
            'leaveTypes' => $leaveTypes,
            'attendances' => $attendances,
            'presentDaysCount' => $presentDaysCount,
            'leaves' => $leaves,
            'excuses' => $excuses,
            'approvedExcusesCount' => $approvedExcusesCount,
            'payrolls' => $payrolls,
            'latestPayroll' => $latestPayroll,
            'selectedPayroll' => $selectedPayroll,
            'announcements' => $announcements,
            'allEmployees' => $allEmployees,
            'allMyGrievances' => $allMyGrievances,
            'myGrievances' => $myGrievances,
            'availableForms' => $availableForms,
            'selectedForm' => $selectedForm,
            'myEvaluations' => $myEvaluations,
        ]);
    }
}
