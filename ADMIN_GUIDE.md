# Admin Guide — LavaSMSID

Panduan ini ditujukan untuk **Super Admin** dan **Admin Sekolah** yang mengelola operasional LavaSMSID melalui panel admin.

## 1. Peran Admin

- **Super Admin**: mengelola konfigurasi global, user, role, permission, seluruh master data, dan audit operasional.
- **Admin Sekolah**: mengelola data sekolah, konten, data akademik, PPDB, keuangan, PKL, BKK/alumni, komunikasi, dan laporan sesuai permission.

## 2. Akses Panel Admin

1. Buka URL aplikasi sekolah yang sudah dikonfigurasi pada `APP_URL`.
2. Login menggunakan akun resmi yang dibuat oleh pengelola sistem.
3. Masuk ke menu admin sesuai role dan permission pengguna.
4. Setelah selesai bekerja, gunakan tombol logout dan jangan meninggalkan sesi aktif di perangkat bersama.

> Jangan menulis atau membagikan kredensial admin di dokumen, chat, tiket, atau catatan publik.

## 3. Dashboard Admin

Dashboard admin digunakan untuk memantau ringkasan operasional, seperti:

- jumlah siswa, guru, staff, kelas, dan jurusan;
- status PPDB;
- ringkasan tagihan dan pembayaran;
- rekap akademik dan absensi;
- notifikasi penting;
- aktivitas terbaru atau audit log.

Gunakan dashboard sebagai titik awal untuk mendeteksi data belum lengkap, proses tertunda, atau aktivitas yang perlu ditindaklanjuti.

## 4. Pengaturan Identitas Sekolah

Menu pengaturan identitas sekolah menjaga konsistensi informasi website publik, dokumen, dan tampilan admin. Pastikan data berikut terisi benar:

- **Nama sekolah**: nama resmi satuan pendidikan.
- **Tagline**: slogan singkat yang tampil pada area publik.
- **Kepala sekolah**: nama pejabat aktif sesuai dokumen resmi.
- **Sambutan**: teks sambutan kepala sekolah untuk website publik.
- **Sejarah**: ringkasan sejarah sekolah yang faktual.
- **Visi/misi**: visi dan misi resmi sekolah.
- **Alamat**: alamat lengkap sekolah.
- **Telepon**: nomor kontak resmi sekolah.
- **Email**: email resmi sekolah.
- **Website URL**: URL website sekolah yang valid.
- **Sosial media**: tautan kanal resmi sekolah.
- **Logo**: unggah logo resmi dengan format dan ukuran yang sesuai validasi aplikasi.
- **Favicon**: unggah ikon kecil untuk browser.

Setelah perubahan identitas, periksa tampilan website publik dan dokumen yang menggunakan data tersebut.

## 5. User Management, Role, dan Permission

- Buat user hanya untuk personel yang memiliki kebutuhan akses jelas.
- Tetapkan role berdasarkan fungsi kerja, bukan jabatan informal.
- Berikan permission minimum yang diperlukan untuk tugas pengguna.
- Nonaktifkan atau ubah akses user yang mutasi, tidak aktif, atau tidak lagi berwenang.
- Tinjau role dan permission secara berkala, terutama untuk modul keuangan, PPDB, dan data pribadi siswa.

## 6. Master Data

Master data menjadi dasar modul lain. Pastikan data berikut lengkap sebelum operasi rutin:

- tahun ajaran dan semester aktif;
- jurusan, kompetensi keahlian, kelas, rombel, dan ruang;
- mata pelajaran dan kalender akademik;
- data guru, staff, siswa, orang tua/wali, dan mitra industri;
- kategori pembayaran dan referensi lain yang dipakai modul operasional.

Hindari duplikasi data. Jika ada kesalahan, koreksi melalui fitur edit resmi agar audit tetap tercatat.

## 7. People Management

Kelola data orang dengan prinsip akurasi dan privasi:

- lengkapi profil siswa, guru, staff, dan orang tua/wali;
- validasi nomor induk, kontak, status aktif, dan relasi kelas;
- perbarui status siswa lulus, pindah, keluar, atau aktif sesuai dokumen resmi;
- batasi akses data pribadi hanya untuk role yang membutuhkan.

## 8. Academic Operation

Operasi akademik mencakup jadwal, absensi, nilai, rapor, kalender akademik, kenaikan kelas, dan kelulusan. Admin perlu memastikan:

- tahun ajaran dan semester aktif benar;
- kelas, wali kelas, mapel, dan guru pengampu sudah terhubung;
- jadwal tidak bentrok;
- input absensi dan nilai mengikuti jadwal sekolah;
- laporan akademik diekspor hanya oleh pengguna berwenang.

## 9. PPDB

Untuk PPDB online:

- atur periode pendaftaran dan pilihan jurusan;
- pantau pendaftar baru dan kelengkapan berkas;
- verifikasi data berdasarkan dokumen resmi;
- ubah status seleksi secara hati-hati;
- konversi pendaftar diterima menjadi siswa aktif hanya setelah keputusan final.

Simpan pengumuman dan data PPDB sesuai kebijakan retensi sekolah.

## 10. Finance

Modul keuangan berisi tagihan, pembayaran, cicilan, kategori pembayaran, dan laporan kas. Praktik aman:

- batasi akses finance ke role yang berwenang;
- verifikasi nominal sebelum menyimpan transaksi;
- gunakan status pembayaran secara konsisten;
- lakukan rekonsiliasi berkala dengan catatan bendahara;
- ekspor laporan hanya untuk kebutuhan resmi.

## 11. Internship / PKL

Kelola PKL dengan data yang terstruktur:

- mitra industri dan pembimbing industri;
- guru pembimbing;
- penempatan siswa;
- logbook dan monitoring;
- penilaian PKL dan status penyelesaian.

Pastikan data kontak mitra industri tetap akurat dan tidak dibagikan ke pihak tidak berwenang.

## 12. BKK / Alumni

Modul BKK dan alumni digunakan untuk tracer study, data alumni, lowongan kerja, mitra perusahaan, dan penyerapan lulusan. Admin perlu:

- memperbarui status alumni bekerja, kuliah, wirausaha, atau lainnya;
- memverifikasi lowongan sebelum dipublikasikan;
- menjaga data kontak alumni;
- membuat laporan penyerapan alumni sesuai kebutuhan sekolah.

## 13. Website CMS

Website CMS mengelola konten publik seperti berita, agenda, galeri, profil sekolah, jurusan, fasilitas, prestasi, PPDB, dan kontak. Prinsip publikasi:

- gunakan judul dan isi yang jelas;
- unggah media yang relevan dan legal digunakan;
- periksa ejaan dan tautan sebelum publikasi;
- arsipkan konten yang tidak berlaku;
- hindari mempublikasikan data pribadi tanpa dasar yang sah.

## 14. Communication / Notification

Gunakan komunikasi dan notifikasi untuk pengumuman resmi, pesan internal, broadcast, dan pengingat operasional. Pastikan:

- target penerima benar;
- isi pesan singkat, sopan, dan jelas;
- informasi sensitif tidak dikirim ke penerima yang salah;
- riwayat notifikasi ditinjau bila ada laporan kesalahan.

## 15. Reporting / Export

Laporan digunakan untuk pemantauan dan administrasi. Admin harus:

- memilih filter tanggal, kelas, jurusan, atau status dengan benar;
- menggunakan export CSV streaming untuk data operasional yang besar;
- membatasi distribusi file laporan;
- menghapus file laporan lokal jika tidak lagi diperlukan;
- mencocokkan laporan penting dengan sumber data sebelum keputusan resmi.

## 16. Checklist Admin Harian

- [ ] Login berhasil dan dashboard normal.
- [ ] Notifikasi penting sudah ditinjau.
- [ ] Data PPDB baru atau tertunda sudah dicek.
- [ ] Input akademik penting sudah dipantau.
- [ ] Transaksi finance tertunda sudah diverifikasi.
- [ ] Konten publik baru sudah ditinjau sebelum dipublikasikan.
- [ ] Tidak ada user atau role mencurigakan.
- [ ] Backup dan log operasional dipantau sesuai jadwal.

## 17. Best Practices Keamanan Admin

- Gunakan password kuat dan unik untuk setiap akun.
- Jangan berbagi akun admin.
- Jangan menyimpan password database atau akun admin di crontab, dokumen publik, atau repository.
- Aktifkan HTTPS di production.
- Gunakan perangkat tepercaya untuk mengakses panel admin.
- Logout setelah selesai bekerja.
- Terapkan prinsip least privilege untuk semua role.
- Tinjau audit log setelah perubahan besar.
- Validasi file upload dan hapus media yang tidak sah.
- Laporkan aktivitas mencurigakan ke pengelola sistem.
