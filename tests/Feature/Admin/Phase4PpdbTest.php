<?php

use App\Models\User;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\PPDB\Models\PpdbPeriod;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\Student\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['ppdb.view', 'ppdb.verify', 'ppdb.approve', 'ppdb.convert', 'student.create'] as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());
    $this->admin->assignRole($role);

    $this->user = User::factory()->create();
    $this->academicYear = AcademicYear::create([
        'name' => '2026/2027',
        'start_date' => '2026-07-01',
        'end_date' => '2027-06-30',
        'is_active' => true,
    ]);
});

test('guest cannot access ppdb dashboard', function () {
    $this->get(route('admin.ppdb.dashboard'))->assertRedirect(route('login'));
});

test('user without ppdb view cannot access ppdb dashboard', function () {
    $this->actingAs($this->user)->get(route('admin.ppdb.dashboard'))->assertForbidden();
});

test('admin can access ppdb dashboard and registrations', function () {
    $this->actingAs($this->admin)->get(route('admin.ppdb.dashboard'))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.ppdb.registrations.index'))->assertOk();
});

test('public ppdb form requires open period for submit', function () {
    $this->post(route('public.ppdb.submit'), [
        'candidate_name' => 'Calon Siswa',
        'gender' => 'L',
    ])->assertSessionHasErrors();
});

test('public can submit registration when period is open', function () {
    PpdbPeriod::create([
        'academic_year_id' => $this->academicYear->id,
        'name' => 'Gelombang 1',
        'start_date' => '2026-07-01',
        'end_date' => '2026-08-01',
        'status' => 'open',
        'is_active' => true,
    ]);

    $this->post(route('public.ppdb.submit'), [
        'candidate_name' => 'Calon Siswa',
        'gender' => 'L',
        'phone' => '08123456789',
    ])->assertRedirect();

    $this->assertDatabaseHas('ppdb_registrations', [
        'candidate_name' => 'Calon Siswa',
        'status' => PpdbRegistration::STATUS_SUBMITTED,
    ]);
});

test('admin can verify accept reject and convert accepted applicant', function () {
    $registration = PpdbRegistration::create([
        'registration_number' => 'PPDB-TEST-0001',
        'candidate_name' => 'Accepted Student',
        'name' => 'Accepted Student',
        'gender' => 'L',
        'status' => PpdbRegistration::STATUS_SUBMITTED,
    ]);

    $this->actingAs($this->admin)->post(route('admin.ppdb.registrations.verify', $registration))->assertRedirect();
    expect($registration->fresh()->status)->toBe(PpdbRegistration::STATUS_VERIFIED);

    $this->actingAs($this->admin)->post(route('admin.ppdb.registrations.accept', $registration))->assertRedirect();
    expect($registration->fresh()->status)->toBe(PpdbRegistration::STATUS_ACCEPTED);

    $this->actingAs($this->admin)->post(route('admin.ppdb.registrations.convert', $registration))->assertRedirect();
    expect($registration->fresh()->status)->toBe(PpdbRegistration::STATUS_CONVERTED);
    expect(Student::where('name', 'Accepted Student')->exists())->toBeTrue();
});

test('rejected applicant cannot be converted', function () {
    $registration = PpdbRegistration::create([
        'registration_number' => 'PPDB-TEST-0002',
        'candidate_name' => 'Rejected Student',
        'name' => 'Rejected Student',
        'gender' => 'L',
        'status' => PpdbRegistration::STATUS_SUBMITTED,
    ]);

    $this->actingAs($this->admin)->post(route('admin.ppdb.registrations.reject', $registration), [
        'notes' => 'Berkas tidak lengkap',
    ])->assertRedirect();

    expect($registration->fresh()->status)->toBe(PpdbRegistration::STATUS_REJECTED);

    $this->actingAs($this->admin)->post(route('admin.ppdb.registrations.convert', $registration))->assertSessionHasErrors();
});

test('panitia ppdb role can convert applicants', function () {
    $committee = Role::firstOrCreate(['name' => 'Panitia PPDB', 'guard_name' => 'web']);
    $committee->givePermissionTo(['ppdb.view', 'ppdb.verify', 'ppdb.approve', 'ppdb.convert']);

    expect($committee->hasPermissionTo('ppdb.convert'))->toBeTrue();
});

test('ppdb sidebar item is visible for ppdb viewers', function () {
    $role = Role::firstOrCreate(['name' => 'Panitia PPDB', 'guard_name' => 'web']);
    $role->givePermissionTo('ppdb.view');
    $this->user->assignRole($role);

    $this->actingAs($this->user)
        ->get(route('admin.ppdb.dashboard'))
        ->assertOk()
        ->assertSee('PPDB');
});

test('phase 4 ppdb routes exist', function () {
    foreach ([
        'admin.ppdb.dashboard',
        'admin.ppdb.registrations.index',
        'admin.ppdb.registrations.show',
        'admin.ppdb.registrations.verify',
        'admin.ppdb.registrations.accept',
        'admin.ppdb.registrations.reject',
        'admin.ppdb.registrations.convert',
        'ppdb.index',
        'public.ppdb.submit',
    ] as $route) {
        expect(route($route, str_contains($route, 'registrations.') && ! str_ends_with($route, 'index') ? PpdbRegistration::create([
            'registration_number' => uniqid('PPDB-'),
            'candidate_name' => 'Route Test',
            'status' => 'submitted',
        ]) : []))->not->toBeNull();
    }
});
