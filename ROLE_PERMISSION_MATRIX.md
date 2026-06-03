# Role Permission Matrix — LavaSMSID

Dokumen ini merangkum akses umum. Implementasi final tetap mengikuti permission yang dikonfigurasi di aplikasi.

| Role | Fokus Akses | Catatan |
|---|---|---|
| Super Admin | Semua modul, user, role, permission, audit, konfigurasi global | Gunakan hanya untuk administrasi tingkat tertinggi. |
| Admin Sekolah | Operasional sekolah, master data, CMS, laporan | Tidak harus memiliki seluruh permission Super Admin. |
| Kepala Sekolah | Dashboard eksekutif dan laporan | Umumnya read-only untuk data strategis. |
| Waka Kurikulum | Akademik, jadwal, nilai, rapor, kalender | Akses sesuai tanggung jawab kurikulum. |
| Waka Kesiswaan | Siswa, absensi, kegiatan, pembinaan | Batasi perubahan data sensitif bila tidak diperlukan. |
| Guru | Jadwal, absensi, nilai, kelas ajar | Hanya data yang menjadi tanggung jawabnya. |
| Wali Kelas | Data kelas, siswa, absensi, nilai ringkas | Akses terbatas pada kelas binaan. |
| Siswa | Portal pribadi, jadwal, nilai, absensi, tagihan | Read-only untuk mayoritas data. |
| Orang Tua / Wali | Data siswa terhubung, absensi, nilai, tagihan | Tidak boleh melihat siswa lain. |
| Bendahara | Tagihan, pembayaran, laporan finance | Perlu kontrol ketat dan audit. |
| Staff TU | Administrasi data sekolah dan master data tertentu | Sesuaikan dengan SOP sekolah. |
| Panitia PPDB | Pendaftar, verifikasi, seleksi, konversi | Aktif sesuai periode PPDB. |
| Pembimbing PKL | Penempatan, monitoring, logbook, nilai PKL | Terbatas pada siswa bimbingan. |
| Admin BKK | Alumni, tracer study, lowongan, mitra | Validasi data sebelum publikasi. |

## Pola Permission

Gunakan pola berikut per modul bila tersedia:

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

## Prinsip Akses

- Terapkan least privilege.
- Pisahkan akses baca, ubah, hapus, verifikasi, approve, dan export.
- Audit perubahan penting, terutama user, permission, finance, PPDB, dan data pribadi.
- Review akses secara berkala atau saat pergantian personel.
