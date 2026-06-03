<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use App\Modules\Internship\Models\Internship;
use App\Modules\Internship\Models\InternshipScore;
use App\Modules\Student\Models\Student;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase6InternshipTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected $internshipViewer;

    protected $internshipCreator;

    protected $internshipUpdater;

    protected $internshipDeleter;

    protected $industryCreator;

    protected $industryUpdater;

    protected $industryDeleter;

    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        Permission::firstOrCreate(['name' => 'internship.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'internship.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'internship.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'internship.delete', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'industry.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'industry.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'industry.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'industry.delete', 'guard_name' => 'web']);

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');

        $this->internshipViewer = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Internship Viewer', 'guard_name' => 'web']);
        $r->syncPermissions(['internship.view', 'industry.view']);
        $this->internshipViewer->assignRole($r);

        $this->internshipCreator = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Internship Creator', 'guard_name' => 'web']);
        $r->syncPermissions(['internship.view', 'internship.create']);
        $this->internshipCreator->assignRole($r);

        $this->internshipUpdater = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Internship Updater', 'guard_name' => 'web']);
        $r->syncPermissions(['internship.view', 'internship.update']);
        $this->internshipUpdater->assignRole($r);

        $this->internshipDeleter = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Internship Deleter', 'guard_name' => 'web']);
        $r->syncPermissions(['internship.view', 'internship.delete']);
        $this->internshipDeleter->assignRole($r);

        $this->industryCreator = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Industry Creator', 'guard_name' => 'web']);
        $r->syncPermissions(['industry.view', 'industry.create']);
        $this->industryCreator->assignRole($r);

        $this->industryUpdater = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Industry Updater', 'guard_name' => 'web']);
        $r->syncPermissions(['industry.view', 'industry.update']);
        $this->industryUpdater->assignRole($r);

        $this->industryDeleter = User::factory()->create();
        $r = Role::firstOrCreate(['name' => 'Industry Deleter', 'guard_name' => 'web']);
        $r->syncPermissions(['industry.view', 'industry.delete']);
        $this->industryDeleter->assignRole($r);

        $this->unauthorizedUser = User::factory()->create();
    }

    protected function createStudent($attributes = [])
    {
        return Student::create(array_merge([
            'name' => 'Student Test',
            'nis' => '12345',
            'gender' => 'L',
            'status' => 'active',
        ], $attributes));
    }

    protected function createPartner($attributes = [])
    {
        return IndustryPartner::create(array_merge(['name' => 'PT Test'], $attributes));
    }

    protected function createInternship($student, $partner, $overrides = [])
    {
        return Internship::create(array_merge([
            'student_id' => $student->id,
            'industry_partner_id' => $partner->id,
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonth(),
            'status' => 'ongoing',
        ], $overrides));
    }

    // ===================== Step 1: Dashboard =====================

    #[Test]
    public function guest_cannot_access_internship_dashboard(): void
    {
        $this->get(route('admin.internships.dashboard'))->assertRedirect('/login');
    }

    #[Test]
    public function user_without_internship_view_cannot_access_internship_dashboard(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $this->get(route('admin.internships.dashboard'))->assertStatus(403);
    }

    #[Test]
    public function user_with_internship_view_can_access_internship_dashboard(): void
    {
        $this->actingAs($this->internshipViewer);
        $this->get(route('admin.internships.dashboard'))
            ->assertStatus(200)
            ->assertSeeText('PKL / Internship Dashboard');
    }

    #[Test]
    public function super_admin_can_access_internship_dashboard(): void
    {
        $this->actingAs($this->superAdmin);
        $this->get(route('admin.internships.dashboard'))
            ->assertStatus(200)
            ->assertSeeText('PKL / Internship Dashboard');
    }

    #[Test]
    public function dashboard_counts_planned_ongoing_completed_placements(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();

        $this->createInternship($student, $partner, ['status' => 'planned']);

        $student2 = $this->createStudent(['nis' => '11111']);
        $this->createInternship($student2, $partner, ['status' => 'ongoing']);

        $student3 = $this->createStudent(['nis' => '22222']);
        $this->createInternship($student3, $partner, ['status' => 'completed']);

        $this->actingAs($this->superAdmin);
        $response = $this->get(route('admin.internships.dashboard'));

        $response->assertSeeText('1'); // planned=1, ongoing=1, completed=1
        $response->assertSeeText('3'); // total=3
    }

    // ===================== Step 2: Industry Partner CRUD =====================

    #[Test]
    public function user_with_industry_view_can_access_industry_partner_index(): void
    {
        $this->actingAs($this->internshipViewer);
        $this->get(route('admin.industry-partners.index'))->assertStatus(200);
    }

    #[Test]
    public function user_with_industry_create_can_create_industry_partner(): void
    {
        $this->actingAs($this->industryCreator);
        $response = $this->post(route('admin.industry-partners.store'), [
            'name' => 'PT Teknologi Mandiri',
            'sector' => 'Teknologi',
        ]);

        $response->assertRedirect(route('admin.industry-partners.index'));
        $this->assertDatabaseHas('industry_partners', ['name' => 'PT Teknologi Mandiri']);
    }

    #[Test]
    public function validation_fails_when_industry_partner_name_is_missing(): void
    {
        $this->actingAs($this->industryCreator);
        $this->post(route('admin.industry-partners.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    #[Test]
    public function user_with_industry_update_can_update_industry_partner(): void
    {
        $partner = $this->createPartner(['name' => 'Old Name']);
        $this->actingAs($this->industryUpdater);

        $this->put(route('admin.industry-partners.update', $partner), [
            'name' => 'Updated Name',
        ])->assertRedirect(route('admin.industry-partners.index'));

        $this->assertDatabaseHas('industry_partners', ['name' => 'Updated Name']);
    }

    #[Test]
    public function user_with_industry_delete_can_delete_unused_industry_partner(): void
    {
        $partner = $this->createPartner(['name' => 'To Delete']);
        $this->actingAs($this->industryDeleter);

        $this->delete(route('admin.industry-partners.destroy', $partner))
            ->assertRedirect(route('admin.industry-partners.index'));

        $this->assertDatabaseMissing('industry_partners', ['name' => 'To Delete']);
    }

    #[Test]
    public function industry_partner_used_by_internship_cannot_be_deleted(): void
    {
        $partner = $this->createPartner(['name' => 'Protected']);
        $student = $this->createStudent();
        $this->createInternship($student, $partner);

        $this->actingAs($this->industryDeleter);
        $this->delete(route('admin.industry-partners.destroy', $partner))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('industry_partners', ['name' => 'Protected']);
    }

    #[Test]
    public function industry_partner_routes_exist(): void
    {
        $routes = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        foreach ($routes as $r) {
            $this->assertTrue(Route::has('admin.industry-partners.'.$r));
        }
    }

    // ===================== Step 3: Internship Placement CRUD =====================

    #[Test]
    public function user_with_internship_view_can_access_internship_index(): void
    {
        $this->actingAs($this->internshipViewer);
        $this->get(route('admin.internships.index'))->assertStatus(200);
    }

    #[Test]
    public function user_with_internship_create_can_create_internship_placement(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();

        $this->actingAs($this->internshipCreator);
        $response = $this->post(route('admin.internships.store'), [
            'student_id' => $student->id,
            'industry_partner_id' => $partner->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'status' => 'planned',
        ]);

        $response->assertRedirect(route('admin.internships.index'));
        $this->assertDatabaseHas('internships', [
            'student_id' => $student->id,
            'status' => 'planned',
        ]);
    }

    #[Test]
    public function duplicate_active_placement_for_same_student_is_rejected(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();

        $this->createInternship($student, $partner, ['status' => 'ongoing']);

        $this->actingAs($this->internshipCreator);
        $response = $this->post(route('admin.internships.store'), [
            'student_id' => $student->id,
            'industry_partner_id' => $partner->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'status' => 'planned',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Internship::where('student_id', $student->id)->count());
    }

    #[Test]
    public function validation_fails_when_internship_end_date_is_before_start_date(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();

        $this->actingAs($this->internshipCreator);
        $response = $this->post(route('admin.internships.store'), [
            'student_id' => $student->id,
            'industry_partner_id' => $partner->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->subDays(1)->format('Y-m-d'),
            'status' => 'planned',
        ]);

        $response->assertSessionHasErrors('end_date');
    }

    #[Test]
    public function ongoing_placement_cannot_be_deleted(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner, ['status' => 'ongoing']);

        $this->actingAs($this->internshipDeleter);
        $response = $this->delete(route('admin.internships.destroy', $internship));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('internships', ['id' => $internship->id]);
    }

    // ===================== Step 4: Logbook & Monitoring =====================

    #[Test]
    public function user_with_internship_create_can_create_logbook_entry(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner, [
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(10),
            'status' => 'ongoing',
        ]);

        $this->actingAs($this->internshipCreator);
        $response = $this->post(route('admin.internships.logs.store'), [
            'internship_id' => $internship->id,
            'activity_date' => now()->format('Y-m-d'),
            'activity' => 'Mengerjakan module login',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('internship_logs', [
            'internship_id' => $internship->id,
            'activity' => 'Mengerjakan module login',
        ]);
    }

    #[Test]
    public function logbook_date_outside_internship_period_is_rejected(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner, [
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(10),
        ]);

        $this->actingAs($this->internshipCreator);
        $response = $this->post(route('admin.internships.logs.store'), [
            'internship_id' => $internship->id,
            'activity_date' => now()->format('Y-m-d'), // Before start_date
            'activity' => 'Dini hari sudah kerja',
        ]);

        $response->assertSessionHas('error');
    }

    #[Test]
    public function logbook_cannot_be_created_for_cancelled_internship(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner, ['status' => 'cancelled']);

        $this->actingAs($this->internshipCreator);
        $response = $this->post(route('admin.internships.logs.store'), [
            'internship_id' => $internship->id,
            'activity_date' => now()->format('Y-m-d'),
            'activity' => 'Tetap rajin walau batal',
        ]);

        $response->assertSessionHas('error');
    }

    // ===================== Step 5: Scoring =====================

    #[Test]
    public function user_with_internship_update_can_create_internship_score(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner);

        $this->actingAs($this->internshipUpdater);
        $response = $this->post(route('admin.internships.scores.store'), [
            'internship_id' => $internship->id,
            'discipline_score' => 90,
            'skill_score' => 80,
            'attitude_score' => 85,
            'report_score' => 85,
            'assessed_by' => 'Manager Industri',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('internship_scores', [
            'internship_id' => $internship->id,
            'final_score' => 85,
            'predicate' => 'A',
        ]);

        $internship->refresh();
        $this->assertEquals(85, $internship->grade);
    }

    #[Test]
    public function score_predicate_is_calculated_correctly(): void
    {
        $this->assertEquals('A', InternshipScore::calculatePredicate(85));
        $this->assertEquals('B', InternshipScore::calculatePredicate(75));
        $this->assertEquals('C', InternshipScore::calculatePredicate(65));
        $this->assertEquals('D', InternshipScore::calculatePredicate(64));
    }

    #[Test]
    public function invalid_score_over_100_is_rejected(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner);

        $this->actingAs($this->internshipUpdater);
        $response = $this->post(route('admin.internships.scores.store'), [
            'internship_id' => $internship->id,
            'discipline_score' => 101,
        ]);

        $response->assertSessionHasErrors('discipline_score');
    }

    #[Test]
    public function cancelled_internship_cannot_be_scored(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner, ['status' => 'cancelled']);

        $this->actingAs($this->internshipUpdater);
        $response = $this->post(route('admin.internships.scores.store'), [
            'internship_id' => $internship->id,
            'discipline_score' => 90,
        ]);

        $response->assertSessionHas('error');
    }

    #[Test]
    public function internship_detail_shows_score(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $internship = $this->createInternship($student, $partner);

        $this->actingAs($this->internshipViewer);
        $response = $this->get(route('admin.internships.show', $internship));
        $response->assertSeeText('Penilaian');
    }

    #[Test]
    public function scoring_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.internships.scores.store'));
    }

    // ===================== Step 6: Reports & Export =====================

    #[Test]
    public function user_with_internship_view_can_access_report_page(): void
    {
        $this->actingAs($this->internshipViewer);
        $this->get(route('admin.internships.reports.index'))->assertStatus(200)->assertSeeText('Laporan PKL');
    }

    #[Test]
    public function user_without_internship_view_cannot_access_report_page(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $this->get(route('admin.internships.reports.index'))->assertStatus(403);
    }

    #[Test]
    public function report_page_shows_totals(): void
    {
        $student = $this->createStudent();
        $partner = $this->createPartner();
        $this->createInternship($student, $partner, ['status' => 'completed', 'grade' => 80]);

        $this->actingAs($this->internshipViewer);
        $response = $this->get(route('admin.internships.reports.index'));
        $response->assertSeeText('Total Penempatan');
        $response->assertSeeText('Rata-rata Nilai');
    }

    #[Test]
    public function csv_export_returns_successful_response(): void
    {
        $this->actingAs($this->internshipViewer);
        $response = $this->get(route('admin.internships.reports.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function report_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.internships.reports.index'));
        $this->assertTrue(Route::has('admin.internships.reports.export'));
    }
}
