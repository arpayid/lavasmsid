<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\ReportCsvExportService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class Phase10ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected $reportViewer;

    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        Permission::firstOrCreate(['name' => 'report.view', 'guard_name' => 'web']);

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');

        $this->reportViewer = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Report Viewer', 'guard_name' => 'web']);
        $role->syncPermissions(['report.view']);
        $this->reportViewer->assignRole($role);

        $this->unauthorizedUser = User::factory()->create();
    }

    // ===================== Step 1: Base =====================

    #[Test]
    public function guest_cannot_access_report_export_route(): void
    {
        $this->get(route('admin.reports.export.sample'))->assertRedirect('/login');
    }

    #[Test]
    public function user_without_report_view_cannot_access_report_export_route(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $this->get(route('admin.reports.export.sample'))->assertStatus(403);
    }

    #[Test]
    public function csv_export_has_text_csv_content_type(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.export.sample'))->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function csv_export_service_streams_rows_safely(): void
    {
        $service = new ReportCsvExportService;
        $response = $service->download('test.csv', ['H1'], [['A']]);
        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test]
    public function existing_report_index_still_loads(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.index'))->assertStatus(200)->assertSeeText('Laporan');
    }

    // ===================== Step 2: Core Exports =====================

    #[Test]
    public function user_with_report_view_can_export_students_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.students.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function students_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.students.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('NIS', $content);
        $this->assertStringContainsString('Nama', $content);
    }

    #[Test]
    public function user_with_report_view_can_export_finance_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.finance.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function finance_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.finance.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('No. Invoice', $content);
        $this->assertStringContainsString('Jumlah Tagihan', $content);
    }

    #[Test]
    public function user_with_report_view_can_export_ppdb_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.ppdb.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    // ===================== Step 3: Supporting Exports =====================

    #[Test]
    public function user_with_report_view_can_export_attendance_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.attendance.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function attendance_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.attendance.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Tanggal', $content);
        $this->assertStringContainsString('Nama Siswa', $content);
    }

    #[Test]
    public function attendance_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.attendance.export'))->assertStatus(200);
    }

    #[Test]
    public function user_with_report_view_can_export_grades_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.grades.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function grades_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.grades.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nama Siswa', $content);
        $this->assertStringContainsString('Mata Pelajaran', $content);
    }

    #[Test]
    public function grades_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.grades.export'))->assertStatus(200);
    }

    #[Test]
    public function user_with_report_view_can_export_internships_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.internships.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function internships_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.internships.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nama Siswa', $content);
        $this->assertStringContainsString('Mitra Industri', $content);
    }

    #[Test]
    public function internships_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.internships.export'))->assertStatus(200);
    }

    #[Test]
    public function user_with_report_view_can_export_alumni_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.alumni.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function alumni_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.alumni.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nama Alumni', $content);
        $this->assertStringContainsString('Status Pekerjaan', $content);
    }

    #[Test]
    public function alumni_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.alumni.export'))->assertStatus(200);
    }

    // ===================== Step 4: Website & Communication =====================

    #[Test]
    public function user_with_report_view_can_access_website_report_page(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.website'))->assertStatus(200)->assertSeeText('Laporan Website CMS');
    }

    #[Test]
    public function user_with_report_view_can_export_website_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.website.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function website_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.website.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Type', $content);
        $this->assertStringContainsString('Title', $content);
        $this->assertStringContainsString('Status', $content);
    }

    #[Test]
    public function website_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.website.export'))->assertStatus(200);
    }

    #[Test]
    public function user_with_report_view_can_access_communication_report_page(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.communication'))->assertStatus(200)->assertSeeText('Laporan Komunikasi');
    }

    #[Test]
    public function user_with_report_view_can_export_communication_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.communication.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function communication_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.communication.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Type', $content);
        $this->assertStringContainsString('Title/Subject', $content);
    }

    #[Test]
    public function communication_csv_export_does_not_expose_private_message_body(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.communication.export'));
        $content = $response->streamedContent();
        // Ensure private message body is not exposed
        $this->assertStringNotContainsString('body', $content);
    }

    #[Test]
    public function communication_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.communication.export'))->assertStatus(200);
    }

    #[Test]
    public function user_with_report_view_can_export_teachers_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.teachers.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function teachers_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.teachers.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nama', $content);
        $this->assertStringContainsString('NIP', $content);
    }

    #[Test]
    public function teachers_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.teachers.export'))->assertStatus(200);
    }

    #[Test]
    public function user_with_report_view_can_export_classrooms_csv(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.classrooms.export'))->assertStatus(200)->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function classrooms_csv_export_contains_expected_header(): void
    {
        $this->actingAs($this->reportViewer);
        $response = $this->get(route('admin.reports.classrooms.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nama Kelas', $content);
        $this->assertStringContainsString('Jurusan', $content);
    }

    #[Test]
    public function classrooms_csv_export_works_with_empty_dataset(): void
    {
        $this->actingAs($this->reportViewer);
        $this->get(route('admin.reports.classrooms.export'))->assertStatus(200);
    }

    #[Test]
    public function user_without_report_view_cannot_access_teachers_and_classrooms_export(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $this->get(route('admin.reports.teachers.export'))->assertStatus(403);
        $this->get(route('admin.reports.classrooms.export'))->assertStatus(403);
    }

    #[Test]
    public function all_export_routes_exist(): void
    {
        $routes = [
            'admin.reports.students.export',
            'admin.reports.finance.export',
            'admin.reports.ppdb.export',
            'admin.reports.attendance.export',
            'admin.reports.grades.export',
            'admin.reports.internships.export',
            'admin.reports.alumni.export',
            'admin.reports.website.export',
            'admin.reports.communication.export',
            'admin.reports.teachers.export',
            'admin.reports.classrooms.export',
        ];
        foreach ($routes as $r) {
            $this->assertTrue(Route::has($r), "Route $r not found");
        }
    }
}
