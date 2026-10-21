<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\JobPosting;
use App\Models\Location;
use App\Models\Talent;
use App\Models\TalentStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected JobPosting $job;
    protected Department $department;
    protected Location $location;

    protected function setUp(): void
    {
        parent::setUp();

        $this->department = Department::create(['name' => 'الهندسة والبرمجيات']);
        $this->location = Location::create(['name' => 'المقر الرئيسي - القاهرة']);
        TalentStatus::create(['name' => 'جديد', 'is_default' => true]);

        $this->job = JobPosting::create([
            'title' => 'مطور لارافيل أول (Senior Laravel Developer)',
            'description' => '<p>نبحث عن مطور شغوف للانضمام لفريقنا التقني.</p>',
            'department_id' => $this->department->id,
            'location_id' => $this->location->id,
            'is_active' => true,
            'brand_color' => '#0d9488',
            'form_layout' => 'wizard',
            'primary_fields_config' => [
                'show_full_name' => true,
                'show_email' => true,
                'show_phone' => true,
                'show_linkedin' => true,
                'show_portfolio' => true,
                'show_current_job_title' => true,
                'show_current_company' => true,
                'show_years_of_experience' => true,
                'show_expected_salary' => true,
                'show_seniority_level' => true,
                'show_work_preference' => true,
                'show_availability' => true,
                'show_resume' => true,
                'show_general_notes' => true,
            ],
            'form_schema' => [
                [
                    'type' => 'text',
                    'data' => [
                        'name' => 'github_profile',
                        'label' => 'حساب GitHub',
                        'is_required' => true,
                    ]
                ],
                [
                    'type' => 'rating',
                    'data' => [
                        'name' => 'laravel_rating',
                        'label' => 'تقييم خبرتك في Laravel',
                        'is_required' => false,
                    ]
                ]
            ]
        ]);
    }

    public function test_can_view_active_job_application_page(): void
    {
        $response = $this->get(route('jobs.apply', $this->job->id));

        $response->assertStatus(200);
        $response->assertSee($this->job->title);
        $response->assertSee('الهندسة والبرمجيات');
        $response->assertSee('المقر الرئيسي - القاهرة');
        $response->assertSee('البيانات الشخصية');
    }

    public function test_inactive_job_returns_404(): void
    {
        $this->job->update(['is_active' => false]);

        $response = $this->get(route('jobs.apply', $this->job->id));

        $response->assertStatus(404);
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->from(route('jobs.apply', $this->job->id))
            ->post(route('jobs.submit', $this->job->id), []);

        $response->assertRedirect(route('jobs.apply', $this->job->id));
        $response->assertSessionHasErrors(['full_name', 'email', 'phone', 'resume', 'custom_github_profile']);
    }

    public function test_validation_fails_for_invalid_email_and_short_phone(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

        $response = $this->from(route('jobs.apply', $this->job->id))
            ->post(route('jobs.submit', $this->job->id), [
                'full_name' => 'أحمد علي',
                'email' => 'invalid-email-format',
                'phone' => '123',
                'resume' => $file,
                'custom_github_profile' => 'https://github.com/example',
            ]);

        $response->assertRedirect(route('jobs.apply', $this->job->id));
        $response->assertSessionHasErrors(['email', 'phone']);
    }

    public function test_submitting_application_successfully_creates_talent_and_document(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('my_resume.pdf', 200, 'application/pdf');

        $response = $this->from(route('jobs.apply', $this->job->id))
            ->post(route('jobs.submit', $this->job->id), [
                'full_name' => 'طارق عبد السلام',
                'email' => 'tarek@example.com',
                'phone' => '+201012345678',
                'linkedin_url' => 'https://linkedin.com/in/tarek',
                'portfolio_url' => 'https://tarek-dev.com',
                'current_job_title' => 'Senior Backend Developer',
                'current_company' => 'Tech Solutions',
                'years_of_experience' => 5,
                'expected_salary' => 35000,
                'seniority_level' => 'senior',
                'work_preference' => 'remote',
                'availability' => 'immediately',
                'general_notes' => 'مستعد للبدء فوراً ومتحمس للمشروع.',
                'resume' => $file,
                'custom_github_profile' => 'https://github.com/tarekdev',
                'custom_laravel_rating' => 5,
            ]);

        $response->assertRedirect(route('jobs.apply', $this->job->id));
        $response->assertSessionHas('success');

        // Follow redirect to verify rendering of the success screen without any Undefined variable errors
        $followedResponse = $this->get(route('jobs.apply', $this->job->id));
        $followedResponse->assertStatus(200);
        $followedResponse->assertSee('تم استلام طلبك بنجاح');

        $this->assertDatabaseHas('talents', [
            'full_name' => 'طارق عبد السلام',
            'email' => 'tarek@example.com',
            'phone' => '+201012345678',
            'job_posting_id' => $this->job->id,
            'expected_salary' => 35000,
            'years_of_experience' => 5,
        ]);

        $talent = Talent::where('email', 'tarek@example.com')->first();
        $this->assertNotNull($talent);
        $this->assertEquals('https://github.com/tarekdev', $talent->custom_answers['github_profile']['value'] ?? null);

        // Verify document was recorded
        $this->assertDatabaseHas('talent_documents', [
            'talent_id' => $talent->id,
            'file_name' => 'my_resume.pdf',
        ]);
    }

    public function test_cannot_apply_twice_with_same_email_for_same_job(): void
    {
        Storage::fake('public');

        // First application
        Talent::create([
            'full_name' => 'محمد سعيد',
            'email' => 'mohamed@example.com',
            'phone' => '01011122233',
            'job_posting_id' => $this->job->id,
        ]);

        $file = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

        $response = $this->from(route('jobs.apply', $this->job->id))
            ->post(route('jobs.submit', $this->job->id), [
                'full_name' => 'محمد سعيد الثاني',
                'email' => 'mohamed@example.com',
                'phone' => '01099988877',
                'resume' => $file,
                'custom_github_profile' => 'https://github.com/mohamed',
            ]);

        $response->assertRedirect(route('jobs.apply', $this->job->id));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_single_page_layout_mode(): void
    {
        $this->job->update(['form_layout' => 'single_page']);

        $response = $this->get(route('jobs.apply', $this->job->id));

        $response->assertStatus(200);
        $response->assertDontSee('المتابعة للخطوة التالية');
        $response->assertSee('إرسال طلب التقديم');
    }

    public function test_success_screen_renders_without_errors(): void
    {
        $response = $this->withSession(['success' => 'شكراً لك، تم استلام طلبك بنجاح!'])
            ->get(route('jobs.apply', $this->job->id));

        $response->assertStatus(200);
        $response->assertSee('تم استلام طلبك بنجاح');
        $response->assertSee('شكراً لك، تم استلام طلبك بنجاح!');
    }
}
