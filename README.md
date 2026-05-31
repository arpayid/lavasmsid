# LavaSMSID — SMK Management System Professional

LavaSMSID adalah starter project Laravel fullstack untuk School Management System khusus jenjang SMK dengan pendekatan Hybrid Modular Architecture.

## Status Implementasi Saat Ini

Tahap yang sudah dibuat di repo ini:

- Fondasi dependency Laravel, Sanctum, Spatie Permission, Laravel Excel, DomPDF.
- Struktur awal `app/Modules` untuk Website, Dashboard, Student, Academic, dan Teacher.
- Route publik, auth, admin, dan API internal.
- Login page modern.
- Admin dashboard layout enterprise berbasis TailwindCSS.
- Website publik beranda dengan CTA PPDB.
- Migration core sekolah, akademik, siswa, absensi, nilai, keuangan, PPDB, mitra industri, dan PKL.
- Seeder role dan permission lengkap.
- Seeder data awal jurusan SMK, tahun ajaran, semester, kategori pembayaran, dan Super Admin.
- Dokumentasi arsitektur hybrid modular.

## Akun Seeder Default

```text
Email: admin@lavasmsid.local
Password: password
Role: Super Admin
```

## Stack

- PHP 8.3+
- Laravel 12+
- MySQL atau PostgreSQL
- Blade
- TailwindCSS
- Vite
- Alpine.js
- Chart.js
- Laravel Sanctum
- Spatie Laravel Permission
- Laravel Excel
- DomPDF

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

## Konfigurasi Database

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavasmsid
DB_USERNAME=root
DB_PASSWORD=your_password
```

Untuk PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lavasmsid
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## Struktur Modular

```text
app/Modules/
├── Academic/
├── Dashboard/
├── Student/
├── Teacher/
├── Website/
├── Attendance/
├── Grade/
├── Finance/
├── PPDB/
├── Internship/
├── Alumni/
├── BKK/
├── Communication/
└── Report/
```

## Roadmap Tahap Berikutnya

### Tahap 2 — Admin Panel Core

- Selesaikan komponen table, form, modal, toast, badge, empty state, dan loading state.
- Buat sidebar collapsible dan menu dinamis berdasarkan permission.
- Tambahkan notification dropdown dan user profile.

### Tahap 3 — User Management

- CRUD user.
- CRUD role dan permission.
- User profile.
- Activity log dan audit log.

### Tahap 4 — Master Data

- CRUD tahun ajaran, semester, jurusan, kompetensi, kelas, ruang, mapel.
- CRUD siswa, guru, staff, orang tua/wali.
- Import/export Excel.

### Tahap 5 — Akademik

- Jadwal pelajaran.
- Absensi.
- Nilai.
- Rapor PDF.
- Kalender akademik.

### Tahap 6 — SMK Specialist

- PKL / Prakerin.
- Mitra industri.
- Teaching Factory.
- Sertifikasi kompetensi.
- BKK dan alumni.

### Tahap 7 — Keuangan

- Tagihan.
- Pembayaran.
- Kwitansi PDF.
- Laporan kas.

### Tahap 8 — PPDB

- Form pendaftaran publik.
- Upload berkas.
- Verifikasi.
- Seleksi.
- Konversi menjadi siswa.

### Tahap 9 — Website Publik

- Profil sekolah.
- Jurusan.
- Berita.
- Agenda.
- Galeri.
- Kontak.

### Tahap 10 — Production

- Testing Pest/PHPUnit.
- Queue worker.
- Cache config/route/view.
- Backup database.
- Nginx + PHP-FPM + SSL.

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

## Catatan Teknis

Repo ini adalah scaffold tahap awal. Beberapa file tambahan seperti `vite.config.js`, `tailwind.config.js`, model guru, dan view guru perlu dilanjutkan oleh AI CLI/local development apabila konektor GitHub menolak commit file tertentu.

## Prinsip Keamanan

- Auth wajib untuk semua admin route.
- Permission wajib untuk setiap modul.
- Validasi Form Request untuk semua form.
- File upload harus validasi MIME dan size.
- Data sensitif wajib memakai policy.
- Gunakan `.env` aman di production.
