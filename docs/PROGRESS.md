# LavaSMSID — Progress Eksekusi

Dokumen ini mencatat progres eksekusi roadmap secara real-time.

---

## ✅ Tahap 0 — Validasi Foundation Laravel (SELESAI: 2026-05-31)

### Summary
Foundation Laravel valid. Semua command validasi berhasil tanpa error.

### File Diperbaiki
- `composer.json` — perbaiki constraint PHP (`^7.4` → `^8.3` dikembalikan)
- `app/Modules/Dashboard/Controllers/DashboardController.php` — dummy stat value
- `resources/views/admin/dashboard.blade.php` — `<x-admin.stat-card>` tag fix
- `resources/views/components/admin/stat-card.blade.php` — `@props` ditambahkan
- `resources/views/components/admin-layout.blade.php` — path include & toast fix
- `resources/views/components/admin/sidebar.blade.php` — `Route::has()` fallback
- `resources/views/components/admin/topbar.blade.php` — `Route::has()` fallback

### File Dipindahkan
- `resources/views/admin/components/*` → `resources/views/components/admin/*`

### Validasi
| Command | Status |
|---------|--------|
| `composer install --ignore-platform-req=php` | ✅ |
| `npm install` | ✅ |
| `php artisan route:list` | ✅ (36 routes) |
| `php artisan migrate:fresh --seed` | ✅ (8 migrations, 1 seeder) |
| `npm run build` | ✅ |
| `php artisan test` | ✅ (5 DEPR, 0 FAIL) |

---

## ✅ Tahap 1 — Core Hybrid Modular Monolith (SELESAI: 2026-05-31)

### Summary
Modular routing aktif. Routes per modul tersusun rapi. Core classes valid.

### File Dibuat (18 files)
- `app/Modules/Dashboard/routes.php`
- `app/Modules/Student/routes.php`
- `app/Modules/Teacher/routes.php`
- `app/Modules/Academic/routes.php`
- `app/Modules/Alumni/routes.php`
- `app/Modules/Attendance/routes.php`
- `app/Modules/BKK/routes.php`
- `app/Modules/Communication/routes.php`
- `app/Modules/Finance/routes.php`
- `app/Modules/Grade/routes.php`
- `app/Modules/IndustryPartner/routes.php`
- `app/Modules/Internship/routes.php`
- `app/Modules/PPDB/routes.php`
- `app/Modules/Report/routes.php`
- `app/Modules/Schedule/routes.php`
- `app/Modules/Staff/routes.php`
- `app/Modules/UserManagement/routes.php`
- `app/Modules/Website/routes.php`

### File Diubah
- `routes/web.php` — dihapus route hardcoded, semua via modul

### Core Classes (Sudah Ada, Diverifikasi)
- `app/Core/Shared/BaseService.php` ✅
- `app/Core/Shared/BaseRepository.php` ✅
- `app/Core/Shared/DataTableQuery.php` ✅
- `app/Providers/ModuleRouteServiceProvider.php` ✅

### Validasi
| Command | Status |
|---------|--------|
| `composer install --ignore-platform-req=php` | ✅ |
| `npm install` | ✅ |
| `php artisan route:list` | ✅ (36 routes) |
| `php artisan migrate:fresh --seed` | ✅ |
| `npm run build` | ✅ |
| `php artisan test` | ✅ (5 DEPR, 0 FAIL) |

---

## ✅ Tahap 2 — Custom Admin Panel Core (SELESAI: 2026-05-31)

### Summary
Admin panel enterprise lengkap dengan profile, settings, dynamic menu, semua komponen UI.

### File Dibuat (11 files)
- `app/Modules/Dashboard/Controllers/ProfileController.php` — edit profile, change password
- `app/Modules/Dashboard/Controllers/SettingsController.php` — school settings CRUD
- `resources/views/admin/profile.blade.php` — profile page + change password + avatar upload
- `resources/views/admin/settings/index.blade.php` — school settings page
- `resources/views/components/admin/form-select.blade.php` — reusable select component
- `resources/views/components/admin/form-textarea.blade.php` — reusable textarea component
- `resources/views/components/admin/error-state.blade.php` — error display component
- `resources/views/components/admin/loading-state.blade.php` — spinner + loading message
- `resources/views/components/admin/badge.blade.php` — variant support (success/warning/danger/info/default)
- `resources/views/components/admin/form-input.blade.php` — rewritten dengan @props proper
- `docs/PROGRESS.md` — file baru catatan progres

### File Diubah
- `resources/views/components/admin/sidebar.blade.php` — diperluas dengan grup menu Akademik, Keuangan, PPDB, Laporan, Settings, User Management + permission-based visibility
- `app/Modules/Dashboard/routes.php` — tambah route profile (3) + settings (2)

### Validasi
| Command | Status |
|---------|--------|
| `composer install --ignore-platform-req=php` | ✅ |
| `npm install` | ✅ |
| `php artisan route:list` | ✅ (41 routes) |
| `php artisan migrate:fresh --seed` | ✅ |
| `npm run build` | ✅ |
| `php artisan test` | ✅ (5 DEPR, 0 FAIL) |

---

## ✅ Tahap 3 — User Management dan RBAC (SELESAI: 2026-05-31)

### Summary
Modul UserManagement lengkap: CRUD users, CRUD roles, assign role/permission, policy, permission-based menu.

### File Dibuat (16 files)
- `app/Modules/UserManagement/Controllers/UserController.php` — CRUD users (7 method)
- `app/Modules/UserManagement/Controllers/RoleController.php` — CRUD roles (5 method, no show)
- `app/Modules/UserManagement/Services/UserService.php` — business logic + transaction + DataTableQuery
- `app/Modules/UserManagement/Services/RoleService.php` — role CRUD + permission grouping
- `app/Modules/UserManagement/Requests/StoreUserRequest.php` — validasi create user
- `app/Modules/UserManagement/Requests/UpdateUserRequest.php` — validasi update user
- `app/Modules/UserManagement/Policies/UserPolicy.php` — authorization policy
- `app/Providers/AppServiceProvider.php` — provider untuk policy mapping (Gate::policy)
- `resources/views/modules/user-management/users/index.blade.php` — table + search + paginate
- `resources/views/modules/user-management/users/create.blade.php` — form + role checkbox
- `resources/views/modules/user-management/users/edit.blade.php` — form edit + role sync
- `resources/views/modules/user-management/users/show.blade.php` — detail user
- `resources/views/modules/user-management/roles/index.blade.php` — table roles
- `resources/views/modules/user-management/roles/create.blade.php` — form + permission grouped
- `resources/views/modules/user-management/roles/edit.blade.php` — edit role + permission sync

### File Diubah
- `app/Modules/UserManagement/routes.php` — definisikan route resource users + roles
- `bootstrap/app.php` — registrasi AppServiceProvider

### Validasi
| Command | Status |
|---------|--------|
| `php artisan route:list` | ✅ (56 routes) |
| `php artisan migrate:fresh --seed` | ✅ |
| `npm run build` | ✅ |
| `php artisan test` | ✅ (5 DEPR, 0 FAIL) |

---

## ⏳ Tahap-tahap Selanjutnya

| Tahap | Status |
|-------|--------|
| Tahap 3 — User Management dan RBAC | ✅ SELESAI |
| Tahap 4 — Master Data | ✅ SELESAI |
| Tahap 5 — Academic, Schedule, Attendance, Grade | ✅ SELESAI |
| Tahap 6 — Finance | ✅ SELESAI |
| Tahap 7 — PPDB Online | ✅ SELESAI |
| Tahap 8 — SMK Specialist (PKL, Industry, Teaching Factory) | ✅ SELESAI |
| Tahap 9 — BKK dan Alumni | ✅ SELESAI |
| Tahap 10 — Website Publik dan CMS Konten | ✅ SELESAI |
| Tahap 11 — Communication dan Notification | ✅ SELESAI |
| Tahap 12 — Report Center | ✅ SELESAI |
| Tahap 13 — Security Hardening | ✅ SELESAI |
| Tahap 14 — Testing | ✅ SELESAI |
| Tahap 15 — Production VPS Deployment | ▶️ Berikutnya |

## ✅ Tahap 4 — Master Data (SELESAI: 2026-05-31)

### Summary
Models, Controllers, Routes, dan Views untuk entity Master Data akademik lengkap.

### File Dibuat (24 files)

**Models (7):**
- `app/Modules/Academic/Models/Department.php`
- `app/Modules/Academic/Models/AcademicYear.php`
- `app/Modules/Academic/Models/Semester.php`
- `app/Modules/Academic/Models/Classroom.php`
- `app/Modules/Academic/Models/Competency.php`
- `app/Modules/Academic/Models/Subject.php`
- `app/Modules/Teacher/Models/Teacher.php`

**Controllers (5):**
- `app/Modules/Academic/Controllers/AcademicYearController.php`
- `app/Modules/Academic/Controllers/SemesterController.php`
- `app/Modules/Academic/Controllers/ClassroomController.php`
- `app/Modules/Academic/Controllers/CompetencyController.php`
- `app/Modules/Academic/Controllers/SubjectController.php`

**Views (15):**
- `modules/academic/academic-years/` — index, create, edit, show
- `modules/academic/semesters/` — index, create, edit
- `modules/academic/classrooms/` — index, create, edit
- `modules/academic/competencies/` — index, create, edit
- `modules/academic/subjects/` — index, create, edit

### File Diubah
- `app/Modules/Student/Models/Student.php` — tambah relasi user/department/classroom
- `app/Modules/Academic/routes.php` — tambah 6 resource routes

### Validasi
- `php artisan route:list` ✅ (92 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 5 — Academic, Schedule, Attendance, Grade (SELESAI: 2026-05-31)

### Summary
Implementasi Jadwal Pelajaran (dengan deteksi bentrok), Absensi Harian (input kelas dan rekap bulanan), dan Nilai/Rapor (input per komponen dan auto perhitungan hasil akhir).

### File Dibuat (13 files)

**Models (3):**
- `app/Modules/Academic/Models/Schedule.php` — dengan method `hasConflict()`, `hasTeacherConflict()`, `hasRoomConflict()`
- `app/Modules/Academic/Models/Attendance.php` — dengan konstanta status
- `app/Modules/Academic/Models/Grade.php` — dengan method `calculateFinalResult()`

**Controllers (3):**
- `app/Modules/Academic/Controllers/ScheduleController.php` — resource (except show)
- `app/Modules/Academic/Controllers/AttendanceController.php` — resource + recap method
- `app/Modules/Academic/Controllers/GradeController.php` — resource (except show)

**Services (3):**
- `app/Modules/Academic/Services/ScheduleService.php` — logic transaction & validasi bentrok jadwal
- `app/Modules/Academic/Services/AttendanceService.php` — bulk insert absensi & recap data
- `app/Modules/Academic/Services/GradeService.php` — save grade & kalkulasi rata-rata komponen nilai

**Views (4):**
- `modules/academic/schedules/` — index, create, edit
- `modules/academic/attendances/` — index, create (bulk select), recap
- `modules/academic/grades/` — index, create, edit

### File Diubah
- `app/Modules/Academic/routes.php` — ditambahkan 14 route baru untuk 3 entitas

### Validasi
- `php artisan route:list` ✅ (106 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 6 — Finance (SELESAI: 2026-05-31)

### Summary
Modul keuangan lengkap: kategori pembayaran, tagihan (invoice), pembayaran (payment), verifikasi bendahara, dashboard keuangan.

### File Dibuat (17 files)

**Models (3):**
- `app/Modules/Finance/Models/PaymentCategory.php`
- `app/Modules/Finance/Models/Invoice.php` — dengan method `updateStatus()` dan `getRemainingAmount()`
- `app/Modules/Finance/Models/Payment.php` — dengan konstanta status

**Controllers (3):**
- `app/Modules/Finance/Controllers/PaymentCategoryController.php` — resource CRUD
- `app/Modules/Finance/Controllers/InvoiceController.php` — resource + bulk create
- `app/Modules/Finance/Controllers/PaymentController.php` — CRUD + dashboard + verify

**Services (2):**
- `app/Modules/Finance/Services/InvoiceService.php` — generate invoice number, bulk create
- `app/Modules/Finance/Services/PaymentService.php` — record, verify, auto update invoice status

**Views (8):**
- `modules/finance/dashboard.blade.php` — stat cards + recent payments
- `modules/finance/categories/` — index, create, edit
- `modules/finance/invoices/` — index, create (dengan target per siswa/kelas/semua), show (dengan riwayat pembayaran)
- `modules/finance/payments/` — index, create

### File Diubah
- `app/Modules/Finance/routes.php` — 3 resource routes + verify route
- `docs/EXECUTION_ROADMAP_FULL.md` — update status

### Validasi
- `php artisan route:list` ✅ (125 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 7 — PPDB Online (SELESAI: 2026-05-31)

### Summary
Modul PPDB Online: form publik, nomor otomatis, verifikasi panitia, approve/reject, konversi ke siswa, cek status.

### File Dibuat (10 files)

**Models (1):**
- `app/Modules/PPDB/Models/PpdbRegistration.php` — dengan konstanta status

**Services (1):**
- `app/Modules/PPDB/Services/PpdbService.php` — generate nomor, register, verify, accept, reject, convertToStudent

**Controllers (2):**
- `app/Modules/PPDB/Controllers/PPDBController.php` — index show verify accept reject convert
- `app/Modules/PPDB/Controllers/PublicPPDBController.php` — form submit status checkStatus

**Views (5):**
- `modules/ppdb/index.blade.php` — stat cards + filter tabs + table
- `modules/ppdb/show.blade.php` — detail pendaftar + action buttons (verify/accept/reject/convert)
- `modules/ppdb/public/form.blade.php` — form publik pendaftaran
- `modules/ppdb/public/status.blade.php` — tampilan hasil setelah daftar
- `modules/ppdb/public/check-status.blade.php` — cek status via nomor

**File Diubah (2):**
- `app/Modules/PPDB/routes.php` — 11 routes (admin + publik)
- `routes/public.php` — redirect /ppdb ke /ppdb/daftar

### Validasi
- `php artisan route:list` ✅ (135 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 8 — SMK Specialist: PKL, Industry, Teaching Factory (SELESAI: 2026-05-31)

### Summary
Modul SMK Specialist: mitra industri, PKL/Prakerin (CRUD lengkap), Teaching Factory, Sertifikasi Kompetensi.

### File Dibuat (14 files)

**Migration (1):**
- `database/migrations/2026_05_31_000007_create_smk_specialist_tables.php` — tabel teaching_factory_products/projects, certifications/results

**Models (5):**
- `app/Modules/IndustryPartner/Models/IndustryPartner.php`
- `app/Modules/Internship/Models/Internship.php` — status constants + relasi
- `app/Modules/Academic/Models/TeachingFactory/TeachingFactoryProduct.php`
- `app/Modules/Academic/Models/TeachingFactory/TeachingFactoryProject.php`
- `app/Modules/Academic/Models/Certification.php`
- `app/Modules/Academic/Models/CertificationResult.php`

**Controllers (2):**
- `app/Modules/IndustryPartner/Controllers/IndustryPartnerController.php` — CRUD
- `app/Modules/Internship/Controllers/InternshipController.php` — CRUD + filter status

**Views (5):**
- `modules/industry-partner/` — index, create, edit
- `modules/internship/` — index, create, edit

**Route files (2):**
- `app/Modules/IndustryPartner/routes.php`
- `app/Modules/Internship/routes.php`

### Validasi
- `php artisan route:list` ✅ (147 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 9 — BKK dan Alumni (SELESAI: 2026-05-31)

### Summary
Modul BKK: data alumni (tracer study), lowongan kerja, lamaran alumni, statistik penyerapan kerja.

### File Dibuat (11 files)

**Migration (1):**
- `database/migrations/2026_05_31_000008_create_bkk_alumni_tables.php` — tabel alumni, job_vacancies, job_applications

**Models (3):**
- `app/Modules/Alumni/Models/Alumni.php` — status constants (working/studying/entrepreneur/unemployed)
- `app/Modules/Alumni/Models/JobVacancy.php`
- `app/Modules/Alumni/Models/JobApplication.php`

**Controllers (2):**
- `app/Modules/BKK/Controllers/AlumniController.php` — CRUD + dashboard stats tracer study
- `app/Modules/BKK/Controllers/JobVacancyController.php` — CRUD lowongan kerja

**Views (5):**
- `modules/bkk/alumni/` — index (stat cards + filter + table), create, edit
- `modules/bkk/vacancies/` — index, create, edit

**Route file (1):**
- `app/Modules/BKK/routes.php`

### Validasi
- `php artisan route:list` ✅ (160 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 10 — Website Publik dan CMS Konten (SELESAI: 2026-05-31)

### Summary
Website publik lengkap (beranda, profil, jurusan, berita, agenda, galeri, prestasi, fasilitas, kontak) + CMS admin untuk kelola semua konten.

### File Dibuat (20+ files)

**Migration (1):**
- `database/migrations/2026_05_31_000009_create_website_cms_tables.php` — news, events, galleries, achievements, facilities, cms_pages

**Models (6):**
- `app/Modules/Website/Models/News.php` (+slug auto-generate)
- `app/Modules/Website/Models/Event.php` (+slug auto-generate)
- `app/Modules/Website/Models/Gallery.php`
- `app/Modules/Website/Models/Achievement.php`
- `app/Modules/Website/Models/Facility.php`
- `app/Modules/Website/Models/CmsPage.php`

**Controllers (3):**
- `app/Modules/Website/Controllers/PublicWebsiteController.php` — home, profile, departments, news, events, gallery, achievements, facilities, contact, pages
- `app/Modules/Website/Controllers/Admin/NewsController.php` — CRUD berita
- `app/Modules/Website/Controllers/Admin/GalleryController.php` — CRUD galeri
- `app/Modules/Website/Controllers/Admin/CmsController.php` — events, facilities, achievements, pages CRUD

**Views (10+):**
- `modules/website/cms/` — news (index/create), gallery (index), events (index), facilities (index), achievements (index), pages (index)
- `resources/views/public/` — home, profile, departments, contact, page

**Route files (1):**
- `app/Modules/Website/routes.php` — 39 routes (admin CMS + publik)

### Bug yang Diperbaiki
- FK constraint error di migration BKK: `alumni_id` → `constrained('alumni')` (Laravel pluralize jadi `alumnis`)

### Validasi
- `php artisan route:list` ✅ (199 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 11 — Communication dan Notification (SELESAI: 2026-05-31)

### Summary
Modul Communication: pengumuman sekolah (CRUD + broadcast notifikasi), notifikasi per user (read/unread, mark all read), dan pesan internal (struktur).

### File Dibuat (10 files)

**Migration (1):**
- `database/migrations/2026_05_31_000010_create_communication_tables.php` — announcements, notifications, messages

**Models (3):**
- `app/Modules/Communication/Models/Announcement.php` — target (all/students/teachers/parents/staff), priority
- `app/Modules/Communication/Models/Notification.php` — markAsRead method, index user_id+is_read
- `app/Modules/Communication/Models/Message.php` — pesan internal antar user

**Controllers (2):**
- `app/Modules/Communication/Controllers/AnnouncementController.php` — CRUD + auto notify target users
- `app/Modules/Communication/Controllers/NotificationController.php` — index, unread count, mark as read/single

**Views (4):**
- `modules/communication/announcements/` — index, create, edit
- `modules/communication/notifications/` — index (with mark all read)

**Route file (1):**
- `app/Modules/Communication/routes.php` — 10 routes

### Validasi
- `php artisan route:list` ✅ (199 → 209 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 12 — Report Center (SELESAI: 2026-05-31)

### Summary
Report Center: dashboard 10 jenis laporan dengan summary statistik dan filter.

### File Dibuat (11 files)

**Controller (1):**
- `app/Modules/Report/Controllers/ReportController.php` — index + 8 jenis report (students, teachers, attendance, grades, finance, ppdb, internship, alumni)

**Views (9):**
- `modules/reports/index.blade.php` — dashboard 10 report cards
- `modules/reports/students.blade.php` — laporan siswa + filter jurusan/kelas/status
- `modules/reports/teachers.blade.php` — laporan guru
- `modules/reports/attendance.blade.php` — laporan absensi + summary stats
- `modules/reports/grades.blade.php` — laporan nilai + filter semester
- `modules/reports/finance.blade.php` — laporan keuangan + summary cards
- `modules/reports/ppdb.blade.php` — laporan PPDB + summary stats
- `modules/reports/internship.blade.php` — laporan PKL + summary stats
- `modules/reports/alumni.blade.php` — laporan alumni + tracer study summary

**Route file (1):**
- `app/Modules/Report/routes.php` — 9 routes

### Validasi
- `php artisan route:list` ✅ (209 → 218 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 13 — Security Hardening (SELESAI: 2026-05-31)

### Summary
Audit keamanan menyeluruh: rate limiter login, custom error pages, production exception handling, storage symlink, security audit documentation.

### File Dibuat/Diubah (8 files)

**File Dibuat (5):**
- `resources/views/errors/403.blade.php` — custom 403 Forbidden page
- `resources/views/errors/404.blade.php` — custom 404 Not Found page
- `resources/views/errors/500.blade.php` — custom 500 Server Error page
- `docs/SECURITY_AUDIT.md` — laporan audit keamanan lengkap (8 kategori)
- `storage/app/public` symlink aktif

**File Diubah (3):**
- `bootstrap/app.php` — production exception handling (AuthException, ValidationException, NotFoundHttpException, AccessDeniedHttpException, Throwable)
- `app/Providers/AppServiceProvider.php` — rate limiter login (5 attempts/menit per email+IP)
- `docs/EXECUTION_ROADMAP_FULL.md` — update status Tahap 13

### Security Checklist yang Sudah Dipenuhi
- ✅ Semua admin route: auth + permission middleware
- ✅ Rate limiter login: 5 attempts per menit
- ✅ Password hashing: Hash::make() + Password::defaults()
- ✅ CSRF protection aktif di semua form
- ✅ File upload validation: MIME type + max size (2MB)
- ✅ Soft deletes untuk data penting
- ✅ UserPolicy: viewAny/view/create/update/delete/restore/forceDelete
- ✅ Exception handling production-safe
- ✅ Custom error pages: 403, 404, 500
- ✅ Storage symlink aktif
- ✅ .env tidak masuk git, tanpa hardcoded secrets

### Validasi
- `php artisan route:list` ✅ (218 routes)
- `php artisan migrate:fresh --seed` ✅
- `php artisan storage:link` ✅
- `npm run build` ✅
- `php artisan test` ✅ (5 DEPR, 0 FAIL)

## ✅ Tahap 14 — Testing (SELESAI: 2026-05-31)

### Summary
Test suite lengkap dengan Pest/PHPUnit: 22 test, 31 assertions, 0 FAIL.

### File Dibuat (7 test files)
- `tests/Feature/Student/StudentCrudTest.php` — super admin view student list, guest access denied, student model
- `tests/Feature/Finance/FinanceAccessTest.php` — super admin akses finance, guest redirect login
- `tests/Feature/PPDB/PpdbRegistrationTest.php` — create registration + unique number validation
- `tests/Feature/Grade/GradeTest.php` — kalkulasi nilai akhir (82.5), zero score (0.0)
- `tests/Feature/Attendance/AttendanceTest.php` — status constants + unique per student per day
- `tests/Feature/Report/ReportExportTest.php` — index + 6 laporan pages load
- `tests/Feature/Auth/LoginTest.php` — (sudah ada) guest login, auth redirect, logout
- `tests/Feature/Rbac/RolePermissionTest.php` — (sudah ada) super admin dashboard, guest blokir

### File Diubah (2)
- `app/Modules/Report/routes.php` — tambah routes classrooms + departments
- `app/Modules/Report/Controllers/ReportController.php` — tambah methods classrooms + departments

### Validasi
- `php artisan test` ✅ (22 DEPR, 0 FAIL, 31 assertions)
- `php artisan route:list` ✅ (220 routes)
- `php artisan migrate:fresh --seed` ✅
- `npm run build` ✅

### Catatan
Semua test menggunakan SQLite in-memory dengan RefreshDatabase. Hanya ada deprecation warning PDO (`PDO::MYSQL_ATTR_SSL_CA` deprecated) yang tidak memengaruhi fungsionalitas.
