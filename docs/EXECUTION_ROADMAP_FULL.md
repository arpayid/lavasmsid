# LavaSMSID — Full Execution Roadmap

Dokumen ini adalah roadmap eksekusi teknis untuk AI CLI/developer agar LavaSMSID dibangun secara bertahap, rapi, dan tidak keluar dari arsitektur resmi.

Arsitektur final: **Hybrid Modular Monolith Laravel**

Admin panel final: **Custom Laravel Blade + TailwindCSS + Alpine.js**, bukan Filament sebagai admin utama.

---

## Aturan Global Eksekusi

1. Jangan menghapus file yang sudah ada tanpa alasan jelas.
2. Jangan membuat menu tanpa route dan view.
3. Jangan membuat route tanpa controller dan authorization.
4. Jangan menaruh business logic panjang di controller.
5. Controller hanya menerima request, memanggil service/action, lalu mengembalikan response.
6. Validasi wajib memakai Form Request.
7. Authorization wajib memakai Policy dan Spatie Permission.
8. Query kompleks wajib masuk Repository.
9. Operasi bisnis penting wajib masuk Service atau Action.
10. Data penting wajib memakai soft delete.
11. Upload file wajib validasi MIME dan size.
12. Aksi penting wajib masuk audit log.
13. Setiap tahap wajib menjalankan command validasi.
14. Setiap tahap wajib mencatat file dibuat, file diubah, potensi error, dan cara test manual.

---

## Format Laporan Setelah Setiap Tahap

Setelah menyelesaikan tahap, AI CLI wajib menampilkan:

```text
TAHAP SELESAI: <nama tahap>

File dibuat:
- ...

File diubah:
- ...

Command dijalankan:
- ...

Hasil validasi:
- ...

Potensi error:
- ...

Cara testing manual:
- ...

Langkah berikutnya:
- ...
```

---

## Tahap 0 — Validasi Foundation Laravel

### Tujuan

Membuat repository menjadi project Laravel valid yang bisa menjalankan `php artisan`, migration, seeder, route list, build frontend, dan test dasar.

### Dependency

Tidak ada. Ini wajib dikerjakan paling pertama.

### File wajib dibuat/dilengkapi

```text
artisan
app/Http/Controllers/Controller.php
app/Models/User.php
bootstrap/cache/.gitignore
config/app.php
config/auth.php
config/cache.php
config/database.php
config/filesystems.php
config/logging.php
config/mail.php
config/permission.php
config/queue.php
config/sanctum.php
config/session.php
public/index.php
routes/console.php
storage/app/.gitignore
storage/app/public/.gitignore
storage/framework/cache/.gitignore
storage/framework/sessions/.gitignore
storage/framework/testing/.gitignore
storage/framework/views/.gitignore
storage/logs/.gitignore
```

### File yang harus diverifikasi

```text
composer.json
package.json
.env.example
bootstrap/app.php
routes/web.php
routes/api.php
routes/auth.php
resources/css/app.css
resources/js/app.js
resources/js/bootstrap.js
```

### Command wajib dijalankan

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan route:list
php artisan migrate:fresh --seed
npm run build
php artisan test
```

### Acceptance criteria

- `php artisan` berjalan.
- `php artisan route:list` tidak error.
- `php artisan migrate:fresh --seed` berhasil.
- `npm run build` berhasil.
- Login default bisa dipakai.
- Dashboard admin dapat dibuka.

### Prompt AI CLI

```text
Selesaikan foundation Laravel untuk repo LavaSMSID. Jangan hapus struktur app/Modules. Lengkapi skeleton Laravel standar, config, public index, User model, Controller base, storage gitignore, dan routes console. Setelah itu jalankan composer install, npm install, key generate, route:list, migrate:fresh --seed, npm run build, dan test. Perbaiki semua error sampai valid.
```

---

## Tahap 1 — Core Hybrid Modular Monolith

### Tujuan

Menetapkan struktur core dan module loader agar semua modul bisa berkembang secara konsisten.

### File/folder wajib dibuat

```text
app/Core/Auth/
app/Core/Dashboard/
app/Core/Settings/
app/Core/Audit/
app/Core/Notification/
app/Core/Shared/BaseService.php
app/Core/Shared/BaseRepository.php
app/Core/Shared/DataTableQuery.php
app/Providers/ModuleRouteServiceProvider.php
```

### Module target

```text
app/Modules/Academic/
app/Modules/Student/
app/Modules/Teacher/
app/Modules/Staff/
app/Modules/Attendance/
app/Modules/Grade/
app/Modules/Finance/
app/Modules/PPDB/
app/Modules/Schedule/
app/Modules/Internship/
app/Modules/IndustryPartner/
app/Modules/Alumni/
app/Modules/BKK/
app/Modules/Website/
app/Modules/Communication/
app/Modules/Report/
app/Modules/UserManagement/
```

### Struktur standar per module

```text
Controllers/
Models/
Services/
Repositories/
Requests/
Policies/
Actions/
Data/
Exports/
Imports/
Resources/
routes.php
```

### Command validasi

```bash
php artisan route:list
composer dump-autoload
```

### Acceptance criteria

- Module route bisa di-load otomatis.
- Struktur module konsisten.
- Tidak ada namespace error.
- Route global tetap berjalan.

---

## Tahap 2 — Custom Admin Panel Core

### Keputusan UI

Admin panel utama memakai **Custom Blade + TailwindCSS + Alpine.js**.

Filament tidak digunakan sebagai admin utama. Filament hanya boleh menjadi optional internal developer tool di masa depan, bukan core admin panel.

### Tujuan

Membangun kerangka admin panel enterprise yang reusable untuk semua modul.

### File wajib dibuat

```text
resources/views/layouts/admin.blade.php
resources/views/admin/dashboard.blade.php
resources/views/admin/profile.blade.php
resources/views/admin/settings/index.blade.php
resources/views/admin/components/sidebar.blade.php
resources/views/admin/components/topbar.blade.php
resources/views/admin/components/breadcrumb.blade.php
resources/views/admin/components/stat-card.blade.php
resources/views/admin/components/data-table.blade.php
resources/views/admin/components/form-input.blade.php
resources/views/admin/components/form-select.blade.php
resources/views/admin/components/form-textarea.blade.php
resources/views/admin/components/badge.blade.php
resources/views/admin/components/modal.blade.php
resources/views/admin/components/toast.blade.php
resources/views/admin/components/empty-state.blade.php
resources/views/admin/components/error-state.blade.php
resources/views/admin/components/loading-state.blade.php
```

### Fitur wajib

- Sidebar collapsible.
- Topbar profil user.
- Breadcrumb.
- Menu dinamis berdasarkan permission.
- Stat card.
- Data table reusable.
- Filter/search/sorting UI.
- Pagination UI.
- Modal form.
- Toast notification.
- Empty state.
- Error state.
- Loading state.
- Notification dropdown.
- Mobile sidebar.

### Permission menu

Menu wajib hanya muncul jika user memiliki permission terkait.

Contoh:

```text
student.view -> menu Siswa tampil
finance.view -> menu Keuangan tampil
ppdb.view -> menu PPDB tampil
```

### Command validasi

```bash
php artisan route:list
npm run build
```

### Acceptance criteria

- Admin dashboard tampil profesional.
- Sidebar responsif.
- Menu mengikuti permission.
- Tidak ada menu mati.
- Komponen bisa dipakai di modul CRUD.

---

## Tahap 3 — User Management dan RBAC

### Tujuan

Membangun pengelolaan user, role, permission, profile, dan audit awal.

### Module

```text
app/Modules/UserManagement/
```

### File wajib dibuat

```text
Controllers/UserController.php
Controllers/RoleController.php
Controllers/PermissionController.php
Controllers/ProfileController.php
Models/ActivityLog.php
Models/AuditLog.php
Services/UserService.php
Services/RoleService.php
Repositories/UserRepository.php
Requests/StoreUserRequest.php
Requests/UpdateUserRequest.php
Requests/StoreRoleRequest.php
Requests/UpdateRoleRequest.php
Policies/UserPolicy.php
Actions/AssignRoleAction.php
Actions/ChangePasswordAction.php
routes.php
```

### View wajib

```text
resources/views/modules/user-management/users/index.blade.php
resources/views/modules/user-management/users/create.blade.php
resources/views/modules/user-management/users/edit.blade.php
resources/views/modules/user-management/users/show.blade.php
resources/views/modules/user-management/roles/index.blade.php
resources/views/modules/user-management/roles/create.blade.php
resources/views/modules/user-management/roles/edit.blade.php
resources/views/modules/user-management/profile/edit.blade.php
```

### Permission wajib

```text
user.view
user.create
user.update
user.delete
role.view
role.create
role.update
role.delete
permission.view
permission.update
```

### Acceptance criteria

- Super Admin bisa CRUD user.
- Super Admin bisa CRUD role.
- Super Admin bisa assign role.
- Role non-admin tidak bisa akses user management.
- Profile bisa diedit.
- Password bisa diganti.
- Audit log mencatat aksi penting.

---

## Tahap 4 — Master Data

### Tujuan

Menyelesaikan data dasar sekolah yang menjadi dependency modul lain.

### Dependency

Tahap 0, 1, 2, 3 harus valid.

### Urutan pengerjaan

1. School Profile
2. Academic Year
3. Semester
4. Department
5. Competency
6. Classroom
7. Subject
8. Teacher
9. Staff
10. Student
11. Guardian
12. Industry Partner
13. Payment Category
14. Academic Calendar

### CRUD wajib per entity

Setiap CRUD harus memiliki:

```text
index
create
store
show
edit
update
destroy
search
filter
pagination
empty state
success toast
error handling
permission middleware
Form Request
Policy
Service
Repository
```

### File pattern per entity

Contoh Student:

```text
app/Modules/Student/Controllers/StudentController.php
app/Modules/Student/Models/Student.php
app/Modules/Student/Services/StudentService.php
app/Modules/Student/Repositories/StudentRepository.php
app/Modules/Student/Requests/StoreStudentRequest.php
app/Modules/Student/Requests/UpdateStudentRequest.php
app/Modules/Student/Policies/StudentPolicy.php
app/Modules/Student/Actions/CreateStudentAction.php
app/Modules/Student/Actions/UpdateStudentAction.php
app/Modules/Student/Exports/StudentsExport.php
app/Modules/Student/Imports/StudentsImport.php
app/Modules/Student/routes.php
resources/views/modules/student/index.blade.php
resources/views/modules/student/create.blade.php
resources/views/modules/student/edit.blade.php
resources/views/modules/student/show.blade.php
```

### Acceptance criteria

- CRUD siswa berjalan.
- CRUD guru berjalan.
- CRUD jurusan berjalan.
- CRUD kelas berjalan.
- Search dan filter berjalan.
- Import Excel siswa/guru tersedia.
- Export Excel siswa/guru tersedia.
- Upload foto siswa/guru aman.

---

## Tahap 5 — Academic, Schedule, Attendance, Grade

### Tujuan

Membangun proses akademik inti.

### Dependency

Master Data harus valid.

### Subtahap 5A — Schedule

Fitur:

- Jadwal pelajaran.
- Jadwal guru.
- Jadwal kelas.
- Jadwal ruang.
- Jadwal ujian.
- Deteksi bentrok guru, kelas, ruang, dan jam.
- Tampilan mingguan.
- Export PDF.

Acceptance:

- Jadwal bisa dibuat.
- Bentrok terdeteksi.
- Jadwal bisa difilter kelas/guru/hari.

### Subtahap 5B — Attendance

Fitur:

- Absensi siswa harian.
- Absensi per mapel.
- Absensi guru.
- Status hadir, sakit, izin, alpha, terlambat.
- Rekap harian.
- Rekap bulanan.
- Rekap per kelas.
- Rekap per siswa.
- Grafik kehadiran.
- Export PDF/Excel.

Acceptance:

- Absensi tidak dobel per siswa dan tanggal.
- Rekap bisa difilter.
- Export berjalan.

### Subtahap 5C — Grade dan Report Card

Fitur:

- Nilai tugas.
- Nilai harian.
- Nilai UTS.
- Nilai UAS.
- Nilai praktik.
- Nilai proyek.
- Nilai produktif SMK.
- Nilai sikap.
- Nilai pengetahuan.
- Nilai keterampilan.
- Bobot nilai.
- Hitung nilai akhir otomatis.
- Rapor PDF.
- Rekap nilai per kelas, siswa, mapel.

Acceptance:

- Guru bisa input nilai sesuai mapel.
- Siswa hanya lihat nilai sendiri.
- Orang tua hanya lihat nilai anaknya.
- Rapor PDF bisa dicetak.

---

## Tahap 6 — Finance

### Tujuan

Membangun modul keuangan sekolah.

### Dependency

Student, Classroom, Academic Year, Semester, UserManagement valid.

### File/module

```text
app/Modules/Finance/
Controllers/
Models/
Services/
Repositories/
Requests/
Policies/
Actions/
Exports/
Resources/
routes.php
```

### Fitur wajib

- Kategori pembayaran.
- Jenis tagihan.
- Generate tagihan siswa.
- Tagihan SPP.
- Tagihan daftar ulang.
- Tagihan ujian.
- Tagihan praktik.
- Pembayaran penuh.
- Pembayaran cicilan.
- Status lunas/sebagian/belum lunas.
- Validasi bendahara.
- Kwitansi PDF.
- Laporan pemasukan.
- Laporan pengeluaran.
- Laporan kas.
- Dashboard keuangan.

### Permission

```text
finance.view
finance.create
finance.update
finance.verify
finance.export
finance.print
```

### Rule akses

- Bendahara bisa mengelola finance.
- Super Admin bisa semua.
- Siswa hanya melihat tagihan sendiri.
- Orang tua hanya melihat tagihan anaknya.
- Guru tidak boleh akses finance.

### Acceptance criteria

- Tagihan bisa dibuat.
- Pembayaran bisa dicatat.
- Status otomatis berubah.
- Kwitansi PDF bisa dicetak.
- Guru ditolak saat akses finance.

---

## Tahap 7 — PPDB Online

### Tujuan

Membangun pendaftaran siswa baru online.

### Fitur wajib

- Form publik PPDB.
- Nomor pendaftaran otomatis.
- Pilihan jurusan.
- Data calon siswa.
- Data orang tua.
- Upload dokumen.
- Validasi dokumen.
- Verifikasi panitia.
- Approve/reject.
- Pengumuman hasil.
- Bukti pendaftaran PDF.
- Konversi diterima menjadi siswa aktif.

### Permission

```text
ppdb.view
ppdb.verify
ppdb.approve
ppdb.convert
ppdb.export
ppdb.print
```

### Acceptance criteria

- Pendaftar bisa daftar dari publik.
- File upload aman.
- Panitia bisa verifikasi.
- Pendaftar diterima bisa dikonversi ke siswa.

---

## Tahap 8 — SMK Specialist: PKL, Industry, Teaching Factory

### Tujuan

Menyediakan fitur khusus SMK.

### Fitur PKL

- Mitra industri.
- Penempatan siswa PKL.
- Guru pembimbing.
- Pembimbing industri.
- Jadwal PKL.
- Logbook harian.
- Monitoring.
- Penilaian PKL.
- Sertifikat PKL.
- Laporan PKL.

### Fitur Teaching Factory dan Sertifikasi

- Teaching Factory project.
- Sertifikasi kompetensi.
- Uji Kompetensi Keahlian.
- Nilai sertifikasi.
- Laporan sertifikasi.

### Acceptance criteria

- Siswa bisa membuat logbook.
- Pembimbing bisa monitoring.
- Nilai PKL bisa disimpan.
- Laporan PKL bisa diexport.

---

## Tahap 9 — BKK dan Alumni

### Fitur wajib

- Data alumni.
- Tracer study.
- Status bekerja/kuliah/wirausaha.
- Data perusahaan.
- Lowongan kerja.
- Lamaran alumni.
- Statistik penyerapan kerja.
- Dashboard BKK.
- Laporan alumni.

### Permission

```text
alumni.view
alumni.create
alumni.update
alumni.export
bkk.view
bkk.create
bkk.update
bkk.export
```

### Acceptance criteria

- Admin BKK bisa kelola alumni.
- Lowongan bisa dibuat.
- Alumni bisa melamar.
- Statistik tampil di dashboard.

---

## Tahap 10 — Website Publik dan CMS Konten

### Tujuan

Menyelesaikan website publik dan CMS konten.

### Halaman publik

- Beranda.
- Profil sekolah.
- Visi misi.
- Sejarah.
- Sambutan kepala sekolah.
- Jurusan.
- Kompetensi.
- Fasilitas.
- Prestasi.
- Berita.
- Agenda.
- Galeri foto.
- Galeri video.
- PPDB.
- Mitra industri.
- Teaching Factory.
- PKL.
- BKK.
- Alumni.
- Kontak.

### CMS admin

- CRUD berita.
- CRUD agenda.
- CRUD galeri.
- CRUD fasilitas.
- CRUD prestasi.
- CRUD halaman profil.
- SEO title, description, slug.

### Acceptance criteria

- Semua halaman publik responsif.
- Konten bisa diatur dari admin.
- SEO meta tersedia.
- CTA PPDB jelas.

---

## Tahap 11 — Communication dan Notification

### Fitur wajib

- Pengumuman sekolah.
- Pesan internal.
- Notification dropdown.
- Broadcast siswa.
- Broadcast orang tua.
- Broadcast guru.
- Notifikasi pembayaran.
- Notifikasi absensi.
- Notifikasi nilai.
- Riwayat notifikasi.
- Queue untuk broadcast besar.

### Acceptance criteria

- Admin bisa broadcast.
- User menerima notifikasi sesuai role.
- Riwayat tersimpan.
- Broadcast besar masuk queue.

---

## Tahap 12 — Report Center

### Laporan wajib

- Laporan siswa.
- Laporan guru.
- Laporan kelas.
- Laporan jurusan.
- Laporan absensi.
- Laporan nilai.
- Laporan rapor.
- Laporan keuangan.
- Laporan PPDB.
- Laporan PKL.
- Laporan alumni.

### Filter wajib

- Tanggal awal.
- Tanggal akhir.
- Tahun ajaran.
- Semester.
- Kelas.
- Jurusan.
- Status.

### Export

- PDF via DomPDF.
- Excel via Laravel Excel.
- Queue untuk dataset besar.

### Acceptance criteria

- Semua laporan utama bisa dibuka.
- Filter berjalan.
- Export PDF/Excel berhasil.

---

## Tahap 13 — Security Hardening

### Checklist

- Semua admin route memakai auth.
- Semua modul memakai permission.
- Policy untuk siswa, nilai, absensi, pembayaran, PPDB, file.
- Rate limit login.
- Validasi upload MIME dan size.
- Audit log aksi penting.
- Soft delete data penting.
- Proteksi data lintas role.
- Error page production.
- Environment production aman.
- Security header Nginx disiapkan.

### Test akses wajib

- Guru tidak bisa akses finance.
- Siswa hanya bisa akses data sendiri.
- Orang tua hanya bisa akses data anaknya.
- Panitia PPDB hanya akses PPDB.
- Bendahara hanya akses finance.

---

## Tahap 14 — Testing

### Test wajib

```text
tests/Feature/Auth/LoginTest.php
tests/Feature/Rbac/RolePermissionTest.php
tests/Feature/Student/StudentCrudTest.php
tests/Feature/Finance/FinanceAccessTest.php
tests/Feature/PPDB/PpdbRegistrationTest.php
tests/Feature/Attendance/AttendanceTest.php
tests/Feature/Grade/GradeTest.php
tests/Feature/Report/ReportExportTest.php
```

### Acceptance criteria

- `php artisan test` berhasil.
- Test mencakup role penting.
- Test mencegah akses lintas role.
- Test export laporan tidak error.

---

## Tahap 15 — Production VPS Deployment

### File dokumentasi/script target

```text
docs/DEPLOYMENT_VPS.md
docs/NGINX_EXAMPLE.conf
docs/SUPERVISOR_QUEUE.conf
docs/CRON_SCHEDULER.md
docs/BACKUP_DATABASE.md
```

### Isi wajib

- Install PHP 8.3+.
- Install Composer.
- Install Node.js.
- Install MySQL/PostgreSQL.
- Install Redis optional.
- Setup Nginx.
- Setup PHP-FPM.
- Setup SSL Certbot.
- Setup Laravel scheduler.
- Setup Supervisor queue worker.
- Setup permission folder.
- Setup backup database.
- Laravel optimize commands.

### Acceptance criteria

- Aplikasi bisa berjalan via Nginx.
- SSL aktif.
- Queue worker aktif.
- Scheduler aktif.
- Backup database tersedia.

---

## Definition of Done Final

Project LavaSMSID selesai jika:

- Login berjalan.
- Role permission berjalan.
- Custom admin panel profesional.
- Website publik modern.
- Dashboard multi role.
- CRUD siswa/guru/jurusan/kelas berjalan.
- Absensi berjalan.
- Nilai berjalan.
- Rapor PDF berjalan.
- Finance berjalan.
- PPDB online berjalan.
- PKL berjalan.
- BKK/alumni berjalan.
- Report PDF/Excel berjalan.
- UI responsif mobile.
- Migration dan seeder tidak error.
- Build frontend berhasil.
- Test berhasil.
- Siap deploy VPS production.

---

## Prompt Utama untuk AI CLI

```text
Anda adalah Senior Laravel Fullstack Architect. Lanjutkan repository LavaSMSID berdasarkan docs/EXECUTION_ROADMAP_FULL.md.

Arsitektur final adalah Hybrid Modular Monolith Laravel. Admin panel utama adalah custom Laravel Blade + TailwindCSS + Alpine.js, bukan Filament.

Jangan hapus file yang sudah ada. Kerjakan bertahap mulai dari Tahap 0. Setelah setiap tahap, jalankan command validasi, tampilkan file dibuat, file diubah, command dijalankan, hasil validasi, potensi error, cara testing manual, dan langkah berikutnya.

Pastikan controller tipis, business logic di Service/Action, query kompleks di Repository, validasi di Form Request, authorization di Policy dan Spatie Permission. Pastikan route:list, migrate:fresh --seed, npm run build, dan php artisan test berhasil.
```
