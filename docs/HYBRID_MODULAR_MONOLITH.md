# LavaSMSID Hybrid Modular Monolith Architecture

Dokumen ini menetapkan arah arsitektur resmi LavaSMSID menjadi **Hybrid Modular Monolith berbasis Laravel**.

> Catatan istilah: jika sebelumnya tertulis `Hybrid Modular Architecture`, maka arah final yang dipakai adalah `Hybrid Modular Monolith`.

---

## 1. Definisi

Hybrid Modular Monolith berarti seluruh aplikasi tetap berada dalam **satu project Laravel**, tetapi fitur dipisahkan secara modular berdasarkan domain bisnis.

Laravel menjadi core utama untuk:

1. Website publik sekolah.
2. Admin panel profesional.
3. Dashboard multi role.
4. API internal.
5. Queue, cache, notification, report, dan file upload.
6. Integrasi masa depan untuk mobile app atau frontend eksternal.

Aplikasi tidak dipisah menjadi microservice dan tidak dipisah menjadi frontend/backend terpisah pada tahap awal.

---

## 2. Alasan Memakai Hybrid Modular Monolith

Arsitektur ini dipilih karena LavaSMSID memiliki banyak modul sekolah tetapi tetap perlu mudah di-deploy ke VPS.

Keunggulan:

- Deploy lebih sederhana karena hanya satu aplikasi Laravel.
- Cocok untuk VPS sekolah atau VPS budget.
- Lebih mudah debug dibanding microservice.
- Struktur tetap rapi karena dipisah per domain module.
- UI publik, admin panel, dan API internal tetap dalam satu source of truth.
- Bisa di-upgrade ke API-first atau frontend Next.js di masa depan tanpa rewrite total.
- Cocok untuk modul besar seperti siswa, guru, absensi, nilai, keuangan, PPDB, PKL, BKK, alumni, dan laporan.

---

## 3. Prinsip Utama

1. Laravel adalah core tunggal aplikasi.
2. Blade + TailwindCSS digunakan untuk website publik dan admin panel.
3. API internal memakai Sanctum untuk fitur dinamis dan integrasi masa depan.
4. Setiap domain besar menjadi module sendiri.
5. Controller harus tipis.
6. Business logic wajib berada di Service Layer atau Action Class.
7. Query kompleks diletakkan di Repository Layer.
8. Validasi wajib memakai Form Request.
9. Authorization wajib memakai Policy, Gate, Middleware, Role, dan Permission.
10. Shared logic global diletakkan di `app/Core` atau `app/Support`.
11. Data penting memakai soft delete dan audit log.
12. Laporan besar dapat memakai queue.

---

## 4. Struktur Folder Target

```text
app/
├── Core/
│   ├── Auth/
│   ├── Dashboard/
│   ├── Settings/
│   ├── Audit/
│   ├── Notification/
│   └── Shared/
│       ├── BaseService.php
│       ├── BaseRepository.php
│       └── DataTableQuery.php
│
├── Modules/
│   ├── Academic/
│   ├── Student/
│   ├── Teacher/
│   ├── Staff/
│   ├── Attendance/
│   ├── Grade/
│   ├── Finance/
│   ├── PPDB/
│   ├── Schedule/
│   ├── Internship/
│   ├── IndustryPartner/
│   ├── Alumni/
│   ├── BKK/
│   ├── Website/
│   ├── Communication/
│   ├── Report/
│   └── UserManagement/
│
├── Http/
├── Models/
├── Providers/
└── Support/
```

---

## 5. Struktur Standar Setiap Module

Setiap module wajib mengikuti pola berikut:

```text
app/Modules/Student/
├── Controllers/
│   └── StudentController.php
├── Models/
│   └── Student.php
├── Services/
│   └── StudentService.php
├── Repositories/
│   └── StudentRepository.php
├── Requests/
│   ├── StoreStudentRequest.php
│   └── UpdateStudentRequest.php
├── Policies/
│   └── StudentPolicy.php
├── Actions/
│   ├── CreateStudentAction.php
│   └── UpdateStudentAction.php
├── Data/
│   └── StudentData.php
├── Exports/
├── Imports/
├── Resources/
└── routes.php
```

Tidak semua folder harus berisi banyak file sejak awal, tetapi struktur ini menjadi standar pengembangan.

---

## 6. Layer dan Tanggung Jawab

### Controller

Tugas controller hanya:

- Menerima request.
- Memanggil Form Request.
- Memanggil Service atau Action.
- Mengembalikan view, redirect, JSON response, atau file export.

Controller tidak boleh berisi business logic panjang.

### Form Request

Tugas Form Request:

- Validasi input.
- Authorization ringan berbasis policy/permission.
- Normalisasi input sederhana.

### Service Layer

Tugas Service:

- Business logic utama.
- Orkestrasi beberapa repository/model.
- Transaksi database.
- Pemanggilan queue/event/notification.

### Repository Layer

Tugas Repository:

- Query kompleks.
- Search, filter, sorting, pagination.
- Query report.

### Action Class

Tugas Action:

- Satu use-case spesifik.
- Contoh: `ConvertPpdbToStudentAction`, `GenerateInvoiceAction`, `CalculateFinalGradeAction`.

### Policy

Tugas Policy:

- Melindungi data sensitif.
- Mencegah akses lintas role.
- Memastikan siswa/orang tua hanya melihat data miliknya.

---

## 7. Route Strategy

Route dipisah menjadi:

```text
routes/web.php       -> entry utama web
routes/api.php       -> API internal
routes/auth.php      -> login/logout
routes/admin.php     -> admin panel global
routes/public.php    -> website publik global
```

Module juga boleh memiliki `routes.php` sendiri, lalu di-load oleh `ModuleRouteServiceProvider`.

Contoh module route:

```php
Route::middleware(['web', 'auth', 'permission:student.view'])
    ->prefix('admin/students')
    ->name('admin.students.')
    ->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
    });
```

---

## 8. UI Strategy

Frontend tetap dalam satu Laravel app:

- Website publik: Blade + TailwindCSS + Alpine.js.
- Admin panel: Blade + TailwindCSS + Alpine.js + Chart.js.
- API internal: dipakai untuk chart, notification, dynamic select, upload progress, dan integrasi masa depan.

Admin panel harus:

- Solid enterprise dashboard.
- Tidak terlalu glassmorphism.
- Responsive mobile-first.
- Sidebar collapsible.
- Menu berdasarkan permission.
- Breadcrumb.
- Table, form, card, badge, modal, toast reusable.

---

## 9. Security Strategy

Wajib diterapkan:

- Authentication.
- Spatie role-permission.
- Policy untuk data siswa, nilai, absensi, pembayaran, PPDB, dan file.
- CSRF protection.
- Rate limit login.
- Secure upload validation.
- Audit log aksi penting.
- Soft delete data penting.
- Query via Eloquent/Query Builder.
- Tidak membuka data lintas role.
- Production error handling.

---

## 10. Database Strategy

Database tetap satu database utama, tetapi tabel dipisah berdasarkan domain.

Contoh domain:

- Identity: users, roles, permissions.
- Master: school_profiles, departments, competencies, classrooms.
- People: students, teachers, staffs, guardians.
- Academic: subjects, schedules, academic_years, semesters.
- Attendance: attendances.
- Grade: grades, report_cards.
- Finance: invoices, payments, expenses.
- PPDB: ppdb_registrations, ppdb_documents.
- SMK: industry_partners, internships, internship_logs, internship_scores.
- BKK: alumni, job_vacancies, job_applications.
- Communication: announcements, messages, notifications.
- Report/Audit: audit_logs.

---

## 11. Upgrade Path Masa Depan

Hybrid Modular Monolith tetap bisa berkembang menjadi:

1. API-first Laravel untuk mobile app.
2. Frontend Next.js hanya untuk public website bila diperlukan.
3. Queue worker terpisah untuk report besar.
4. Redis untuk cache, queue, dan session.
5. Read replica database untuk laporan besar.
6. Microservice kecil hanya jika beban sudah sangat besar.

Namun tahap awal tetap satu Laravel app agar stabil dan mudah dikelola.

---

## 12. Prompt untuk AI CLI

Gunakan prompt ini untuk melanjutkan repo:

```text
Ubah dan rapikan arsitektur LavaSMSID menjadi Hybrid Modular Monolith berbasis Laravel.

Laravel harus menjadi core tunggal aplikasi yang menangani website publik, admin panel, dashboard multi role, API internal, queue, cache, report, dan file upload.

Jangan pisahkan frontend ke project lain. Gunakan Blade, TailwindCSS, Vite, Alpine.js, dan Chart.js.

Pisahkan fitur berdasarkan domain module di app/Modules. Tambahkan app/Core untuk Auth, Dashboard, Settings, Audit, Notification, dan Shared base class.

Setiap module wajib mengikuti struktur Controllers, Models, Services, Repositories, Requests, Policies, Actions, Data, Resources, routes.php.

Business logic tidak boleh berada di controller. Controller hanya menerima request, memanggil service/action, lalu mengembalikan response.

Gunakan Spatie Laravel Permission untuk RBAC. Semua route admin wajib memakai auth dan permission middleware. Tambahkan policy untuk data sensitif.

Pastikan project tetap bisa menjalankan composer install, npm install, php artisan route:list, php artisan migrate:fresh --seed, npm run build, dan php artisan test.
```
