# Release Notes — LavaSMSID

## Phase 13 Documentation Final Patch

Rilis dokumentasi ini menyiapkan referensi operasional production untuk admin sekolah, pengguna, deployment, backup/restore, role/permission, dan audit akhir dokumentasi.

## Highlight

- Panduan Super Admin dan Admin Sekolah tersedia di `ADMIN_GUIDE.md`.
- Panduan pengguna multi-role tersedia di `USER_GUIDE.md`.
- Matriks ringkas role dan permission tersedia di `ROLE_PERMISSION_MATRIX.md`.
- Prosedur backup dan restore aman tersedia di `BACKUP_RESTORE.md`.
- README diselaraskan agar merujuk dokumen root yang benar-benar ada.

## Catatan Keamanan

Dokumentasi production menekankan penggunaan HTTPS, perlindungan secret, backup aman, dan pembatasan akses berbasis role/permission. Untuk hardening HTTP response, gunakan header keamanan aktual berikut sesuai konfigurasi web server atau middleware aplikasi:

- `X-Frame-Options`
- `X-Content-Type-Options`
- `Referrer-Policy`
- `Permissions-Policy`

Hindari menyebut perlindungan keamanan secara terlalu umum. Catat kontrol yang benar-benar dikonfigurasi dan dapat diverifikasi.

## Batasan Rilis

- Patch ini hanya mengubah dokumentasi.
- Tidak ada perubahan PHP, Blade, route, migration, seeder, test, atau package manager file.
- Kredensial, domain production, password, dan secret key tidak dicantumkan.
