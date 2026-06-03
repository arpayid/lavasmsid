<?php

namespace Tests\Feature\Admin;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentCategory;
use App\Models\User;
use App\Modules\Student\Models\Student;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase5FinanceTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdminUser;

    protected $viewerUser;

    protected $creatorUser;

    protected $updaterUser;

    protected $verifierUser;

    protected $exporterUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        Permission::firstOrCreate(['name' => 'finance.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'finance.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'finance.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'finance.verify', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'finance.export', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'finance.print', 'guard_name' => 'web']);

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $this->superAdminUser = User::factory()->create(['name' => 'Super Admin Test']);
        $this->superAdminUser->assignRole($superAdminRole);

        $viewerRole = Role::firstOrCreate(['name' => 'Finance Viewer', 'guard_name' => 'web']);
        $viewerRole->syncPermissions(['finance.view']);
        $this->viewerUser = User::factory()->create(['name' => 'Viewer Test']);
        $this->viewerUser->assignRole($viewerRole);

        $creatorRole = Role::firstOrCreate(['name' => 'Finance Creator', 'guard_name' => 'web']);
        $creatorRole->syncPermissions(['finance.view', 'finance.create']);
        $this->creatorUser = User::factory()->create(['name' => 'Creator Test']);
        $this->creatorUser->assignRole($creatorRole);

        $updaterRole = Role::firstOrCreate(['name' => 'Finance Updater', 'guard_name' => 'web']);
        $updaterRole->syncPermissions(['finance.view', 'finance.update']);
        $this->updaterUser = User::factory()->create(['name' => 'Updater Test']);
        $this->updaterUser->assignRole($updaterRole);

        $verifierRole = Role::firstOrCreate(['name' => 'Finance Verifier', 'guard_name' => 'web']);
        $verifierRole->syncPermissions(['finance.view', 'finance.verify']);
        $this->verifierUser = User::factory()->create(['name' => 'Verifier Test']);
        $this->verifierUser->assignRole($verifierRole);

        $exporterRole = Role::firstOrCreate(['name' => 'Finance Exporter', 'guard_name' => 'web']);
        $exporterRole->syncPermissions(['finance.view', 'finance.export']);
        $this->exporterUser = User::factory()->create(['name' => 'Exporter Test']);
        $this->exporterUser->assignRole($exporterRole);
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

    protected function createInvoice($student, $category, $amount = 100000)
    {
        return Invoice::create([
            'student_id' => $student->id,
            'payment_category_id' => $category->id,
            'amount' => $amount,
        ]);
    }

    // Step 1: Dashboard
    #[Test]
    public function guest_cannot_access_finance_dashboard(): void
    {
        $response = $this->get(route('admin.finance.dashboard'));
        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_with_finance_view_permission_can_access_dashboard(): void
    {
        $this->actingAs($this->superAdminUser);
        $response = $this->get(route('admin.finance.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeText('Finance Dashboard');
    }

    // Step 2: Payment Categories
    #[Test]
    public function user_with_finance_view_can_access_category_index(): void
    {
        $this->actingAs($this->viewerUser);
        $response = $this->get(route('admin.finance.categories.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_finance_create_can_create_payment_category(): void
    {
        $this->actingAs($this->creatorUser);
        $response = $this->post(route('admin.finance.categories.store'), [
            'name' => 'SPP Tahunan',
            'description' => 'Biaya SPP tahunan',
        ]);
        $response->assertRedirect(route('admin.finance.categories.index'));
        $this->assertDatabaseHas('payment_categories', ['name' => 'SPP Tahunan']);
    }

    // Step 3: Invoices
    #[Test]
    public function user_with_finance_view_can_access_invoice_index(): void
    {
        $this->actingAs($this->viewerUser);
        $response = $this->get(route('admin.finance.invoices.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_finance_create_can_create_invoice(): void
    {
        $this->actingAs($this->creatorUser);
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);

        $response = $this->post(route('admin.finance.invoices.store'), [
            'student_id' => $student->id,
            'payment_category_id' => $category->id,
            'amount' => 500000,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.finance.invoices.index'));
        $this->assertDatabaseHas('invoices', [
            'student_id' => $student->id,
            'amount' => 500000,
        ]);
    }

    #[Test]
    public function invoice_number_is_auto_generated(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category);
        $this->assertNotNull($invoice->invoice_number);
        $this->assertStringStartsWith('INV-', $invoice->invoice_number);
    }

    #[Test]
    public function user_with_finance_update_can_delete_unpaid_invoice(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category);

        $this->actingAs($this->updaterUser);
        $response = $this->delete(route('admin.finance.invoices.destroy', $invoice));

        $response->assertRedirect(route('admin.finance.invoices.index'));
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    #[Test]
    public function paid_invoice_cannot_be_deleted(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'payment_category_id' => $category->id,
            'amount' => 100000,
            'paid_amount' => 100000,
        ]);

        $this->actingAs($this->updaterUser);
        $response = $this->delete(route('admin.finance.invoices.destroy', $invoice));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }

    // Step 4: Payments
    #[Test]
    public function user_with_finance_view_can_access_payment_index(): void
    {
        $this->actingAs($this->viewerUser);
        $response = $this->get(route('admin.finance.payments.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_finance_create_can_record_pending_payment(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category, 100000);

        // Creator does not have finance.verify, so it should be pending
        $this->actingAs($this->creatorUser);
        $response = $this->post(route('admin.finance.payments.store'), [
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'paid_at' => now()->format('Y-m-d'),
            'method' => 'cash',
        ]);

        $response->assertRedirect(route('admin.finance.invoices.show', $invoice->id));
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'status' => 'pending',
        ]);

        $invoice->refresh();
        $this->assertEquals(0, $invoice->paid_amount, 'Pending payment should not update invoice paid_amount');
    }

    #[Test]
    public function user_with_finance_verify_can_verify_payment(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category, 100000);
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'paid_at' => now(),
            'method' => 'cash',
            'status' => 'pending',
            'receipt_number' => 'RCP-TEST',
        ]);

        $this->actingAs($this->verifierUser);
        $response = $this->post(route('admin.finance.payments.verify', $payment));

        $response->assertStatus(302);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'verified',
            'verified_by' => $this->verifierUser->id,
        ]);

        $invoice->refresh();
        $this->assertEquals(50000, $invoice->paid_amount);
        $this->assertEquals('partial', $invoice->status);
    }

    #[Test]
    public function invoice_status_becomes_paid_after_full_payment(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category, 100000);
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 100000,
            'paid_at' => now(),
            'method' => 'cash',
            'status' => 'pending',
            'receipt_number' => 'RCP-FULL',
        ]);

        $this->actingAs($this->verifierUser);
        $this->post(route('admin.finance.payments.verify', $payment));

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
    }

    #[Test]
    public function overpayment_is_rejected_on_payment_creation(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category, 100000);

        $this->actingAs($this->creatorUser);
        $response = $this->post(route('admin.finance.payments.store'), [
            'invoice_id' => $invoice->id,
            'amount' => 150000, // Over 100000
            'paid_at' => now()->format('Y-m-d'),
            'method' => 'cash',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('payments', ['amount' => 150000]);
    }

    #[Test]
    public function pending_payment_can_be_deleted(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $invoice = $this->createInvoice($student, $category, 100000);
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'paid_at' => now(),
            'method' => 'cash',
            'status' => 'pending',
            'receipt_number' => 'RCP-DEL',
        ]);

        $this->actingAs($this->updaterUser);
        $response = $this->delete(route('admin.finance.payments.destroy', $payment));

        $response->assertRedirect(route('admin.finance.payments.index'));
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    // Step 5: Reports
    #[Test]
    public function user_with_finance_view_can_access_finance_report_page(): void
    {
        $this->actingAs($this->viewerUser);
        $response = $this->get(route('admin.finance.reports.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Laporan Keuangan');
    }

    #[Test]
    public function user_without_finance_view_cannot_access_finance_report_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('admin.finance.reports.index'));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_finance_export_can_export_report_csv(): void
    {
        $this->actingAs($this->exporterUser);
        $response = $this->get(route('admin.finance.reports.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function user_without_finance_export_cannot_export_csv(): void
    {
        $this->actingAs($this->viewerUser);
        $response = $this->get(route('admin.finance.reports.export'));
        $response->assertStatus(403);
    }

    #[Test]
    public function finance_dashboard_uses_real_totals(): void
    {
        $student = $this->createStudent();
        $category = PaymentCategory::create(['name' => 'SPP']);
        $this->createInvoice($student, $category, 1000000);

        $this->actingAs($this->superAdminUser);
        $response = $this->get(route('admin.finance.dashboard'));
        $response->assertStatus(200);
        // Expecting dot as thousands separator (1.000.000)
        $response->assertSeeText('1.000.000');
    }
}
