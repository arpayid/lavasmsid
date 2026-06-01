<?php

use App\Models\User;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Attendance;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Grade;
use App\Modules\Academic\Models\Schedule;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\Subject;
use App\Modules\Student\Models\Student;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $perms = ['schedule.view', 'schedule.create', 'schedule.update', 'schedule.delete',
        'attendance.view', 'attendance.create', 'attendance.update', 'attendance.delete', 'attendance.export',
        'grade.view', 'grade.create', 'grade.update', 'grade.delete', 'grade.export',
        'report.view', 'report.export', 'academic.view', 'academic.create', 'academic.update', 'academic.delete'];
    foreach ($perms as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create();
    $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $adminRole->givePermissionTo(Permission::all());
    $this->admin->assignRole($adminRole);

    $this->user = User::factory()->create(['email' => 'test@test.com']);
    $this->academicYear = AcademicYear::create(['name' => '2026/2027', 'start_date' => '2026-07-01', 'end_date' => '2027-06-30', 'is_active' => true]);
    $this->semester = Semester::create(['academic_year_id' => $this->academicYear->id, 'name' => 'Ganjil', 'is_active' => true]);
});

test('guest cannot access schedules index', function () {
    $this->get(route('admin.schedules.index'))->assertRedirect(route('login'));
});

test('user without schedule.view cannot access schedules index', function () {
    $this->actingAs($this->user)->get(route('admin.schedules.index'))->assertForbidden();
});

test('user with schedule.view can access schedules index', function () {
    $this->actingAs($this->admin)->get(route('admin.schedules.index'))->assertOk();
});

test('user with schedule.create can create schedule', function () {
    $classroom = Classroom::create(['name' => 'Test Class', 'level' => 'X']);
    $subject = Subject::create(['code' => 'SCH1', 'name' => 'Schedule Test']);

    $response = $this->actingAs($this->admin)->post(route('admin.schedules.store'), [
        'classroom_id' => $classroom->id,
        'subject_id' => $subject->id,
        'academic_year_id' => $this->academicYear->id,
        'semester_id' => $this->semester->id,
        'day' => 'monday',
        'start_time' => '08:00',
        'end_time' => '09:00',
        'status' => 'active',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('schedules', ['day' => 'monday']);
});

test('schedule conflict by classroom is rejected', function () {
    $classroom = Classroom::create(['name' => 'Conflict Class', 'level' => 'X']);
    $subject1 = Subject::create(['code' => 'CF1', 'name' => 'Subject 1']);
    $subject2 = Subject::create(['code' => 'CF2', 'name' => 'Subject 2']);
    Schedule::create(['classroom_id' => $classroom->id, 'subject_id' => $subject1->id, 'day' => 'monday', 'start_time' => '08:00', 'end_time' => '09:00', 'status' => 'active']);

    $response = $this->actingAs($this->admin)->post(route('admin.schedules.store'), [
        'classroom_id' => $classroom->id, 'subject_id' => $subject2->id,
        'day' => 'monday', 'start_time' => '08:30', 'end_time' => '09:30', 'status' => 'active',
    ]);

    $response->assertSessionHasErrors();
});

test('schedule conflict by teacher is rejected', function () {
    $classroom1 = Classroom::create(['name' => 'Class A', 'level' => 'X']);
    $classroom2 = Classroom::create(['name' => 'Class B', 'level' => 'X']);
    $subject1 = Subject::create(['code' => 'TC1', 'name' => 'Subject 1']);
    $subject2 = Subject::create(['code' => 'TC2', 'name' => 'Subject 2']);
    $teacher = Teacher::create(['name' => 'Teacher T', 'status' => 'active']);
    Schedule::create(['classroom_id' => $classroom1->id, 'subject_id' => $subject1->id, 'teacher_id' => $teacher->id, 'day' => 'tuesday', 'start_time' => '10:00', 'end_time' => '11:00', 'status' => 'active']);

    $response = $this->actingAs($this->admin)->post(route('admin.schedules.store'), [
        'classroom_id' => $classroom2->id, 'subject_id' => $subject2->id, 'teacher_id' => $teacher->id,
        'day' => 'tuesday', 'start_time' => '10:30', 'end_time' => '11:30', 'status' => 'active',
    ]);

    $response->assertSessionHasErrors();
});

test('user with attendance.view can access attendances index', function () {
    $this->actingAs($this->admin)->get(route('admin.attendances.index'))->assertOk();
});

test('bulk attendance store creates and updates attendance', function () {
    $classroom = Classroom::create(['name' => 'Attendance Class', 'level' => 'X']);
    $student = Student::create(['nis' => 'A001', 'name' => 'Attendance Student', 'gender' => 'L', 'status' => 'active', 'classroom_id' => $classroom->id]);

    $payload = [
        'classroom_id' => $classroom->id,
        'attendance_date' => '2026-07-10',
        'records' => [['student_id' => $student->id, 'status' => 'present', 'note' => null]],
    ];

    $this->actingAs($this->admin)->post(route('admin.attendances.store'), $payload)->assertRedirect();
    $this->assertDatabaseHas('attendances', ['student_id' => $student->id, 'attendance_date' => '2026-07-10 00:00:00', 'status' => 'present']);

    $payload['records'][0]['status'] = 'absent';
    $this->actingAs($this->admin)->post(route('admin.attendances.store'), $payload)->assertRedirect();
    $this->assertDatabaseHas('attendances', ['student_id' => $student->id, 'attendance_date' => '2026-07-10 00:00:00', 'status' => 'absent']);
    expect(Attendance::where('student_id', $student->id)->whereDate('attendance_date', '2026-07-10')->count())->toBe(1);
});

test('attendance recap and export can be accessed', function () {
    $this->actingAs($this->admin)->get(route('admin.attendances.recap'))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.attendances.export'))->assertOk();
});

test('user with grade.view can access grades index', function () {
    $this->actingAs($this->admin)->get(route('admin.grades.index'))->assertOk();
});

test('user with grade.create can create grade', function () {
    $student = Student::create(['nis' => 'G001', 'name' => 'Grade Student', 'gender' => 'L', 'status' => 'active']);
    $subject = Subject::create(['code' => 'GR1', 'name' => 'Grade Subject']);

    $response = $this->actingAs($this->admin)->post(route('admin.grades.store'), [
        'student_id' => $student->id, 'subject_id' => $subject->id, 'semester_id' => $this->semester->id,
        'assignment_score' => 80, 'midterm_score' => 75, 'final_score' => 85, 'practice_score' => 90,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('grades', ['student_id' => $student->id]);
});

test('grade final_result, grade_letter, and predicate are calculated and update changes result', function () {
    $student = Student::create(['nis' => 'GL01', 'name' => 'Calc Student', 'gender' => 'L', 'status' => 'active']);
    $subject = Subject::create(['code' => 'GL1', 'name' => 'Calc Subject']);

    $this->actingAs($this->admin)->post(route('admin.grades.store'), [
        'student_id' => $student->id, 'subject_id' => $subject->id, 'semester_id' => $this->semester->id,
        'assignment_score' => 80, 'midterm_score' => 75, 'final_score' => 85, 'practice_score' => 90,
    ])->assertRedirect();

    $grade = Grade::where('student_id', $student->id)->first();
    expect($grade->final_result)->not->toBeNull();
    expect($grade->grade_letter)->not->toBeNull();
    expect($grade->predicate)->not->toBeNull();
    $oldResult = (float) $grade->final_result;

    $this->actingAs($this->admin)->put(route('admin.grades.update', $grade), [
        'student_id' => $student->id, 'subject_id' => $subject->id, 'semester_id' => $this->semester->id,
        'assignment_score' => 60, 'midterm_score' => 60, 'final_score' => 60, 'practice_score' => 60,
    ])->assertRedirect();

    $grade->refresh();
    expect((float) $grade->final_result)->not->toBe($oldResult);
});

test('grade bulk route stores grades', function () {
    $student = Student::create(['nis' => 'BG01', 'name' => 'Bulk Grade Student', 'gender' => 'L', 'status' => 'active']);
    $subject = Subject::create(['code' => 'BGR1', 'name' => 'Bulk Grade Subject']);

    $this->actingAs($this->admin)->get(route('admin.grades.bulk'))->assertOk();
    $this->actingAs($this->admin)->post(route('admin.grades.bulk-store'), [
        'subject_id' => $subject->id,
        'semester_id' => $this->semester->id,
        'grades' => [['student_id' => $student->id, 'assignment_score' => 90, 'midterm_score' => 90, 'final_score' => 90, 'practice_score' => 90]],
    ])->assertRedirect();

    $this->assertDatabaseHas('grades', ['student_id' => $student->id, 'subject_id' => $subject->id]);
});

test('report card index show and pdf can be accessed', function () {
    $student = Student::create(['nis' => 'R001', 'name' => 'Report Student', 'gender' => 'L', 'status' => 'active']);
    $subject = Subject::create(['code' => 'RP1', 'name' => 'Report Subject']);
    Grade::create(['student_id' => $student->id, 'subject_id' => $subject->id, 'semester_id' => $this->semester->id, 'final_result' => 85, 'grade_letter' => 'B', 'predicate' => 'Baik']);

    $this->actingAs($this->admin)->get(route('admin.report-cards.index'))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.report-cards.show', ['student_id' => $student->id, 'semester_id' => $this->semester->id]))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.report-cards.pdf', ['student_id' => $student->id, 'semester_id' => $this->semester->id]))->assertOk();
});

test('academic event index can be accessed', function () {
    $this->actingAs($this->admin)->get(route('admin.academic-events.index'))->assertOk();
});

test('academic event CRUD basic', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.academic-events.store'), [
        'title' => 'Test Event', 'start_date' => '2026-07-01', 'type' => 'event', 'is_public' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('academic_events', ['title' => 'Test Event']);
});

test('sidebar academic operation routes exist', function () {
    foreach (['admin.schedules.index', 'admin.attendances.index', 'admin.attendances.recap', 'admin.attendances.export',
        'admin.grades.index', 'admin.report-cards.index', 'admin.academic-events.index'] as $route) {
        expect(route($route))->not->toBeNull();
    }
});
