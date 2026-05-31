<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'report.view', 'guard_name' => 'web']);
    $this->admin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());
    $this->admin->assignRole($role);
});

test('report index page loads', fn () => $this->actingAs($this->admin)->get(route('admin.reports.index'))->assertOk());
test('student report page loads', fn () => $this->actingAs($this->admin)->get(route('admin.reports.students'))->assertOk());
test('attendance report page loads', fn () => $this->actingAs($this->admin)->get(route('admin.reports.attendance'))->assertOk());
test('grades report page loads', fn () => $this->actingAs($this->admin)->get(route('admin.reports.grades'))->assertOk());
test('finance report page loads', fn () => $this->actingAs($this->admin)->get(route('admin.reports.finance'))->assertOk());
test('alumni report page loads', fn () => $this->actingAs($this->admin)->get(route('admin.reports.alumni'))->assertOk());
