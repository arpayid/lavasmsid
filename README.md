# LavaSMSID — SMK Management System Professional

LavaSMSID adalah project **School Management System khusus SMK** berbasis Laravel dengan arsitektur **Hybrid Modular Monolith**.

Project ini dirancang untuk mengelola website publik sekolah, admin panel profesional, dashboard multi role, data siswa, guru, staff, akademik, absensi, nilai, rapor, PPDB online, keuangan, PKL, BKK, alumni, komunikasi, dan laporan dalam satu aplikasi Laravel yang rapi, aman, responsif, dan siap dikembangkan ke production VPS.

---

## Arsitektur Resmi

```text
Hybrid Modular Monolith Laravel
```

Artinya:

- Laravel menjadi satu core aplikasi utama.
- Website publik dan admin panel berada dalam satu project Laravel.
- Frontend menggunakan Blade, TailwindCSS, Vite, Alpine.js, dan Chart.js.
- API internal tetap tersedia via Laravel Sanctum.
- Fitur besar dipisahkan berdasarkan domain module di `app/Modules`.
- Fitur global diletakkan di `app/Core` atau `app/Support`.
- Tidak memakai microservice pada tahap awal.
- Tidak memisahkan frontend ke Next.js pada tahap awal.
- Tetap bisa dikembangkan ke mobile app atau frontend eksternal di masa depan.

Dokumentasi arsitektur:

```text
docs/HYBRID_MODULAR_MONOLITH.md
docs/MONOLITH_PLAN.md
docs/ARCHITECTURE.md
docs/ROADMAP_UNEXECUTED_PROMPT.md
```

---

## Tujuan Project

Membangun sistem manajemen sekolah SMK yang:

- Profesional.
- Modular.
- Mudah dikembangkan.
- Aman secara role dan permission.
- Responsif di desktop dan mobile.
- Siap deploy ke VPS production.
- Cocok untuk kebutuhan sekolah SMK modern.

---

## Stack Teknologi

- PHP 8.3+
- Laravel 12+
- MySQL atau PostgreSQL
- Laravel Blade
- TailwindCSS
- Vite
- Alpine.js
- Chart.js
- Laravel Sanctum
- Spatie Laravel Permission
- Export CSV streaming untuk laporan operasional
- PDF/Excel dapat ditambahkan sebagai integrasi opsional bila diperlukan
- Laravel Queue
- Redis optional
- Laravel Pint
- Pest/PHPUnit

---

## Modul Utama

### Core

- Auth
- Dashboard
- Settings
- Audit Log
- Notification
- Shared Service
- Shared Repository

### Website Publik

- Beranda
- Profil Sekolah
- Visi dan Misi
- Sejarah Sekolah
- Sambutan Kepala Sekolah
- Jurusan SMK
- Fasilitas
- Prestasi
- Berita
- Agenda
- Galeri
- PPDB
- Mitra Industri
- Teaching Factory
- PKL / Prakerin
- BKK
- Alumni
- Kontak
- Login Portal

### Admin Panel

- Dashboard statistik
- Sidebar profesional
- Topbar user
- Breadcrumb
- Menu berdasarkan role
- Table modern
- Form modern
- Search, filter, sorting
- Pagination
- Modal
- Toast notification
- Loading state
- Empty state
- Error state
- Badge status
- User profile
- Setting sekolah
- Activity log
- Notification dropdown

### Master Data

- Profil Sekolah
- Tahun Ajaran
- Semester
- Jurusan
- Kompetensi Keahlian
- Kelas / Rombel
- Ruang Kelas
- Mata Pelajaran
- Guru
- Staff
- Siswa
- Orang Tua / Wali
- Ekstrakurikuler
- Mitra Industri
- Kategori Pembayaran
- Kalender Akademik

### Akademik

- Jadwal Pelajaran
- Jadwal Guru
- Jadwal Kelas
- Jadwal Ruang
- Jadwal Ujian
- Deteksi bentrok jadwal
- Kalender akademik
- Kenaikan kelas
- Kelulusan siswa

### Absensi

- Absensi siswa harian
- Absensi per mata pelajaran
- Absensi guru
- Rekap harian
- Rekap bulanan
- Rekap per kelas
- Rekap per siswa
- Grafik kehadiran
- Export CSV streaming

### Nilai dan Rapor

- Nilai tugas
- Nilai harian
- Nilai UTS
- Nilai UAS
- Nilai praktik
- Nilai proyek
- Nilai produktif SMK
- Nilai PKL
- Nilai sikap
- Nilai pengetahuan
- Nilai keterampilan
- Perhitungan nilai akhir otomatis
- Rapor PDF
- Rekap nilai

### Keuangan

- Kategori pembayaran
- Tagihan SPP
- Tagihan daftar ulang
- Tagihan ujian
- Tagihan praktik
- Tagihan lain-lain
- Pembayaran siswa
- Pembayaran cicilan
- Status lunas / sebagian / belum lunas
- Kwitansi PDF
- Laporan pemasukan
- Laporan pengeluaran
- Laporan kas
- Dashboard keuangan

### PPDB Online

- Form pendaftaran publik
- Nomor pendaftaran otomatis
- Pilihan jurusan SMK
- Data calon siswa
- Data orang tua
- Upload berkas
- Verifikasi berkas
- Seleksi administrasi
- Status pendaftaran
- Pengumuman hasil
- Cetak bukti pendaftaran
- Konversi pendaftar diterima menjadi siswa aktif

### SMK Specialist

- Program Keahlian
- Kompetensi Keahlian
- Mata Pelajaran Produktif
- PKL / Prakerin
- Mitra Industri
- Guru Pembimbing PKL
- Pembimbing Industri
- Logbook PKL
- Monitoring PKL
- Penilaian PKL
- Teaching Factory
- Sertifikasi Kompetensi
- Uji Kompetensi Keahlian

### BKK dan Alumni

- Data alumni
- Tracer study
- Status alumni bekerja
- Status alumni kuliah
- Status alumni wirausaha
- Lowongan kerja
- Lamaran alumni
- Perusahaan mitra
- Statistik penyerapan alumni
- Laporan alumni

### Komunikasi

- Pengumuman sekolah
- Pesan internal
- Notifikasi dashboard
- Broadcast siswa
- Broadcast orang tua
- Broadcast guru
- Notifikasi pembayaran
- Notifikasi absensi
- Notifikasi nilai
- Riwayat notifikasi

### Report

- Laporan siswa
- Laporan guru
- Laporan kelas
- Laporan jurusan
- Laporan absensi
- Laporan nilai
- Laporan rapor
- Laporan keuangan
- Laporan PPDB
- Laporan PKL
- Laporan alumni
- Export CSV streaming

---

## Struktur Folder Target

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

## Standar Struktur Setiap Module

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

---

## Prinsip Kode

- Controller harus tipis.
- Business logic berada di Service atau Action.
- Query kompleks berada di Repository.
- Validasi input memakai Form Request.
- Authorization memakai Policy, Gate, Middleware, Role, dan Permission.
- Semua route admin wajib memakai auth dan permission.
- Semua upload file wajib validasi MIME dan ukuran.
- Data penting wajib memakai soft delete.
- Aksi penting wajib masuk audit log.
- Tidak boleh ada menu tanpa route dan view.
- Tidak boleh ada route mati.
- Seeder harus bisa dijalankan ulang.
- `npm run build` harus berhasil.
- `php artisan migrate --seed` harus berhasil.

---

## Dokumentasi Root

| Dokumen | Kegunaan |
|---|---|
| `DEPLOYMENT.md` | Panduan deployment production dan validasi pascadeploy. |
| `ADMIN_GUIDE.md` | Panduan operasional Super Admin dan Admin Sekolah. |
| `USER_GUIDE.md` | Panduan penggunaan portal untuk pengguna sekolah. |
| `ROLE_PERMISSION_MATRIX.md` | Ringkasan role dan permission utama. |
| `BACKUP_RESTORE.md` | Panduan backup dan restore database serta file upload. |
| `CHANGELOG.md` | Catatan perubahan rilis. |
| `RELEASE_NOTES.md` | Ringkasan rilis dan catatan keamanan. |
| `PHASE_15_PRODUCTION_DEPLOYMENT.md` | Checklist executable deployment production v1.0.0 ke VPS. |
| `CLAUDE.md` | Panduan kerja untuk AI coding assistant. |

---

## Role Wajib

- Super Admin
- Admin Sekolah
- Kepala Sekolah
- Waka Kurikulum
- Waka Kesiswaan
- Guru
- Wali Kelas
- Siswa
- Orang Tua / Wali
- Bendahara
- Staff TU
- Panitia PPDB
- Pembimbing PKL
- Admin BKK

---

## Permission Pattern

Setiap module memakai pola permission:

```text
module.view
module.create
module.update
module.delete
module.export
module.import
module.approve
module.verify
module.print
```

Contoh:

```text
student.view
student.create
student.update
student.delete
student.import
student.export

finance.view
finance.create
finance.verify
finance.export
finance.print

ppdb.view
ppdb.verify
ppdb.approve
ppdb.convert
```

---

## Instalasi Lokal / VPS

```bash
git clone https://github.com/arpayid/lavasmsid.git
cd lavasmsid
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

---

## Konfigurasi Database MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavasmsid
DB_USERNAME=root
DB_PASSWORD=your_password
```

## Konfigurasi Database PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lavasmsid
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

---

## Perintah Development

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
npm run dev
npm run build
vendor/bin/pint
```

---

## Perintah Production VPS

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

---

## Roadmap Eksekusi

### Tahap 0 — Validasi Foundation Laravel ✅ SELESAI (2026-05-31)

- Lengkapi skeleton Laravel standar.
- Pastikan `php artisan` berjalan.
- Pastikan route tidak error.
- Pastikan migration dan seeder berjalan.
- Pastikan Vite dan Tailwind build berhasil.

### Tahap 1 — Core Hybrid Modular Monolith

- Tambahkan `app/Core`.
- Tambahkan base service.
- Tambahkan base repository.
- Tambahkan module route loader.
- Standarkan struktur setiap module.

### Tahap 2 — Admin Panel Core

- Sidebar collapsible.
- Topbar user.
- Breadcrumb.
- Dynamic menu by permission.
- Reusable table, form, card, badge, modal, toast.

### Tahap 3 — User Management

- CRUD users.
- CRUD roles.
- CRUD permissions.
- Assign role dan permission.
- User profile.
- Activity log.

### Tahap 4 — Master Data

- CRUD siswa.
- CRUD guru.
- CRUD staff.
- CRUD jurusan.
- CRUD kelas.
- CRUD mapel.
- CRUD tahun ajaran dan semester.

### Tahap 5 — Akademik

- Jadwal pelajaran.
- Absensi.
- Nilai.
- Rapor.
- Kalender akademik.

### Tahap 6 — SMK Specialist

- PKL.
- Mitra industri.
- Teaching Factory.
- Sertifikasi kompetensi.
- BKK.
- Alumni.

### Tahap 7 — Keuangan

- Tagihan.
- Pembayaran.
- Kwitansi.
- Laporan kas.

### Tahap 8 — PPDB

- Form publik.
- Upload berkas.
- Verifikasi.
- Seleksi.
- Konversi menjadi siswa.

### Tahap 9 — Website Publik

- Profil.
- Jurusan.
- Berita.
- Agenda.
- Galeri.
- Kontak.

### Tahap 10 — Production

- Testing.
- Security check.
- Queue worker.
- Scheduler.
- Nginx.
- PHP-FPM.
- SSL.
- Backup database.

---

## Checklist Final

Project dianggap selesai jika:

- [ ] Login berjalan.
- [ ] Role permission berjalan.
- [ ] Admin panel tampil profesional.
- [ ] Website publik tampil modern.
- [ ] Dashboard berbeda sesuai role.
- [ ] CRUD siswa berjalan.
- [ ] CRUD guru berjalan.
- [ ] CRUD jurusan berjalan.
- [ ] CRUD kelas berjalan.
- [ ] Absensi berjalan.
- [ ] Nilai berjalan.
- [ ] Rapor bisa dicetak.
- [ ] Keuangan berjalan.
- [ ] PPDB online berjalan.
- [ ] PKL berjalan.
- [ ] BKK/alumni berjalan.
- [ ] Laporan operasional bisa diekspor CSV streaming.
- [ ] UI responsif di mobile.
- [ ] Migration dan seeder tidak error.
- [ ] `npm run build` berhasil.
- [ ] `php artisan test` berhasil.
- [ ] Siap deploy ke VPS production.

---

## Prompt Lanjutan untuk AI CLI

```text
Anda adalah Senior Laravel Fullstack Architect. Lanjutkan repository LavaSMSID dari kondisi saat ini dengan arsitektur Hybrid Modular Monolith Laravel.

Jangan hapus file yang sudah ada. Laravel harus tetap menjadi satu core aplikasi utama untuk website publik, admin panel, dashboard multi role, API internal, report, queue, cache, dan upload file.

Pertama, audit semua file dan jalankan composer install, npm install, php artisan route:list, php artisan migrate:fresh --seed, npm run build, dan php artisan test. Perbaiki semua error foundation sampai project Laravel valid.

Setelah foundation valid, lanjutkan roadmap di README.md dan docs/ROADMAP_UNEXECUTED_PROMPT.md secara bertahap.

Setiap module wajib mengikuti struktur Controllers, Models, Services, Repositories, Requests, Policies, Actions, Data, Resources, routes.php.

Controller harus tipis. Business logic harus berada di Service atau Action. Query kompleks berada di Repository. Validasi memakai Form Request. Authorization memakai Policy dan Spatie Permission.

Setelah setiap tahap, tampilkan file dibuat, file diubah, command dijalankan, potensi error, cara testing manual, dan langkah berikutnya.
```

---

## Catatan Status Repository

Patch dokumentasi **Phase 13** sudah digabung ke dokumentasi root repository. Dokumen operasional admin, panduan pengguna, matriks role/permission, backup/restore, changelog, release notes, deployment, dan panduan AI assistant sudah tersedia untuk audit akhir.

Perubahan Phase 13 bersifat dokumentasi-only. Tidak ada perubahan PHP, Blade, route, migration, seeder, test, atau package manager file dalam patch dokumentasi ini.

Prioritas berikutnya adalah melakukan **final audit dokumentasi** dan validasi manual production-readiness sesuai `DEPLOYMENT.md` serta `BACKUP_RESTORE.md`.
