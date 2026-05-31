# Roadmap Prompt yang Belum Dieksekusi — LavaSMSID

Dokumen ini berisi daftar pekerjaan dari prompt utama **SMK Management System Professional** yang belum selesai dieksekusi penuh di repository `arpayid/lavasmsid`.

Status saat ini: **Tahap 0 (Foundation Laravel) dan Tahap 1 (Core Hybrid Modular Monolith) SELESAI ✅ pada 2026-05-31.** Repository sudah memiliki struktur modular lengkap, routes per modul, base service/repository, permission middleware, custom admin panel components, dan seluruh validasi foundation (composer install, npm install, route:list, migrate:fresh --seed, npm run build, php artisan test) berhasil. Roadmap ini dipakai untuk melanjutkan pengembangan dari Tahap 2 melalui AI CLI, local development, atau VPS.

---

## 0. Perbaikan Fondasi yang Harus Didahulukan ✅ SELESAI (Tahap 0)

### 0.1 Lengkapi Skeleton Laravel Standar ✅ SELESAI

~~Belum lengkap~~ — Sudah lengkap:

- `artisan` ✅
- `app/Models/User.php` ✅
- `app/Http/Controllers/Controller.php` ✅
- `config/app.php` ✅
- `config/auth.php` ✅
- `config/database.php` ✅
- `config/permission.php` ✅
- `config/sanctum.php` ✅
- `public/index.php` ✅
- `routes/console.php` ✅
- `storage/` structure ✅
- `bootstrap/cache/.gitignore` ✅

Instruksi untuk AI CLI:

```text
Lengkapi repository ini menjadi Laravel 12 project valid tanpa menghapus struktur app/Modules yang sudah ada. Tambahkan file framework standar Laravel yang belum ada, pastikan composer install, php artisan, php artisan migrate --seed, npm install, dan npm run build bisa berjalan.
```

### 0.2 Lengkapi Frontend Build Config ✅ SELESAI

~~Belum lengkap / perlu diverifikasi~~ — Sudah lengkap:

- `vite.config.js` ✅
- `tailwind.config.js` ✅
- `postcss.config.js` ✅
- `resources/css/app.css` ✅
- `resources/js/app.js` ✅
- `resources/js/bootstrap.js` ✅

Instruksi:

```text
Tambahkan konfigurasi Vite, TailwindCSS, PostCSS, Alpine.js, Chart.js, dan Laravel Vite Plugin. Pastikan semua Blade yang memakai @vite tidak error dan npm run build berhasil.
```

### 0.3 Perbaiki Route Resource yang Belum Lengkap

Saat ini beberapa route menggunakan `Route::resource`, tetapi controller belum memiliki semua method resource.

Wajib tambahkan method:

- `create`
- `store`
- `show`
- `edit`
- `update`
- `destroy`

Untuk controller:

- `StudentController`
- `TeacherController`
- `DepartmentController`

Instruksi:

```text
Lengkapi semua controller resource agar route:list tidak error. Semua method harus memiliki view atau redirect yang valid. Jangan membuat menu tanpa route/view.
```

---

## 1. Tahap 1 — Setup Foundation Lanjutan ✅ SELESAI (Sebagian besar sudah ada)

Sudah selesai:
- ✅ Setup auth (custom Blade login/logout) — sudah ada
- ✅ Publish dan migrate Spatie Permission — sudah ada
- ✅ Publish Sanctum config dan migration — sudah ada
- ✅ Setup Service Provider untuk auto-load module route (`ModuleRouteServiceProvider`) — sudah ada
- ✅ Middleware role/permission — sudah ada di `bootstrap/app.php`
- ✅ Setup route per modul (`routes.php` di 18 modul) — sudah ada

Belum / Perlu dilanjutkan:
- [ ] Setup error pages `403`, `404`, `500`.
- [ ] Setup rate limiter login.

---

## 2. Tahap 2 — Admin Panel Core

Belum dieksekusi penuh:

- Sidebar collapsible.
- Topbar dengan profil user.
- Breadcrumb.
- Menu dinamis berdasarkan permission.
- Card statistik reusable.
- Table component reusable.
- Form component reusable.
- Badge component.
- Modal component.
- Toast notification.
- Loading state.
- Empty state.
- Error state.
- Notification dropdown.
- User profile page.
- Setting sekolah.
- Activity log page.
- Quick action lengkap.
- Responsive mobile navigation.
- Dark mode optional.

Prompt eksekusi:

```text
Bangun Admin Panel Core LavaSMSID dengan UI enterprise solid background, sidebar collapsible, topbar profil, breadcrumb, menu dinamis berdasarkan permission Spatie, komponen card/table/form/badge/modal/toast/loading/empty/error state, notification dropdown, quick action, user profile, setting sekolah, activity log, dan responsive mobile layout. Semua menu harus memiliki route dan view valid.
```

Acceptance criteria:

- Admin panel tampil profesional di desktop dan mobile.
- Menu berubah sesuai role.
- Tidak ada menu mati.
- Semua component bisa dipakai ulang di modul CRUD.

---

## 3. Tahap 3 — User Management

Belum dieksekusi:

- CRUD users.
- CRUD roles.
- CRUD permissions.
- Assign role ke user.
- Assign permission ke role.
- User profile.
- Change password.
- Activity log.
- Audit log aksi penting.

Prompt eksekusi:

```text
Implementasikan modul UserManagement di app/Modules/UserManagement dengan Controller, Model bila perlu, Service, Repository, Request, Policy, route, dan view. Buat CRUD user, role, permission, assign role, assign permission, profile, change password, activity log, dan audit log. Gunakan Spatie Permission dan validasi Form Request.
```

Acceptance criteria:

- Super Admin bisa kelola semua user/role/permission.
- Role non-admin tidak bisa akses modul user management.
- Semua aksi penting tercatat ke audit log.

---

## 4. Tahap 4 — Master Data

Belum dieksekusi penuh:

- Profil sekolah CRUD.
- Tahun ajaran CRUD.
- Semester CRUD.
- Jurusan CRUD lengkap.
- Kompetensi keahlian CRUD.
- Tingkat kelas X, XI, XII.
- Rombel / kelas CRUD.
- Ruang kelas CRUD.
- Mata pelajaran CRUD.
- Guru CRUD lengkap.
- Staff CRUD lengkap.
- Siswa CRUD lengkap.
- Orang tua / wali CRUD.
- Ekstrakurikuler CRUD.
- Mitra industri CRUD.
- Kategori pembayaran CRUD.
- Jenis tagihan CRUD.
- Kalender akademik CRUD.

Setiap CRUD wajib:

- index
- create
- store
- show
- edit
- update
- destroy
- Form Request validation
- permission check
- search
- filter
- pagination
- empty state
- success/error notification

Prompt eksekusi:

```text
Implementasikan seluruh Master Data LavaSMSID secara modular. Buat CRUD lengkap dengan service layer, repository layer untuk query kompleks, Form Request, Policy, permission middleware, search, filter, pagination, empty state, toast notification, dan soft delete untuk data penting. Pastikan semua route dan view tidak error.
```

Acceptance criteria:

- CRUD siswa berjalan.
- CRUD guru berjalan.
- CRUD jurusan berjalan.
- CRUD kelas berjalan.
- Import/export Excel untuk siswa dan guru tersedia.
- Upload foto aman dengan validasi MIME dan size.

---

## 5. Tahap 5 — Akademik

Belum dieksekusi:

- Pembagian siswa ke kelas.
- Pembagian wali kelas.
- Pembagian guru mapel.
- Jadwal pelajaran.
- Jadwal ujian.
- Kalender akademik lanjutan.
- Kenaikan kelas.
- Kelulusan siswa.

Prompt eksekusi:

```text
Bangun modul Academic lengkap untuk SMK: pembagian siswa ke kelas, wali kelas, guru mapel, kalender akademik, jadwal pelajaran, jadwal ujian, kenaikan kelas, dan kelulusan. Terapkan validasi bentrok jadwal, permission, service layer, dan tampilan admin profesional.
```

Acceptance criteria:

- Jadwal pelajaran bisa dibuat.
- Deteksi bentrok guru/kelas/ruang berjalan.
- Kenaikan kelas bisa diproses.
- Kelulusan siswa bisa dicatat.

---

## 6. Tahap 5B — Absensi

Belum dieksekusi penuh:

- Absensi siswa harian.
- Absensi per mata pelajaran.
- Absensi guru.
- Status hadir/sakit/izin/alpha/terlambat.
- Rekap harian.
- Rekap bulanan.
- Rekap per kelas.
- Rekap per siswa.
- Export PDF.
- Export Excel.
- Grafik kehadiran.
- Filter tanggal, kelas, jurusan, semester.

Prompt eksekusi:

```text
Implementasikan modul Attendance lengkap untuk siswa dan guru. Buat input absensi harian dan per mapel, rekap harian/bulanan/per kelas/per siswa, grafik kehadiran, export PDF/Excel, filter lengkap, permission, policy, dan validasi agar absensi tidak dobel pada tanggal yang sama.
```

Acceptance criteria:

- Absensi bisa disimpan.
- Rekap bisa difilter.
- Export PDF/Excel tidak error.

---

## 7. Tahap 5C — Nilai dan Rapor

Belum dieksekusi:

- Input nilai tugas.
- Input nilai harian.
- Input nilai UTS.
- Input nilai UAS.
- Input nilai praktik.
- Input nilai proyek.
- Input nilai produktif SMK.
- Nilai PKL.
- Nilai sertifikasi kompetensi.
- Nilai teaching factory.
- Nilai sikap.
- Nilai pengetahuan.
- Nilai keterampilan.
- Perhitungan nilai akhir otomatis.
- Bobot nilai.
- Rapor siswa.
- Cetak rapor PDF.
- Rekap nilai per kelas/siswa/mapel.
- Ranking optional.

Prompt eksekusi:

```text
Bangun modul Grade dan ReportCard lengkap. Buat input nilai multi komponen, bobot nilai, hitung nilai akhir otomatis, nilai sikap/pengetahuan/keterampilan, nilai produktif SMK, nilai PKL, nilai sertifikasi, rekap nilai, ranking optional, dan cetak rapor PDF. Terapkan permission per role guru, wali kelas, siswa, dan orang tua.
```

Acceptance criteria:

- Guru bisa input nilai sesuai mapel.
- Siswa hanya melihat nilai sendiri.
- Orang tua hanya melihat nilai anaknya.
- Rapor PDF bisa dicetak.

---

## 8. Tahap 6 — SMK Specialist

Belum dieksekusi:

- Program keahlian detail.
- Kompetensi keahlian detail.
- Mata pelajaran produktif.
- PKL / Prakerin.
- Mitra industri.
- Guru pembimbing PKL.
- Pembimbing industri.
- Logbook PKL.
- Monitoring PKL.
- Penilaian PKL.
- Teaching Factory.
- Sertifikasi kompetensi.
- Uji Kompetensi Keahlian.

Prompt eksekusi:

```text
Implementasikan fitur khusus SMK: program keahlian, kompetensi keahlian, mapel produktif, PKL/Prakerin, mitra industri, guru pembimbing, pembimbing industri, logbook harian PKL, monitoring, penilaian, sertifikat PKL, Teaching Factory, sertifikasi kompetensi, dan UKK. Gunakan arsitektur modular, service layer, policy, dan laporan export.
```

Acceptance criteria:

- Siswa bisa membuat logbook PKL.
- Pembimbing bisa monitoring.
- Nilai PKL bisa dimasukkan.
- Laporan PKL bisa diexport.

---

## 9. Tahap 6B — BKK dan Alumni

Belum dieksekusi:

- Data alumni.
- Tracer study.
- Status bekerja/kuliah/wirausaha.
- Data perusahaan mitra.
- Lowongan kerja.
- Lamaran alumni.
- Statistik penyerapan kerja.
- Laporan alumni.

Prompt eksekusi:

```text
Bangun modul Alumni dan BKK. Buat CRUD alumni, tracer study, status alumni, perusahaan mitra, lowongan kerja, lamaran alumni, statistik penyerapan kerja, dashboard BKK, dan laporan alumni. Terapkan permission Admin BKK dan export PDF/Excel.
```

Acceptance criteria:

- Admin BKK bisa kelola alumni dan lowongan.
- Alumni bisa melamar lowongan.
- Statistik penyerapan alumni tampil di dashboard.

---

## 10. Tahap 7 — Keuangan

Belum dieksekusi penuh:

- Kategori pembayaran CRUD.
- Tagihan SPP.
- Tagihan daftar ulang.
- Tagihan ujian.
- Tagihan praktik.
- Tagihan seragam.
- Tagihan lain-lain.
- Pembayaran siswa.
- Pembayaran cicilan.
- Status lunas/belum lunas/sebagian.
- Kwitansi pembayaran.
- Cetak bukti pembayaran PDF.
- Rekap pembayaran per kelas/siswa.
- Laporan pemasukan.
- Laporan pengeluaran.
- Laporan kas.
- Dashboard keuangan.
- Validasi pembayaran oleh bendahara.

Prompt eksekusi:

```text
Implementasikan modul Finance lengkap. Buat tagihan siswa, kategori pembayaran, pembayaran penuh/cicilan, status pembayaran otomatis, validasi bendahara, kwitansi PDF, rekap pembayaran per kelas/siswa, laporan pemasukan, pengeluaran, kas, dashboard keuangan, dan export Excel/PDF. Guru dan siswa tidak boleh mengakses modul keuangan kecuali data tagihan pribadi siswa.
```

Acceptance criteria:

- Bendahara bisa membuat dan memverifikasi pembayaran.
- Kwitansi PDF bisa dicetak.
- Siswa hanya melihat tagihan sendiri.
- Guru tidak bisa akses finance.

---

## 11. Tahap 8 — PPDB Online

Belum dieksekusi:

- Form pendaftaran online publik.
- Nomor pendaftaran otomatis.
- Pilihan jurusan SMK.
- Data calon siswa.
- Data orang tua.
- Upload berkas.
- Validasi berkas.
- Seleksi administrasi.
- Status pendaftaran.
- Pengumuman hasil seleksi.
- Cetak bukti pendaftaran.
- Dashboard panitia PPDB.
- Konversi pendaftar diterima menjadi siswa aktif.

Prompt eksekusi:

```text
Bangun modul PPDB Online lengkap. Buat form publik, nomor pendaftaran otomatis, pilihan jurusan, data calon siswa dan orang tua, upload dokumen aman, validasi MIME/size, verifikasi berkas, seleksi, status pendaftaran, pengumuman hasil, cetak bukti pendaftaran PDF, dashboard panitia PPDB, dan konversi calon siswa diterima menjadi siswa aktif.
```

Acceptance criteria:

- Pendaftar bisa daftar dari halaman publik.
- Dokumen bisa diupload aman.
- Panitia PPDB bisa verifikasi dan approve.
- Data diterima bisa dikonversi menjadi siswa.

---

## 12. Tahap 9 — Website Publik

Belum dieksekusi penuh:

- Profil sekolah.
- Visi dan misi.
- Sejarah sekolah.
- Sambutan kepala sekolah.
- Jurusan / program keahlian.
- Kompetensi keahlian.
- Fasilitas sekolah.
- Prestasi.
- Berita sekolah.
- Agenda sekolah.
- Galeri foto.
- Galeri video.
- Informasi PPDB.
- Mitra industri.
- Teaching Factory.
- PKL / Prakerin.
- BKK.
- Alumni.
- Kontak.
- SEO meta.

Prompt eksekusi:

```text
Lengkapi website publik LavaSMSID dengan halaman profil sekolah, visi misi, sejarah, sambutan kepala sekolah, jurusan, kompetensi, fasilitas, prestasi, berita, agenda, galeri foto/video, PPDB, mitra industri, Teaching Factory, PKL, BKK, alumni, kontak, SEO meta, slug, dan CMS admin untuk mengelola konten publik.
```

Acceptance criteria:

- Semua halaman publik tampil responsif.
- Konten bisa dikelola dari admin.
- SEO meta dasar tersedia.
- CTA PPDB jelas.

---

## 13. Tahap 9B — Komunikasi

Belum dieksekusi:

- Pengumuman sekolah.
- Pesan internal.
- Notifikasi dashboard.
- Broadcast ke siswa.
- Broadcast ke orang tua.
- Broadcast ke guru.
- Notifikasi pembayaran.
- Notifikasi absensi.
- Notifikasi nilai.
- Riwayat notifikasi.

Prompt eksekusi:

```text
Implementasikan modul Communication: pengumuman sekolah, pesan internal, notifikasi dashboard, broadcast ke siswa/orang tua/guru, notifikasi pembayaran, absensi, nilai, dan riwayat notifikasi. Gunakan queue untuk broadcast besar dan permission sesuai role.
```

Acceptance criteria:

- Admin bisa broadcast pengumuman.
- User menerima notifikasi sesuai role.
- Riwayat notifikasi tersimpan.

---

## 14. Tahap 10 — Report dan Export

Belum dieksekusi:

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
- Export PDF.
- Export Excel.
- Filter tanggal, kelas, jurusan, semester, tahun ajaran.

Prompt eksekusi:

```text
Bangun modul Report lengkap. Buat laporan siswa, guru, kelas, jurusan, absensi, nilai, rapor, keuangan, PPDB, PKL, alumni. Semua laporan wajib punya filter relevan dan export PDF/Excel. Gunakan Laravel Excel dan DomPDF. Pastikan export tidak timeout untuk dataset besar dengan queue bila perlu.
```

Acceptance criteria:

- Semua laporan utama bisa dibuka.
- Export PDF/Excel berhasil.
- Filter berjalan benar.

---

## 15. Tahap 10B — Security Hardening

Belum dieksekusi penuh:

- Policy semua data sensitif.
- Middleware role/permission per route.
- Rate limit login.
- Secure file upload.
- Validasi MIME file.
- Audit log aksi penting.
- Soft delete semua data penting.
- Proteksi data lintas role.
- Error handling production.
- Security headers.
- Backup `.env.example` aman.

Prompt eksekusi:

```text
Lakukan security hardening LavaSMSID. Terapkan policy untuk data siswa, nilai, absensi, pembayaran, PPDB, dan file upload. Pastikan setiap route admin punya middleware auth dan permission. Tambahkan rate limit login, secure upload validation, audit log aksi penting, soft delete, proteksi data lintas role, error page production, dan rekomendasi security header di Nginx.
```

Acceptance criteria:

- Guru tidak bisa akses finance.
- Siswa hanya bisa lihat data sendiri.
- Orang tua hanya bisa lihat data anaknya.
- Upload file menolak MIME berbahaya.
- Aksi penting tercatat di audit log.

---

## 16. Tahap 10C — Testing

Belum dieksekusi:

- Test user bisa login.
- Test role permission berjalan.
- Test admin bisa CRUD siswa.
- Test guru tidak bisa akses modul keuangan.
- Test bendahara bisa akses pembayaran.
- Test siswa hanya bisa lihat data sendiri.
- Test orang tua hanya bisa lihat data anaknya.
- Test PPDB bisa pendaftaran.
- Test absensi bisa disimpan.
- Test nilai bisa disimpan.
- Test export laporan tidak error.

Prompt eksekusi:

```text
Buat test Pest/PHPUnit untuk auth, role permission, CRUD siswa, pembatasan akses guru ke finance, akses bendahara ke pembayaran, akses siswa ke data sendiri, akses orang tua ke data anak, PPDB registration, absensi, nilai, dan export laporan. Pastikan php artisan test berhasil.
```

Acceptance criteria:

- `php artisan test` berhasil.
- Test mencakup akses role penting.
- Test mencegah regresi route utama.

---

## 17. Tahap 10D — Production VPS Deployment

Belum dieksekusi penuh:

- Nginx config.
- PHP-FPM config.
- Supervisor queue worker.
- Cron scheduler.
- SSL Certbot.
- Redis optional.
- Database backup script.
- File permission production.
- Laravel optimization commands.
- Deployment checklist.

Prompt eksekusi:

```text
Buat dokumentasi dan script deployment production VPS untuk LavaSMSID di Ubuntu/Debian: install PHP 8.3, Composer, Node.js, MySQL/PostgreSQL, Redis optional, Nginx, PHP-FPM, SSL Certbot, Supervisor queue worker, cron scheduler, file permission, database backup, dan Laravel optimization. Semua command harus siap copy-paste.
```

Acceptance criteria:

- VPS bisa menjalankan aplikasi via Nginx.
- Queue worker aktif.
- Scheduler aktif.
- SSL aktif.
- Backup database tersedia.

---

## 18. Checklist Kriteria Selesai Final

Project dianggap selesai jika semua item ini terpenuhi:

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
- [ ] Laporan bisa export PDF/Excel.
- [ ] UI responsif di mobile.
- [ ] Migration dan seeder tidak error.
- [ ] `npm run build` berhasil.
- [ ] `php artisan test` berhasil.
- [ ] Siap deploy ke VPS production.

---

## 19. Prompt Lanjutan yang Direkomendasikan untuk AI CLI

Gunakan prompt ini setelah clone repo di VPS/local:

```text
Anda adalah Senior Laravel Fullstack Architect. Lanjutkan repository LavaSMSID dari kondisi saat ini. Jangan hapus file yang sudah ada. Pertama, audit semua file dan jalankan composer install, npm install, php artisan route:list, php artisan migrate:fresh --seed, npm run build, dan php artisan test. Perbaiki semua error foundation sampai project Laravel valid. Setelah itu lanjutkan roadmap di docs/ROADMAP_UNEXECUTED_PROMPT.md secara bertahap. Setelah setiap tahap, tampilkan file dibuat, file diubah, command dijalankan, potensi error, cara testing manual, dan langkah berikutnya.
```
