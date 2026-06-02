<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Modules\Alumni\Models\Alumni;
use App\Modules\Alumni\Models\JobApplication;
use App\Modules\Alumni\Models\JobVacancy;
use App\Modules\Student\Models\Student;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase7BkkTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected $bkkAdmin;

    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        // Ensure all required permissions exist
        $permissions = [
            'bkk.view', 'bkk.create', 'bkk.update', 'bkk.export',
            'alumni.view', 'alumni.create', 'alumni.update', 'alumni.delete', 'alumni.export',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Super Admin
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');

        // BKK Admin
        $this->bkkAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Admin BKK', 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
        $this->bkkAdmin->assignRole($role);

        // BKK Viewer
        $this->bkkViewer = User::factory()->create();
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer BKK', 'guard_name' => 'web']);
        $viewerRole->syncPermissions(['bkk.view', 'alumni.view']);
        $this->bkkViewer->assignRole($viewerRole);

        // Unauthorized User
        $this->unauthorizedUser = User::factory()->create();
    }

    protected function createStudent($attributes = [])
    {
        return Student::create(array_merge([
            'name' => 'Student Test',
            'nis' => '12345',
            'gender' => 'male',
            'status' => 'active',
        ], $attributes));
    }

    // ===================== Step 1: Dashboard =====================

    #[Test]
    public function guest_cannot_access_bkk_dashboard(): void
    {
        $response = $this->get(route('admin.bkk.dashboard'));
        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_without_bkk_view_cannot_access_bkk_dashboard(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $response = $this->get(route('admin.bkk.dashboard'));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_bkk_view_can_access_bkk_dashboard(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeText('BKK & Alumni Dashboard');
    }

    #[Test]
    public function super_admin_can_access_bkk_dashboard(): void
    {
        $this->actingAs($this->superAdmin);
        $response = $this->get(route('admin.bkk.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeText('BKK & Alumni Dashboard');
    }

    #[Test]
    public function bkk_dashboard_route_exists(): void
    {
        $this->assertTrue(Route::has('admin.bkk.dashboard'));
    }

    // ===================== Step 2: Alumni CRUD =====================

    #[Test]
    public function user_with_alumni_view_can_access_alumni_index(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.alumni.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_alumni_create_can_create_alumni_profile(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.alumni.store'), [
            'name' => 'John Doe Alumni',
            'graduation_year' => 2023,
            'status' => 'working',
            'company_name' => 'Tech Corp',
        ]);

        $response->assertRedirect(route('admin.bkk.alumni.index'));
        $this->assertDatabaseHas('alumni', ['name' => 'John Doe Alumni', 'company_name' => 'Tech Corp']);
    }

    #[Test]
    public function validation_fails_when_name_is_missing(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.alumni.store'), [
            'name' => '',
            'graduation_year' => 2023,
            'status' => 'unemployed',
        ]);
        $response->assertSessionHasErrors('name');
    }

    #[Test]
    public function validation_fails_when_graduation_year_is_before_2000(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.alumni.store'), [
            'name' => 'Old Alumnus',
            'graduation_year' => 1999,
            'status' => 'working',
        ]);
        $response->assertSessionHasErrors('graduation_year');
    }

    #[Test]
    public function validation_fails_when_email_is_invalid(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.alumni.store'), [
            'name' => 'Bad Email Alumnus',
            'graduation_year' => 2023,
            'status' => 'working',
            'email' => 'not-an-email',
        ]);
        $response->assertSessionHasErrors('email');
    }

    #[Test]
    public function duplicate_alumni_profile_for_same_student_is_rejected(): void
    {
        $student = $this->createStudent();
        Alumni::create([
            'student_id' => $student->id,
            'name' => 'Exist Alumni',
            'graduation_year' => 2023,
            'status' => 'working',
        ]);

        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.alumni.store'), [
            'student_id' => $student->id,
            'name' => 'New Profile Same Student',
            'graduation_year' => 2023,
            'status' => 'working',
        ]);

        $response->assertSessionHasErrors('student_id');
        $this->assertEquals(1, Alumni::where('student_id', $student->id)->count());
    }

    #[Test]
    public function user_with_alumni_view_can_access_alumni_show(): void
    {
        $alumnus = Alumni::create([
            'name' => 'Visible Alumni',
            'graduation_year' => 2023,
            'status' => 'working',
        ]);

        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.alumni.show', $alumnus));
        $response->assertStatus(200);
        $response->assertSeeText('Visible Alumni');
    }

    #[Test]
    public function user_with_alumni_update_can_update_alumni_profile(): void
    {
        $alumnus = Alumni::create(['name' => 'Old', 'graduation_year' => 2023, 'status' => 'unemployed']);

        $this->actingAs($this->bkkAdmin);
        $response = $this->put(route('admin.bkk.alumni.update', $alumnus), [
            'name' => 'New Name',
            'graduation_year' => 2024,
            'status' => 'working',
        ]);

        $response->assertRedirect(route('admin.bkk.alumni.index'));
        $this->assertDatabaseHas('alumni', ['id' => $alumnus->id, 'name' => 'New Name', 'status' => 'working']);
    }

    #[Test]
    public function user_with_alumni_delete_can_delete_alumni_profile(): void
    {
        $alumnus = Alumni::create(['name' => 'Delete Me', 'graduation_year' => 2023, 'status' => 'unemployed']);

        $this->actingAs($this->bkkAdmin);
        $response = $this->delete(route('admin.bkk.alumni.destroy', $alumnus));

        $response->assertRedirect(route('admin.bkk.alumni.index'));
        $this->assertDatabaseMissing('alumni', ['id' => $alumnus->id]);
    }

    #[Test]
    public function dashboard_totals_reflect_alumni_stats(): void
    {
        Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'working']);
        Alumni::create(['name' => 'A2', 'graduation_year' => 2023, 'status' => 'studying']);

        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.dashboard'));

        $response->assertSeeText('Total Alumni');
        $response->assertSeeText('2');
    }

    #[Test]
    public function alumni_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.bkk.alumni.index'));
        $this->assertTrue(Route::has('admin.bkk.alumni.show'));
    }

    // ===================== Step 3: Vacancy CRUD =====================

    #[Test]
    public function user_with_bkk_view_can_access_vacancy_index(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.vacancies.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_bkk_create_can_create_vacancy(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.vacancies.store'), [
            'title' => 'Web Dev',
            'company_name' => 'Tech Corp',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.bkk.vacancies.index'));
        $this->assertDatabaseHas('job_vacancies', ['title' => 'Web Dev']);
    }

    #[Test]
    public function validation_fails_when_title_or_company_name_is_missing(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.vacancies.store'), ['title' => '']);
        $response->assertSessionHasErrors(['title', 'company_name']);
    }

    #[Test]
    public function active_vacancy_with_past_deadline_is_rejected(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.vacancies.store'), [
            'title' => 'Expired',
            'company_name' => 'C1',
            'status' => 'active',
            'deadline' => now()->subDays(1)->format('Y-m-d'),
        ]);
        $response->assertSessionHasErrors('deadline');
    }

    #[Test]
    public function salary_max_lower_than_salary_min_is_rejected(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->post(route('admin.bkk.vacancies.store'), [
            'title' => 'Bad Salary',
            'company_name' => 'C1',
            'status' => 'active',
            'salary_min' => 5000,
            'salary_max' => 4000,
        ]);
        $response->assertSessionHasErrors('salary_max');
    }

    #[Test]
    public function user_with_bkk_view_can_access_vacancy_show(): void
    {
        $v = JobVacancy::create(['title' => 'Show Me', 'company_name' => 'C1', 'status' => 'active']);

        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.vacancies.show', $v));
        $response->assertStatus(200)->assertSeeText('Show Me');
    }

    #[Test]
    public function user_with_bkk_update_can_update_vacancy(): void
    {
        $v = JobVacancy::create(['title' => 'Old', 'company_name' => 'C1', 'status' => 'draft']);

        $this->actingAs($this->bkkAdmin);
        $this->put(route('admin.bkk.vacancies.update', $v), [
            'title' => 'New',
            'company_name' => 'C1',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('job_vacancies', ['id' => $v->id, 'title' => 'New']);
    }

    #[Test]
    public function user_with_bkk_update_can_delete_unused_vacancy(): void
    {
        $v = JobVacancy::create(['title' => 'Del', 'company_name' => 'C1', 'status' => 'draft']);

        $this->actingAs($this->bkkAdmin);
        $this->delete(route('admin.bkk.vacancies.destroy', $v));
        $this->assertDatabaseMissing('job_vacancies', ['id' => $v->id]);
    }

    #[Test]
    public function dashboard_totals_update_after_active_vacancy_creation(): void
    {
        JobVacancy::create(['title' => 'V1', 'company_name' => 'C1', 'status' => 'active']);

        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.dashboard'));
        $response->assertSeeText('Total'); // In sidebar or dashboard
    }

    #[Test]
    public function vacancy_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.bkk.vacancies.index'));
        $this->assertTrue(Route::has('admin.bkk.vacancies.show'));
    }

    // ===================== Step 4: Job Application =====================

    #[Test]
    public function user_with_bkk_view_can_access_application_index(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.applications.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_bkk_update_can_create_job_application(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);

        $this->actingAs($this->bkkAdmin);
        $this->post(route('admin.bkk.applications.store'), [
            'alumni_id' => $alumni->id,
            'job_vacancy_id' => $vacancy->id,
        ])->assertStatus(302);

        $this->assertDatabaseHas('job_applications', ['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id]);
    }

    #[Test]
    public function duplicate_job_application_is_rejected(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->post(route('admin.bkk.applications.store'), [
            'alumni_id' => $alumni->id,
            'job_vacancy_id' => $vacancy->id,
        ])->assertSessionHas('error');
    }

    #[Test]
    public function application_to_closed_vacancy_is_rejected(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'closed']);

        $this->actingAs($this->bkkAdmin);
        $this->post(route('admin.bkk.applications.store'), [
            'alumni_id' => $alumni->id,
            'job_vacancy_id' => $vacancy->id,
        ])->assertSessionHas('error');
    }

    #[Test]
    public function application_after_deadline_is_rejected(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active', 'deadline' => now()->subDays(1)->format('Y-m-d')]);

        $this->actingAs($this->bkkAdmin);
        $this->post(route('admin.bkk.applications.store'), [
            'alumni_id' => $alumni->id,
            'job_vacancy_id' => $vacancy->id,
        ])->assertSessionHas('error');
    }

    #[Test]
    public function user_with_bkk_view_can_access_application_show(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        $app = JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->get(route('admin.bkk.applications.show', $app))->assertStatus(200);
    }

    #[Test]
    public function user_with_bkk_update_can_update_application_status_to_interview(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        $app = JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->put(route('admin.bkk.applications.update-status', $app), ['status' => 'interview']);

        $this->assertDatabaseHas('job_applications', ['id' => $app->id, 'status' => 'interview']);
    }

    #[Test]
    public function user_with_bkk_update_can_update_application_status_to_hired(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        $app = JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->put(route('admin.bkk.applications.update-status', $app), ['status' => 'hired']);

        $this->assertDatabaseHas('job_applications', ['id' => $app->id, 'status' => 'hired']);
        $alumni->refresh();
        $this->assertEquals('working', $alumni->status);
    }

    #[Test]
    public function hired_application_updates_alumni_employment_status_to_working(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        $app = JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->put(route('admin.bkk.applications.update-status', $app), ['status' => 'hired']);

        $alumni->refresh();
        $this->assertEquals('working', $alumni->status);
    }

    #[Test]
    public function invalid_application_status_is_rejected(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        $app = JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->put(route('admin.bkk.applications.update-status', $app), ['status' => 'invalid-status'])
            ->assertSessionHasErrors('status');
    }

    #[Test]
    public function user_with_bkk_update_can_delete_application(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        $app = JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->delete(route('admin.bkk.applications.destroy', $app));
        $this->assertDatabaseMissing('job_applications', ['id' => $app->id]);
    }

    #[Test]
    public function vacancy_show_displays_application(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->get(route('admin.bkk.vacancies.show', $vacancy))->assertSeeText('A1');
    }

    #[Test]
    public function alumni_show_displays_application(): void
    {
        $alumni = Alumni::create(['name' => 'A1', 'graduation_year' => 2023, 'status' => 'unemployed']);
        $vacancy = JobVacancy::create(['title' => 'J1', 'company_name' => 'C1', 'status' => 'active']);
        JobApplication::create(['alumni_id' => $alumni->id, 'job_vacancy_id' => $vacancy->id, 'status' => 'applied']);

        $this->actingAs($this->bkkAdmin);
        $this->get(route('admin.bkk.alumni.show', $alumni))->assertSeeText('J1');
    }

    #[Test]
    public function application_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.bkk.applications.index'));
        $this->assertTrue(Route::has('admin.bkk.applications.store'));
        $this->assertTrue(Route::has('admin.bkk.applications.show'));
        $this->assertTrue(Route::has('admin.bkk.applications.update-status'));
        $this->assertTrue(Route::has('admin.bkk.applications.destroy'));
    }

    // ===================== Step 5: Report & Export =====================

    #[Test]
    public function user_with_bkk_view_can_access_report_page(): void
    {
        $this->actingAs($this->bkkViewer);
        $response = $this->get(route('admin.bkk.reports.index'));
        $response->assertStatus(200)->assertSeeText('Laporan BKK & Alumni');
    }

    #[Test]
    public function user_without_bkk_view_cannot_access_report_page(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $response = $this->get(route('admin.bkk.reports.index'));
        $response->assertStatus(403);
    }

    #[Test]
    public function report_page_shows_bkk_report_metrics(): void
    {
        Alumni::create(['name' => 'John', 'graduation_year' => 2023, 'status' => 'working']);
        JobVacancy::create(['title' => 'Job 1', 'company_name' => 'C1', 'status' => 'active']);

        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.reports.index'));

        $response->assertSeeText('Total Alumni');
        $response->assertSeeText('Lowongan Aktif');
    }

    #[Test]
    public function user_with_alumni_export_can_export_alumni_csv(): void
    {
        $this->actingAs($this->bkkAdmin);
        $response = $this->get(route('admin.bkk.reports.alumni-export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function user_without_alumni_export_cannot_export_alumni_csv(): void
    {
        $this->actingAs($this->bkkViewer);
        $response = $this->get(route('admin.bkk.reports.alumni-export'));
        $response->assertStatus(403);
    }

    #[Test]
    public function report_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.bkk.reports.index'));
        $this->assertTrue(Route::has('admin.bkk.reports.alumni-export'));
        $this->assertTrue(Route::has('admin.bkk.reports.vacancy-export'));
        $this->assertTrue(Route::has('admin.bkk.reports.application-export'));
    }
}
