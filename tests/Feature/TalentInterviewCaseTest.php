<?php

namespace Tests\Feature;

use App\Actions\Talent\RecordInterviewCaseAction;
use App\Enums\InterviewAttendance;
use App\Enums\InterviewStage;
use App\Enums\ReapplyEligibility;
use App\Enums\TalentDecisionOutcome;
use App\Enums\TalentDecisionReason;
use App\Enums\TalentNoteCategory;
use App\Filament\Admin\Resources\TalentResource\Pages\EditTalent;
use App\Filament\Admin\Resources\TalentResource\Pages\ListTalents;
use App\Filament\Admin\Resources\TalentResource\Pages\TalentKanbanBoard;
use App\Filament\Admin\Resources\TalentResource\Pages\ViewTalent;
use App\Filament\Admin\Resources\TalentResource\RelationManagers\NotesRelationManager;
use App\Filament\Admin\Resources\TalentResource\RelationManagers\ReviewsRelationManager;
use App\Models\Department;
use App\Models\JobPosting;
use App\Models\Talent;
use App\Models\TalentStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TalentInterviewCaseTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $employeeUser;
    protected Department $department;
    protected JobPosting $jobPosting;
    protected JobPosting $alternativeJobPosting;
    protected TalentStatus $newStatus;
    protected TalentStatus $interviewStatus;
    protected TalentStatus $rejectedStatus;
    protected TalentStatus $holdStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'موظف', 'guard_name' => 'web']);

        $this->adminUser = User::factory()->create([
            'name' => 'مدير التوظيف',
            'email' => 'hr-admin@example.com',
        ]);
        $this->adminUser->assignRole($adminRole);

        $this->employeeUser = User::factory()->create([
            'name' => 'موظف عادي',
            'email' => 'staff@example.com',
        ]);
        $this->employeeUser->assignRole($employeeRole);

        $this->department = Department::create(['name' => 'الهندسة والبرمجيات']);

        $this->jobPosting = JobPosting::create([
            'title' => 'Senior Backend Developer',
            'department_id' => $this->department->id,
            'is_active' => true,
        ]);

        $this->alternativeJobPosting = JobPosting::create([
            'title' => 'Mid-Level PHP Developer',
            'department_id' => $this->department->id,
            'is_active' => true,
        ]);

        $this->newStatus = TalentStatus::create([
            'name' => 'جديد',
            'color' => 'info',
            'is_default' => true,
            'order_column' => 1,
        ]);

        $this->interviewStatus = TalentStatus::create([
            'name' => 'مرحلة الإنترفيو',
            'color' => 'purple',
            'is_default' => false,
            'order_column' => 4,
        ]);

        $this->rejectedStatus = TalentStatus::create([
            'name' => 'مرفوض',
            'color' => 'danger',
            'is_default' => false,
            'order_column' => 7,
        ]);

        $this->holdStatus = TalentStatus::create([
            'name' => 'معلّق',
            'color' => 'gray',
            'is_default' => false,
            'order_column' => 8,
        ]);
    }

    public function test_existing_legacy_candidates_without_case_fields_render_cleanly_in_default_and_kanban_views(): void
    {
        $legacyTalent = Talent::create([
            'full_name' => 'أحمد حسن القديم',
            'email' => 'legacy@example.com',
            'phone' => '+201001112233',
            'department_id' => $this->department->id,
            'job_posting_id' => $this->jobPosting->id,
            'status_id' => $this->newStatus->id,
            'general_notes' => 'مرشح قديم مسجل قبل التحديث.',
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(ListTalents::class)
            ->assertSuccessful()
            ->assertSee('أحمد حسن القديم')
            ->assertSee('لم يُحسم بعد');

        Livewire::actingAs($this->adminUser)
            ->test(ViewTalent::class, ['record' => $legacyTalent->getKey()])
            ->assertSuccessful()
            ->assertSee('أحمد حسن القديم')
            ->assertSee('مرشح قديم مسجل قبل التحديث.')
            ->assertDontSee('قرار المقابلة وملخص الكيس الكامل');

        Livewire::actingAs($this->adminUser)
            ->test(EditTalent::class, ['record' => $legacyTalent->getKey()])
            ->assertSuccessful()
            ->assertSee('المقابلة وقرار الكيس');

        Livewire::actingAs($this->adminUser)
            ->test(TalentKanbanBoard::class)
            ->assertSuccessful()
            ->assertSee('أحمد حسن القديم');
    }

    public function test_record_interview_case_action_records_full_not_suitable_case_review_note_and_optional_email(): void
    {
        Mail::fake();

        $talent = Talent::create([
            'full_name' => 'كريم محمود',
            'email' => 'karim@example.com',
            'phone' => '+201011223344',
            'department_id' => $this->department->id,
            'job_posting_id' => $this->jobPosting->id,
            'status_id' => $this->interviewStatus->id,
            'expected_salary' => 25000,
        ]);

        $review = app(RecordInterviewCaseAction::class)->execute($talent, [
            'interview_stage' => InterviewStage::TECHNICAL->value,
            'attendance_status' => InterviewAttendance::ATTENDED->value,
            'rating' => 3,
            'decision_outcome' => TalentDecisionOutcome::NOT_SUITABLE->value,
            'decision_reason' => TalentDecisionReason::SALARY_MISMATCH->value,
            'reapply_eligibility' => ReapplyEligibility::ELIGIBLE_NOW->value,
            'recommended_job_posting_id' => $this->alternativeJobPosting->id,
            'interview_salary_expectation' => 45000,
            'notice_period' => 'شهر واحد',
            'strengths' => 'تواصل ممتاز وخبرة قوية في Laravel',
            'weaknesses' => 'الراتب المطلوب يفوق ميزانية الدور الحالي',
            'decision_summary' => 'حضر المقابلة الفنية وأداؤه جيد لكنه طلب 45 ألف وهو أعلى من البادجت، ومناسب لوظيفة Mid-Level.',
            'status_id' => $this->holdStatus->id,
            'send_rejection_email' => true,
            'rejection_email_subject' => 'تحديث بخصوص مقابلتك',
            'rejection_email_body' => 'شكراً لحضورك المقابلة، لم يتم اختيارك حالياً للدور المتقدم عليه.',
        ], $this->adminUser);

        $talent->refresh();

        $this->assertEquals(TalentDecisionOutcome::NOT_SUITABLE, $talent->decision_outcome);
        $this->assertEquals(TalentDecisionReason::SALARY_MISMATCH, $talent->decision_reason);
        $this->assertEquals(ReapplyEligibility::ELIGIBLE_NOW, $talent->reapply_eligibility);
        $this->assertEquals($this->alternativeJobPosting->id, $talent->recommended_job_posting_id);
        $this->assertEquals(45000, (float) $talent->interview_salary_expectation);
        $this->assertEquals($this->holdStatus->id, $talent->status_id);
        $this->assertEquals($this->adminUser->id, $talent->decision_by);
        $this->assertNotNull($talent->decision_at);

        $this->assertDatabaseHas('talent_reviews', [
            'id' => $review->id,
            'talent_id' => $talent->id,
            'reviewer_id' => $this->adminUser->id,
            'interview_stage' => InterviewStage::TECHNICAL->value,
            'attendance_status' => InterviewAttendance::ATTENDED->value,
            'recommendation' => TalentDecisionOutcome::NOT_SUITABLE->value,
            'decision_reason' => TalentDecisionReason::SALARY_MISMATCH->value,
            'notice_period' => 'شهر واحد',
        ]);

        $this->assertDatabaseHas('talent_notes', [
            'talent_id' => $talent->id,
            'user_id' => $this->adminUser->id,
            'category' => TalentNoteCategory::REJECTION->value,
        ]);

        $this->assertDatabaseHas('talent_notes', [
            'talent_id' => $talent->id,
            'user_id' => $this->adminUser->id,
            'category' => TalentNoteCategory::COMMUNICATION->value,
        ]);
    }

    public function test_can_record_interview_case_from_default_view_talent_and_table_actions(): void
    {
        $talent = Talent::create([
            'full_name' => 'سارة عادل',
            'email' => 'sara@example.com',
            'phone' => '+201022334455',
            'department_id' => $this->department->id,
            'job_posting_id' => $this->jobPosting->id,
            'status_id' => $this->interviewStatus->id,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(ViewTalent::class, ['record' => $talent->getKey()])
            ->callAction('recordInterviewCase', data: [
                'interview_stage' => InterviewStage::HR_SCREENING->value,
                'attendance_status' => InterviewAttendance::ATTENDED->value,
                'rating' => 2,
                'decision_outcome' => TalentDecisionOutcome::REJECTED->value,
                'decision_reason' => TalentDecisionReason::TECHNICAL_GAP->value,
                'status_id' => $this->rejectedStatus->id,
                'reapply_eligibility' => ReapplyEligibility::AFTER_6_MONTHS->value,
                'strengths' => 'منظمة وملتزمة بالمواعيد',
                'weaknesses' => 'نقص في الخبرة العملية بقواعد البيانات',
                'decision_summary' => 'حضرت المقابلة ولكن هناك فجوة واضحة في المهارات الأساسية المطلوبة للوظيفة.',
                'send_rejection_email' => false,
            ])
            ->assertHasNoActionErrors();

        $talent->refresh();

        $this->assertEquals(TalentDecisionOutcome::REJECTED, $talent->decision_outcome);
        $this->assertEquals($this->rejectedStatus->id, $talent->status_id);

        Livewire::actingAs($this->adminUser)
            ->test(ViewTalent::class, ['record' => $talent->getKey()])
            ->assertSee('قرار المقابلة وملخص الكيس الكامل')
            ->assertSee('مرفوض (غير مقبول)')
            ->assertSee('فجوة في المهارات الفنية / الخبرة')
            ->assertSee('فجوة واضحة في المهارات الأساسية');

        // Verify table action in default ListTalents works too
        $secondTalent = Talent::create([
            'full_name' => 'محمود طارق',
            'email' => 'mahmoud@example.com',
            'phone' => '+201033445566',
            'department_id' => $this->department->id,
            'job_posting_id' => $this->jobPosting->id,
            'status_id' => $this->interviewStatus->id,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(ListTalents::class)
            ->callTableAction('recordInterviewCase', $secondTalent, data: [
                'interview_stage' => InterviewStage::MANAGER->value,
                'attendance_status' => InterviewAttendance::NO_SHOW->value,
                'decision_outcome' => TalentDecisionOutcome::NO_SHOW->value,
                'decision_reason' => TalentDecisionReason::NO_SHOW->value,
                'status_id' => $this->rejectedStatus->id,
                'reapply_eligibility' => ReapplyEligibility::DO_NOT_REHIRE->value,
                'decision_summary' => 'تم تحديد موعد المقابلة وتأكيده ولم يحضر المرشح دون اعتذار.',
                'send_rejection_email' => false,
            ])
            ->assertHasNoTableActionErrors();

        $secondTalent->refresh();
        $this->assertEquals(TalentDecisionOutcome::NO_SHOW, $secondTalent->decision_outcome);
        $this->assertEquals($this->rejectedStatus->id, $secondTalent->status_id);
    }

    public function test_kanban_drop_on_negative_status_prompts_case_modal_instead_of_silent_move(): void
    {
        $talent = Talent::create([
            'full_name' => 'يوسف نبيل',
            'email' => 'youssef@example.com',
            'phone' => '+201044556677',
            'department_id' => $this->department->id,
            'job_posting_id' => $this->jobPosting->id,
            'status_id' => $this->newStatus->id,
        ]);

        // 1. Moving to normal stage (مرحلة الإنترفيو) updates directly
        Livewire::actingAs($this->adminUser)
            ->test(TalentKanbanBoard::class)
            ->call('updateTalentStatus', $talent->id, $this->interviewStatus->id)
            ->assertActionNotMounted('recordCase');

        $this->assertEquals($this->interviewStatus->id, $talent->fresh()->status_id);

        // 2. Dropping onto negative status (مرفوض) keeps current status until case modal is submitted
        $component = Livewire::actingAs($this->adminUser)
            ->test(TalentKanbanBoard::class)
            ->call('updateTalentStatus', $talent->id, $this->rejectedStatus->id)
            ->assertActionMounted('recordCase');

        $this->assertEquals($this->interviewStatus->id, $talent->fresh()->status_id);

        // 3. Submitting the mounted Kanban case modal saves full case and updates status
        $component->callMountedAction([
            'interview_stage' => InterviewStage::TECHNICAL->value,
            'attendance_status' => InterviewAttendance::ATTENDED->value,
            'rating' => 2,
            'decision_outcome' => TalentDecisionOutcome::REJECTED->value,
            'decision_reason' => TalentDecisionReason::COMMUNICATION_CULTURE->value,
            'status_id' => $this->rejectedStatus->id,
            'reapply_eligibility' => ReapplyEligibility::AFTER_12_MONTHS->value,
            'decision_summary' => 'تم إجراء المقابلة عبر الكانبان واتضح عدم التوافق في أسلوب العمل والتواصل.',
            'send_rejection_email' => false,
        ])->assertHasNoActionErrors();

        $talent->refresh();
        $this->assertEquals($this->rejectedStatus->id, $talent->status_id);
        $this->assertEquals(TalentDecisionOutcome::REJECTED, $talent->decision_outcome);
        $this->assertEquals(TalentDecisionReason::COMMUNICATION_CULTURE, $talent->decision_reason);
    }

    public function test_notes_and_reviews_relation_managers_create_records_safely(): void
    {
        $talent = Talent::create([
            'full_name' => 'نور الهدى',
            'email' => 'noor@example.com',
            'phone' => '+201055667788',
            'status_id' => $this->interviewStatus->id,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(NotesRelationManager::class, [
                'ownerRecord' => $talent,
                'pageClass' => ViewTalent::class,
            ])
            ->callTableAction('create', data: [
                'category' => TalentNoteCategory::INTERVIEW->value,
                'body' => 'ملاحظة تفصيلية من شاشة الملاحظات الافتراضية.',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('talent_notes', [
            'talent_id' => $talent->id,
            'user_id' => $this->adminUser->id,
            'category' => TalentNoteCategory::INTERVIEW->value,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(ReviewsRelationManager::class, [
                'ownerRecord' => $talent,
                'pageClass' => ViewTalent::class,
            ])
            ->callTableAction('create', data: [
                'interview_stage' => InterviewStage::FINAL->value,
                'attendance_status' => InterviewAttendance::ATTENDED->value,
                'rating' => 4,
                'recommendation' => TalentDecisionOutcome::RUNNER_UP->value,
                'decision_reason' => TalentDecisionReason::BETTER_CANDIDATE->value,
                'notes' => 'مرشحة احتياطية ممتازة لوظائف قادمة.',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('talent_reviews', [
            'talent_id' => $talent->id,
            'reviewer_id' => $this->adminUser->id,
            'interview_stage' => InterviewStage::FINAL->value,
            'recommendation' => TalentDecisionOutcome::RUNNER_UP->value,
        ]);
    }

    public function test_returning_candidate_shows_previous_case_alert_on_view_page(): void
    {
        Talent::create([
            'full_name' => 'رامي سامي',
            'email' => 'rami@example.com',
            'phone' => '+201066778899',
            'current_company' => 'شركة النيل للتقنية',
            'job_posting_id' => $this->jobPosting->id,
            'status_id' => $this->rejectedStatus->id,
            'decision_outcome' => TalentDecisionOutcome::NOT_SUITABLE->value,
            'decision_reason' => TalentDecisionReason::UNDERQUALIFIED->value,
            'reapply_eligibility' => ReapplyEligibility::AFTER_6_MONTHS->value,
            'decision_summary' => 'خبرته أقل من المطلوب لدور Senior في التقديم السابق.',
            'decision_at' => now()->subMonths(3),
        ]);

        // Re-application (matched automatically by DetectDuplicateTalentAction via full_name + current_company)
        $newApplication = Talent::create([
            'full_name' => 'رامي سامي',
            'email' => 'rami.new@example.com',
            'phone' => '+201099887766',
            'current_company' => 'شركة النيل للتقنية',
            'job_posting_id' => $this->alternativeJobPosting->id,
            'status_id' => $this->newStatus->id,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(ViewTalent::class, ['record' => $newApplication->getKey()])
            ->assertSee('تنبيه: يوجد سجل مقابلة أو قرار سابق لهذا المرشح')
            ->assertSee('خبرته أقل من المطلوب لدور Senior في التقديم السابق.');
    }

    public function test_unauthorized_user_cannot_update_kanban_status(): void
    {
        $talent = Talent::create([
            'full_name' => 'طارق فؤاد',
            'email' => 'tarek-auth@example.com',
            'phone' => '+201077889900',
            'status_id' => $this->newStatus->id,
        ]);

        // Regular portal-only employee is redirected to /portal away from Kanban
        $this->actingAs($this->employeeUser)
            ->get('/admin/talents/kanban')
            ->assertRedirect('/portal');

        // Read-only viewer inside admin panel cannot mutate Kanban status
        $viewerRole = Role::firstOrCreate(['name' => 'مراقب توظيف', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view_any_talent', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view_talent', 'guard_name' => 'web']);
        $viewerRole->syncPermissions(['view_any_talent', 'view_talent']);

        $viewerUser = User::factory()->create(['email' => 'viewer@example.com']);
        $viewerUser->assignRole($viewerRole);

        Livewire::actingAs($viewerUser)
            ->test(TalentKanbanBoard::class)
            ->call('updateTalentStatus', $talent->id, $this->interviewStatus->id);

        $this->assertEquals($this->newStatus->id, $talent->fresh()->status_id);
    }
}
