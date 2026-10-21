<?php

namespace App\Filament\Admin\Pages;

use App\Models\AvailabilityOption;
use App\Models\Department;
use App\Models\LeaveType;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Source;
use App\Models\TalentStatus;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;

class Settings extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.admin.pages.settings';

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات العامة';

    protected static ?int $navigationSort = 1;

    public function getSubNavigation(): array
    {
        return [];
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام']);
    }

    public static function getNavigationLabel(): string
    {
        return 'مركز الإعدادات الموحد';
    }

    public function getTitle(): string
    {
        return 'مركز الإعدادات الموحد';
    }

    public function getSubheading(): ?string
    {
        return 'إدارة شاملة لكافة إعدادات النظام، الهيكل التنظيمي، أوقات العمل، وسياسات التوظيف في واجهة تفاعلية موحدة.';
    }

    /**
     * Active View State:
     * - 'grid': Direct Flat Grid (All settings squares visible on one screen with section headers)
     * - 'departments', 'locations', 'general', 'shifts', 'sources', 'statuses', 'templates', 'availability', 'leaves', 'alerts': Detail Views
     */
    public string $activeTab = 'grid';
    public ?string $currentCategory = null;
    public string $searchQuery = '';

    // ──────────────────────────────────────────
    // General System Settings
    // ──────────────────────────────────────────
    public string $brand_name = '';
    public string $currency = 'EGP';
    public string $currency_symbol = 'ج.م';
    public string $timezone = 'Africa/Cairo';
    public string $date_format = 'Y-m-d';
    public string $brand_primary_color = '#0d9488';
    public string $font_family = 'Cairo';
    public string $sidebar_theme = 'dark_navy';
    public ?string $existing_logo = null;
    public $new_logo = null;

    // Password Update
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // ──────────────────────────────────────────
    // Working Hours & Shifts Settings
    // ──────────────────────────────────────────
    public string $work_start_time = '09:00';
    public string $work_end_time = '17:00';
    public int $grace_period_minutes = 15;
    public array $weekend_days = ['friday', 'saturday'];
    public bool $allow_remote_clock_in = true;
    public bool $enable_gps_clockin = false;
    public string $company_latitude = '24.713600';
    public string $company_longitude = '46.675300';
    public int $company_geofence_radius = 200;

    // ──────────────────────────────────────────
    // HR & Leave Policies Settings
    // ──────────────────────────────────────────
    public int $default_annual_leave_days = 21;
    public string $leave_type_name = '';
    public int $leave_type_days = 21;
    public bool $leave_type_requires_approval = true;
    public ?int $editing_leave_type_id = null;

    // ──────────────────────────────────────────
    // Alerts & Notifications Settings
    // ──────────────────────────────────────────
    public int $alert_contract_expiry_days = 60;
    public bool $notify_new_application = true;
    public string $hr_notification_email = '';

    // Inline CRUD: Department
    public string $dept_name = '';
    public ?int $editing_dept_id = null;

    // Inline CRUD: Location
    public string $loc_name = '';
    public ?int $editing_loc_id = null;

    // Inline CRUD: Source
    public string $source_name = '';
    public ?int $editing_source_id = null;

    // Inline CRUD: Status
    public string $status_name = '';
    public string $status_color = 'primary';
    public bool $status_is_default = false;
    public ?int $editing_status_id = null;

    // Inline CRUD: Availability Option
    public string $avail_name = '';
    public ?int $editing_avail_id = null;

    // Communication Templates
    public string $wa_template_interview = '';
    public string $wa_template_docs = '';
    public string $email_template_interview_subject = '';
    public string $email_template_interview_body = '';
    public string $email_template_offer_subject = '';
    public string $email_template_offer_body = '';
    public string $email_template_reject_subject = '';
    public string $email_template_reject_body = '';
    public string $email_template_application_received_subject = '';
    public string $email_template_application_received_body = '';
    public array $custom_templates = [];

    public function mount(): void
    {
        $tab = request()->query('tab');
        if ($tab && in_array($tab, ['grid', 'departments', 'locations', 'general', 'shifts', 'sources', 'statuses', 'templates', 'availability', 'leaves', 'alerts'])) {
            $this->activeTab = $tab;
        }

        $this->loadGeneralSettings();
        $this->loadShiftSettings();
        $this->loadAlertSettings();
        $this->loadTemplates();
    }

    public function openSection(string $section): void
    {
        $this->activeTab = $section;
        $this->searchQuery = '';
        $this->resetCrudForms();
    }

    public function backToGrid(): void
    {
        $this->activeTab = 'grid';
        $this->searchQuery = '';
        $this->resetCrudForms();
    }

    public function backToCurrentGrid(): void
    {
        $this->backToGrid();
    }

    public function backToCategories(): void
    {
        $this->backToGrid();
    }

    public function openCategory(string $category): void
    {
        $this->currentCategory = $category;
        $this->activeTab = 'grid';
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['grid', 'departments', 'locations', 'general', 'shifts', 'sources', 'statuses', 'templates', 'availability', 'leaves', 'alerts'])) {
            $this->activeTab = $tab;
            $this->searchQuery = '';
            $this->resetCrudForms();
        }
    }

    protected function resetCrudForms(): void
    {
        $this->dept_name = '';
        $this->editing_dept_id = null;
        $this->loc_name = '';
        $this->editing_loc_id = null;
        $this->source_name = '';
        $this->editing_source_id = null;
        $this->status_name = '';
        $this->status_color = 'primary';
        $this->status_is_default = false;
        $this->editing_status_id = null;
        $this->avail_name = '';
        $this->editing_avail_id = null;
        $this->leave_type_name = '';
        $this->leave_type_days = 21;
        $this->leave_type_requires_approval = true;
        $this->editing_leave_type_id = null;
    }

    // ──────────────────────────────────────────
    // 1. GENERAL SYSTEM SETTINGS
    // ──────────────────────────────────────────
    public function loadGeneralSettings(): void
    {
        $this->brand_name = (string) Setting::get('brand_name', 'نظام إدارة الموارد البشرية');
        $this->currency = (string) Setting::get('currency', 'EGP');
        $this->currency_symbol = (string) Setting::get('currency_symbol', 'ج.م');
        $this->timezone = (string) Setting::get('timezone', 'Africa/Cairo');
        $this->date_format = (string) Setting::get('date_format', 'Y-m-d');
        $this->brand_primary_color = (string) Setting::get('brand_primary_color', '#0d9488');
        $this->font_family = (string) Setting::get('font_family', 'Cairo');
        $this->sidebar_theme = (string) Setting::get('sidebar_theme', 'dark_navy');

        $logo = Setting::get('logo');
        if ($logo && Storage::disk('public')->exists($logo)) {
            $this->existing_logo = $logo;
        } else {
            $this->existing_logo = null;
        }
    }

    public function setPrimaryColor(string $color): void
    {
        $this->brand_primary_color = $color;
    }

    public function setSidebarTheme(string $theme): void
    {
        $this->sidebar_theme = $theme;
    }

    public function setFontFamily(string $font): void
    {
        $this->font_family = $font;
    }

    public function updatedCurrency(string $value): void
    {
        $symbols = [
            'EGP' => 'ج.م',
            'SAR' => 'ر.س',
            'AED' => 'د.إ',
            'USD' => '$',
            'EUR' => '€',
            'KWD' => 'د.ك',
            'QAR' => 'ر.ق',
            'BHD' => 'د.ب',
            'OMR' => 'ر.ع',
            'JOD' => 'د.أ',
        ];
        if (isset($symbols[$value])) {
            $this->currency_symbol = $symbols[$value];
        }
    }

    public function saveGeneralSettings(): void
    {
        $this->validate([
            'brand_name'          => 'required|string|max:120',
            'currency'            => 'required|string|max:10',
            'currency_symbol'     => 'required|string|max:10',
            'timezone'            => 'required|string|max:60',
            'date_format'         => 'required|string|max:20',
            'brand_primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'font_family'         => 'required|string|max:50',
            'sidebar_theme'       => 'required|string|in:dark_navy,deep_black,brand_tinted,slate_gray,light_clean',
            'new_logo'            => 'nullable|image|max:2048',
        ]);

        Setting::set('brand_name', $this->brand_name);
        Setting::set('currency', strtoupper(trim($this->currency)));
        Setting::set('currency_symbol', trim($this->currency_symbol));
        Setting::set('timezone', trim($this->timezone));
        Setting::set('date_format', trim($this->date_format));
        Setting::set('brand_primary_color', trim($this->brand_primary_color));
        Setting::set('font_family', trim($this->font_family));
        Setting::set('sidebar_theme', trim($this->sidebar_theme));

        // تطبيق التوقيت فورياً على وقت تنفيذ الجلسة الحالية
        try {
            date_default_timezone_set(trim($this->timezone));
            config(['app.timezone' => trim($this->timezone)]);
        } catch (\Throwable) {
            //
        }

        if ($this->new_logo) {
            $path = $this->new_logo->store('logo', 'public');
            Setting::set('logo', $path);
            $this->existing_logo = $path;
            $this->new_logo = null;
        }

        Notification::make()
            ->title('تم حفظ إعدادات النظام بنجاح')
            ->body('تم تطبيق إعدادات الهوية البصرية، العملة، التوقيت، وصيغ التاريخ في كافة أرجاء النظام.')
            ->success()
            ->send();
    }

    public function removeLogo(): void
    {
        if ($this->existing_logo && Storage::disk('public')->exists($this->existing_logo)) {
            Storage::disk('public')->delete($this->existing_logo);
        }
        Setting::set('logo', null);
        $this->existing_logo = null;

        Notification::make()
            ->title('تم حذف الشعار')
            ->success()
            ->send();
    }

    public function savePassword(): void
    {
        $this->validate([
            'current_password'          => 'required|current_password',
            'new_password'              => ['required', 'confirmed', Password::defaults()],
            'new_password_confirmation' => 'required',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        Notification::make()
            ->title('تم تغيير كلمة المرور بنجاح')
            ->success()
            ->send();
    }

    // ──────────────────────────────────────────
    // 2. WORKING HOURS & SHIFTS
    // ──────────────────────────────────────────
    public function loadShiftSettings(): void
    {
        $this->work_start_time = (string) Setting::get('work_start_time', '09:00');
        $this->work_end_time = (string) Setting::get('work_end_time', '17:00');
        $this->grace_period_minutes = (int) Setting::get('grace_period_minutes', 15);
        $this->allow_remote_clock_in = (bool) Setting::get('allow_remote_clock_in', true);
        $this->enable_gps_clockin = (bool) Setting::get('enable_gps_clockin', false);
        $this->company_latitude = (string) Setting::get('company_latitude', '24.713600');
        $this->company_longitude = (string) Setting::get('company_longitude', '46.675300');
        $this->company_geofence_radius = (int) Setting::get('company_geofence_radius', 200);

        $weekends = Setting::get('weekend_days');
        $this->weekend_days = $weekends ? json_decode($weekends, true) : ['friday', 'saturday'];
    }

    public function setCompanyLocation(float $lat, float $lng): void
    {
        $this->company_latitude = number_format($lat, 6, '.', '');
        $this->company_longitude = number_format($lng, 6, '.', '');

        Notification::make()
            ->title('تم التقاط إحداثيات موقعك الحالي')
            ->body("خط العرض: {$this->company_latitude} | خط الطول: {$this->company_longitude}")
            ->success()
            ->send();
    }

    public function saveShiftSettings(): void
    {
        $this->validate([
            'work_start_time'         => 'required|string',
            'work_end_time'           => 'required|string',
            'grace_period_minutes'    => 'required|integer|min:0|max:120',
            'company_geofence_radius' => 'required|integer|min:50|max:5000',
        ]);

        Setting::set('work_start_time', $this->work_start_time);
        Setting::set('work_end_time', $this->work_end_time);
        Setting::set('grace_period_minutes', $this->grace_period_minutes);
        Setting::set('weekend_days', json_encode($this->weekend_days));
        Setting::set('allow_remote_clock_in', $this->allow_remote_clock_in);
        Setting::set('enable_gps_clockin', $this->enable_gps_clockin);
        Setting::set('company_latitude', $this->company_latitude);
        Setting::set('company_longitude', $this->company_longitude);
        Setting::set('company_geofence_radius', $this->company_geofence_radius);

        Notification::make()
            ->title('تم حفظ إعدادات وساعات الدوام ونطاق الـ GPS')
            ->body('تم تحديث إعدادات الدوام والنطاق الجغرافي المسموح به للبصمة بنجاح.')
            ->success()
            ->send();
    }

    // ──────────────────────────────────────────
    // 3. HR & LEAVE POLICIES
    // ──────────────────────────────────────────
    public function createLeaveType(): void
    {
        $this->validate([
            'leave_type_name' => 'required|string|max:150|unique:leave_types,name',
            'leave_type_days' => 'required|integer|min:1|max:365',
        ]);

        LeaveType::create([
            'name'                  => trim($this->leave_type_name),
            'default_days_per_year' => $this->leave_type_days,
            'requires_approval'     => $this->leave_type_requires_approval,
        ]);

        $this->leave_type_name = '';
        $this->leave_type_days = 21;
        $this->leave_type_requires_approval = true;

        Notification::make()
            ->title('تم إضافة نوع الإجازة بنجاح')
            ->success()
            ->send();
    }

    public function editLeaveType(int $id): void
    {
        $lt = LeaveType::findOrFail($id);
        $this->editing_leave_type_id = $lt->id;
        $this->leave_type_name = $lt->name;
        $this->leave_type_days = (int) $lt->default_days_per_year;
        $this->leave_type_requires_approval = (bool) $lt->requires_approval;
    }

    public function updateLeaveType(): void
    {
        if (!$this->editing_leave_type_id) return;

        $this->validate([
            'leave_type_name' => 'required|string|max:150|unique:leave_types,name,' . $this->editing_leave_type_id,
            'leave_type_days' => 'required|integer|min:1|max:365',
        ]);

        $lt = LeaveType::findOrFail($this->editing_leave_type_id);
        $lt->update([
            'name'                  => trim($this->leave_type_name),
            'default_days_per_year' => $this->leave_type_days,
            'requires_approval'     => $this->leave_type_requires_approval,
        ]);

        $this->leave_type_name = '';
        $this->leave_type_days = 21;
        $this->leave_type_requires_approval = true;
        $this->editing_leave_type_id = null;

        Notification::make()
            ->title('تم تحديث نوع الإجازة بنجاح')
            ->success()
            ->send();
    }

    public function deleteLeaveType(int $id): void
    {
        $lt = LeaveType::findOrFail($id);
        $lt->delete();

        Notification::make()
            ->title('تم حذف نوع الإجازة')
            ->success()
            ->send();
    }

    public function cancelEditLeaveType(): void
    {
        $this->leave_type_name = '';
        $this->leave_type_days = 21;
        $this->leave_type_requires_approval = true;
        $this->editing_leave_type_id = null;
    }

    // ──────────────────────────────────────────
    // 4. NOTIFICATIONS & ALERTS
    // ──────────────────────────────────────────
    public function loadAlertSettings(): void
    {
        $this->alert_contract_expiry_days = (int) Setting::get('alert_contract_expiry_days', 60);
        $this->notify_new_application = (bool) Setting::get('notify_new_application', true);
        $this->hr_notification_email = (string) Setting::get('hr_notification_email', 'hr@company.com');
    }

    public function saveAlertSettings(): void
    {
        $this->validate([
            'alert_contract_expiry_days' => 'required|integer|min:5|max:180',
            'hr_notification_email'      => 'nullable|email',
        ]);

        Setting::set('alert_contract_expiry_days', $this->alert_contract_expiry_days);
        Setting::set('notify_new_application', $this->notify_new_application);
        Setting::set('hr_notification_email', trim($this->hr_notification_email));

        Notification::make()
            ->title('تم حفظ إعدادات التنبيهات والإشعارات')
            ->success()
            ->send();
    }

    // ──────────────────────────────────────────
    // 5. DEPARTMENTS CRUD
    // ──────────────────────────────────────────
    public function createDepartment(): void
    {
        $this->validate([
            'dept_name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create(['name' => trim($this->dept_name)]);
        $this->dept_name = '';

        Notification::make()
            ->title('تم إضافة القسم بنجاح')
            ->success()
            ->send();
    }

    public function editDepartment(int $id): void
    {
        $dept = Department::findOrFail($id);
        $this->editing_dept_id = $dept->id;
        $this->dept_name = $dept->name;
    }

    public function updateDepartment(): void
    {
        if (!$this->editing_dept_id) return;

        $this->validate([
            'dept_name' => 'required|string|max:255|unique:departments,name,' . $this->editing_dept_id,
        ]);

        $dept = Department::findOrFail($this->editing_dept_id);
        $dept->update(['name' => trim($this->dept_name)]);

        $this->dept_name = '';
        $this->editing_dept_id = null;

        Notification::make()
            ->title('تم تحديث بيانات القسم بنجاح')
            ->success()
            ->send();
    }

    public function deleteDepartment(int $id): void
    {
        $dept = Department::findOrFail($id);
        if ($dept->talents()->count() > 0 || $dept->employees()->count() > 0) {
            Notification::make()
                ->title('تعذر الحذف')
                ->body('لا يمكن حذف القسم لوجود موظفين أو مرشحين مسجلين عليه.')
                ->danger()
                ->send();
            return;
        }

        $dept->delete();
        Notification::make()
            ->title('تم حذف القسم بنجاح')
            ->success()
            ->send();
    }

    public function cancelEditDepartment(): void
    {
        $this->dept_name = '';
        $this->editing_dept_id = null;
    }

    // ──────────────────────────────────────────
    // 6. LOCATIONS CRUD
    // ──────────────────────────────────────────
    public function createLocation(): void
    {
        $this->validate([
            'loc_name' => 'required|string|max:255|unique:locations,name',
        ]);

        Location::create(['name' => trim($this->loc_name)]);
        $this->loc_name = '';

        Notification::make()
            ->title('تم إضافة الموقع / الفرع بنجاح')
            ->success()
            ->send();
    }

    public function editLocation(int $id): void
    {
        $loc = Location::findOrFail($id);
        $this->editing_loc_id = $loc->id;
        $this->loc_name = $loc->name;
    }

    public function updateLocation(): void
    {
        if (!$this->editing_loc_id) return;

        $this->validate([
            'loc_name' => 'required|string|max:255|unique:locations,name,' . $this->editing_loc_id,
        ]);

        $loc = Location::findOrFail($this->editing_loc_id);
        $loc->update(['name' => trim($this->loc_name)]);

        $this->loc_name = '';
        $this->editing_loc_id = null;

        Notification::make()
            ->title('تم تحديث الموقع بنجاح')
            ->success()
            ->send();
    }

    public function deleteLocation(int $id): void
    {
        $loc = Location::findOrFail($id);
        if ($loc->talents()->count() > 0) {
            Notification::make()
                ->title('تعذر الحذف')
                ->body('لا يمكن حذف هذا الموقع لارتباط مرشحين به.')
                ->danger()
                ->send();
            return;
        }

        $loc->delete();
        Notification::make()
            ->title('تم حذف الموقع بنجاح')
            ->success()
            ->send();
    }

    public function cancelEditLocation(): void
    {
        $this->loc_name = '';
        $this->editing_loc_id = null;
    }

    // ──────────────────────────────────────────
    // 7. SOURCES CRUD
    // ──────────────────────────────────────────
    public function createSource(): void
    {
        $this->validate([
            'source_name' => 'required|string|max:255|unique:sources,name',
        ]);

        Source::create(['name' => trim($this->source_name)]);
        $this->source_name = '';

        Notification::make()
            ->title('تم إضافة مصدر الترشيح بنجاح')
            ->success()
            ->send();
    }

    public function editSource(int $id): void
    {
        $src = Source::findOrFail($id);
        $this->editing_source_id = $src->id;
        $this->source_name = $src->name;
    }

    public function updateSource(): void
    {
        if (!$this->editing_source_id) return;

        $this->validate([
            'source_name' => 'required|string|max:255|unique:sources,name,' . $this->editing_source_id,
        ]);

        $src = Source::findOrFail($this->editing_source_id);
        $src->update(['name' => trim($this->source_name)]);

        $this->source_name = '';
        $this->editing_source_id = null;

        Notification::make()
            ->title('تم تحديث مصدر الترشيح بنجاح')
            ->success()
            ->send();
    }

    public function deleteSource(int $id): void
    {
        $src = Source::findOrFail($id);
        if ($src->talents()->count() > 0) {
            Notification::make()
                ->title('تعذر الحذف')
                ->body('لا يمكن حذف هذا المصدر لارتباط مرشحين مسجلين به.')
                ->danger()
                ->send();
            return;
        }

        $src->delete();
        Notification::make()
            ->title('تم حذف مصدر الترشيح')
            ->success()
            ->send();
    }

    public function cancelEditSource(): void
    {
        $this->source_name = '';
        $this->editing_source_id = null;
    }

    // ──────────────────────────────────────────
    // 8. TALENT STATUSES CRUD
    // ──────────────────────────────────────────
    public function createStatus(): void
    {
        $this->validate([
            'status_name'  => 'required|string|max:100|unique:talent_statuses,name',
            'status_color' => 'required|string|max:30',
        ]);

        if ($this->status_is_default) {
            TalentStatus::where('is_default', true)->update(['is_default' => false]);
        }

        TalentStatus::create([
            'name'       => trim($this->status_name),
            'color'      => $this->status_color,
            'is_default' => $this->status_is_default,
        ]);

        $this->status_name = '';
        $this->status_color = 'primary';
        $this->status_is_default = false;

        Notification::make()
            ->title('تم إضافة حالة المرشح بنجاح')
            ->success()
            ->send();
    }

    public function editStatus(int $id): void
    {
        $status = TalentStatus::findOrFail($id);
        $this->editing_status_id = $status->id;
        $this->status_name = $status->name;
        $this->status_color = $status->color ?? 'primary';
        $this->status_is_default = (bool) $status->is_default;
    }

    public function updateStatus(): void
    {
        if (!$this->editing_status_id) return;

        $this->validate([
            'status_name'  => 'required|string|max:100|unique:talent_statuses,name,' . $this->editing_status_id,
            'status_color' => 'required|string|max:30',
        ]);

        $status = TalentStatus::findOrFail($this->editing_status_id);

        if ($this->status_is_default && !$status->is_default) {
            TalentStatus::where('is_default', true)->update(['is_default' => false]);
        }

        $status->update([
            'name'       => trim($this->status_name),
            'color'      => $this->status_color,
            'is_default' => $this->status_is_default,
        ]);

        $this->status_name = '';
        $this->status_color = 'primary';
        $this->status_is_default = false;
        $this->editing_status_id = null;

        Notification::make()
            ->title('تم تحديث حالة المرشح بنجاح')
            ->success()
            ->send();
    }

    public function deleteStatus(int $id): void
    {
        $status = TalentStatus::findOrFail($id);
        if ($status->talents()->count() > 0) {
            Notification::make()
                ->title('تعذر الحذف')
                ->body('توجد طلبات مرشحين تعتمد على هذه الحالة حالياً.')
                ->danger()
                ->send();
            return;
        }

        $status->delete();
        Notification::make()
            ->title('تم حذف الحالة بنجاح')
            ->success()
            ->send();
    }

    public function cancelEditStatus(): void
    {
        $this->status_name = '';
        $this->status_color = 'primary';
        $this->status_is_default = false;
        $this->editing_status_id = null;
    }

    // ──────────────────────────────────────────
    // 9. AVAILABILITY OPTIONS CRUD
    // ──────────────────────────────────────────
    public function createAvailabilityOption(): void
    {
        $this->validate([
            'avail_name' => 'required|string|max:255|unique:availability_options,name',
        ]);

        AvailabilityOption::create(['name' => trim($this->avail_name)]);
        $this->avail_name = '';

        Notification::make()
            ->title('تم إضافة خيار الإتاحة بنجاح')
            ->success()
            ->send();
    }

    public function editAvailabilityOption(int $id): void
    {
        $opt = AvailabilityOption::findOrFail($id);
        $this->editing_avail_id = $opt->id;
        $this->avail_name = $opt->name;
    }

    public function updateAvailabilityOption(): void
    {
        if (!$this->editing_avail_id) return;

        $this->validate([
            'avail_name' => 'required|string|max:255|unique:availability_options,name,' . $this->editing_avail_id,
        ]);

        $opt = AvailabilityOption::findOrFail($this->editing_avail_id);
        $opt->update(['name' => trim($this->avail_name)]);

        $this->avail_name = '';
        $this->editing_avail_id = null;

        Notification::make()
            ->title('تم تحديث خيار الإتاحة بنجاح')
            ->success()
            ->send();
    }

    public function deleteAvailabilityOption(int $id): void
    {
        $opt = AvailabilityOption::findOrFail($id);
        if ($opt->talents()->count() > 0) {
            Notification::make()
                ->title('تعذر الحذف')
                ->body('يوجد مرشحون مسجلون بهذا الخيار.')
                ->danger()
                ->send();
            return;
        }

        $opt->delete();
        Notification::make()
            ->title('تم حذف خيار الإتاحة بنجاح')
            ->success()
            ->send();
    }

    public function cancelEditAvailabilityOption(): void
    {
        $this->avail_name = '';
        $this->editing_avail_id = null;
    }

    // ──────────────────────────────────────────
    // 10. COMMUNICATION TEMPLATES
    // ──────────────────────────────────────────
    public function loadTemplates(): void
    {
        $this->wa_template_interview = (string) Setting::get('wa_template_interview', "مرحباً {name}،\nنود إعلامك بأنه تم ترشيحك لمقابلة عمل في ({brand}). متى تكون متاحاً؟");
        $this->wa_template_docs = (string) Setting::get('wa_template_docs', "مرحباً {name}،\nيرجى تزويدنا بالمستندات الناقصة لاستكمال ملفك في ({brand}).");

        $this->email_template_interview_subject = (string) Setting::get('email_template_interview_subject', "دعوة لمقابلة عمل - {brand}");
        $this->email_template_interview_body = (string) Setting::get('email_template_interview_body', "عزيزي/عزيزتي {name}،\n\nتحية طيبة وبعد،\n\nيسعدنا إعلامك بأنه تم ترشيحك لإجراء مقابلة عمل لوظيفة {job_title}.\n\nمع خالص التحيات،\n{brand}");

        $this->email_template_offer_subject = (string) Setting::get('email_template_offer_subject', "عرض وظيفي - {brand}");
        $this->email_template_offer_body = (string) Setting::get('email_template_offer_body', "عزيزي/عزيزتي {name}،\n\nيسعدنا تقديم عرض وظيفي لك للانضمام إلى فريق عملنا.\n\nمع خالص التحيات،\n{brand}");

        $this->email_template_reject_subject = (string) Setting::get('email_template_reject_subject', "تحديث بخصوص التقديم - {brand}");
        $this->email_template_reject_body = (string) Setting::get('email_template_reject_body', "عزيزي/عزيزتي {name}،\n\nنشكرك على اهتمامك بالانضمام إلينا. نود إعلامك بأنه لم يقع الاختيار عليك في هذه المرحلة.\n\nنتمنى لك كل التوفيق.\n{brand}");

        $this->email_template_application_received_subject = (string) Setting::get('email_template_application_received_subject', "استلام طلب التوظيف بنجاح - {brand}");
        $this->email_template_application_received_body = (string) Setting::get('email_template_application_received_body', "أهلاً {name}،\n\nنود إعلامك بأننا قد استلمنا طلب التوظيف الخاص بك بنجاح، وقمنا بإضافة سيرتك الذاتية إلى قاعدة بياناتنا.\n\nسيقوم فريق الموارد البشرية بمراجعة ملفك والتواصل معك في حال توافقت مهاراتك مع متطلبات الوظيفة.\n\nنتمنى لك التوفيق!\nفريق التوظيف");

        $custom = Setting::get('custom_templates');
        $this->custom_templates = $custom ? json_decode($custom, true) : [];
    }

    public function saveTemplates(): void
    {
        $this->validate([
            'wa_template_interview'                        => 'required|string',
            'wa_template_docs'                             => 'required|string',
            'email_template_interview_subject'             => 'required|string',
            'email_template_interview_body'                => 'required|string',
            'email_template_offer_subject'                 => 'required|string',
            'email_template_offer_body'                    => 'required|string',
            'email_template_reject_subject'                => 'required|string',
            'email_template_reject_body'                   => 'required|string',
            'email_template_application_received_subject'  => 'required|string',
            'email_template_application_received_body'     => 'required|string',
        ]);

        Setting::set('wa_template_interview', $this->wa_template_interview);
        Setting::set('wa_template_docs', $this->wa_template_docs);
        Setting::set('email_template_interview_subject', $this->email_template_interview_subject);
        Setting::set('email_template_interview_body', $this->email_template_interview_body);
        Setting::set('email_template_offer_subject', $this->email_template_offer_subject);
        Setting::set('email_template_offer_body', $this->email_template_offer_body);
        Setting::set('email_template_reject_subject', $this->email_template_reject_subject);
        Setting::set('email_template_reject_body', $this->email_template_reject_body);
        Setting::set('email_template_application_received_subject', $this->email_template_application_received_subject);
        Setting::set('email_template_application_received_body', $this->email_template_application_received_body);

        Notification::make()
            ->title('تم حفظ قوالب المراسلة بنجاح')
            ->body('تم تطبيق نصوص قوالب الواتساب والبريد الإلكتروني وتنعكس فورياً على شاشات التواصل مع المرشحين.')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $deptQuery = Department::query()->withCount(['talents', 'employees']);
        $locQuery = Location::query()->withCount('talents');
        $srcQuery = Source::query()->withCount('talents');
        $statusQuery = TalentStatus::query()->withCount('talents')->orderBy('order_column', 'asc')->orderBy('id', 'asc');
        $availQuery = AvailabilityOption::query()->withCount('talents');
        $leaveTypesQuery = LeaveType::query();

        if ($this->searchQuery) {
            $q = '%' . trim($this->searchQuery) . '%';
            $deptQuery->where('name', 'like', $q);
            $locQuery->where('name', 'like', $q);
            $srcQuery->where('name', 'like', $q);
            $statusQuery->where('name', 'like', $q);
            $availQuery->where('name', 'like', $q);
            $leaveTypesQuery->where('name', 'like', $q);
        }

        return [
            'departments'         => $deptQuery->get(),
            'locations'           => $locQuery->get(),
            'sources'             => $srcQuery->get(),
            'statuses'            => $statusQuery->get(),
            'availabilityOptions' => $availQuery->get(),
            'leaveTypes'          => $leaveTypesQuery->get(),
            'allDepartmentsCount' => Department::count(),
            'allLocationsCount'   => Location::count(),
            'allSourcesCount'     => Source::count(),
            'allStatusesCount'    => TalentStatus::count(),
            'allAvailCount'       => AvailabilityOption::count(),
            'allLeaveTypesCount'  => LeaveType::count(),
        ];
    }
}
