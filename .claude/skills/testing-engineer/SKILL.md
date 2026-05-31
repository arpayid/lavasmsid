---
name: Laravel Testing Engineer
description: Use this skill when creating Pest or PHPUnit tests for LavaSMSID auth, RBAC, CRUD, finance access, PPDB registration, attendance, grade, report export, and role-based restrictions.
---

# Laravel Testing Engineer

Create tests for LavaSMSID using Pest or PHPUnit depending on project setup.

## Required Test Areas

- Auth
- RBAC
- User Management
- Student CRUD
- Teacher CRUD
- Academic CRUD
- Attendance
- Grade
- Finance
- PPDB
- Internship
- BKK
- Report Export
- Security Access

## Required Test Files

- tests/Feature/Auth/LoginTest.php
- tests/Feature/Rbac/RolePermissionTest.php
- tests/Feature/UserManagement/UserCrudTest.php
- tests/Feature/Student/StudentCrudTest.php
- tests/Feature/Teacher/TeacherCrudTest.php
- tests/Feature/Academic/AcademicMasterDataTest.php
- tests/Feature/Attendance/AttendanceTest.php
- tests/Feature/Grade/GradeTest.php
- tests/Feature/Finance/FinanceAccessTest.php
- tests/Feature/PPDB/PpdbRegistrationTest.php
- tests/Feature/Internship/InternshipTest.php
- tests/Feature/BKK/BkkAlumniTest.php
- tests/Feature/Report/ReportExportTest.php

## Test Rules

Every test should use factories when possible, use RefreshDatabase, test authorized access, test unauthorized access, test validation errors, test successful creation, test update, test delete when needed, and test important role restrictions.

## Access Tests

Must test:

- Guru cannot access finance.
- Siswa can only access own data.
- Orang Tua can only access child data.
- Bendahara can access finance.
- Panitia PPDB can access PPDB.
- Admin BKK can access BKK.
- Super Admin can access all.

## Required Command

```bash
php artisan test
```

## Final Rule

Do not mark a stage complete if tests fail without explaining the reason and fix.
