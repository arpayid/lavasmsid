# Arsitektur LavaSMSID

LavaSMSID memakai pendekatan Hybrid Modular Architecture:

- Laravel sebagai core aplikasi fullstack.
- Frontend publik dan admin panel berada dalam satu aplikasi Laravel Blade.
- Domain besar dipisahkan ke `app/Modules`.
- Controller dibuat tipis.
- Business logic ditempatkan di Service Layer.
- Query kompleks dapat dipindahkan ke Repository Layer.
- Validasi memakai Form Request.
- Authorization memakai Spatie Permission, policy, middleware, dan gate.

## Modul Target

- Website
- Dashboard
- UserManagement
- Academic
- Student
- Teacher
- Staff
- Attendance
- Grade
- Schedule
- Finance
- PPDB
- Internship
- Alumni
- BKK
- Communication
- Report

## Standar Implementasi Modul

Setiap modul idealnya memiliki:

```text
Controllers/
Models/
Services/
Repositories/
Requests/
Policies/
Resources/
routes.php
```

## Aturan Kode

- Jangan menaruh business logic berat di controller.
- Semua input form harus divalidasi.
- Semua route admin harus dilindungi auth dan permission.
- Data penting memakai soft delete.
- Upload file harus validasi MIME dan ukuran.
- Semua laporan harus memiliki filter tanggal/tahun ajaran/semester bila relevan.
