# LavaSMSID Monolith Plan

Arah resmi arsitektur project ini adalah Hybrid Modular Monolith berbasis Laravel.

Laravel tetap menjadi satu aplikasi utama untuk:

- Website publik
- Admin panel
- Dashboard multi role
- API internal
- Report
- Queue
- Cache
- Upload file

Frontend tidak dipisah ke project lain pada tahap awal. Gunakan Blade, TailwindCSS, Vite, Alpine.js, dan Chart.js.

## Struktur target

```text
app/Core/
├── Auth/
├── Dashboard/
├── Settings/
├── Audit/
├── Notification/
└── Shared/

app/Modules/
├── Academic/
├── Student/
├── Teacher/
├── Staff/
├── Attendance/
├── Grade/
├── Finance/
├── PPDB/
├── Schedule/
├── Internship/
├── IndustryPartner/
├── Alumni/
├── BKK/
├── Website/
├── Communication/
├── Report/
└── UserManagement/
```

## Standar module

```text
Controllers/
Models/
Services/
Repositories/
Requests/
Policies/
Actions/
Data/
Resources/
routes.php
```

## Checklist

- Tambahkan app/Core.
- Tambahkan BaseService.
- Tambahkan BaseRepository.
- Tambahkan module route loader.
- Pisahkan route per module.
- Controller tetap tipis.
- Business logic di Service atau Action.
- Query kompleks di Repository.
- Validasi di Form Request.
- Akses data dilindungi Policy dan Permission.

## Prompt AI CLI

```text
Rapikan LavaSMSID menjadi Hybrid Modular Monolith Laravel. Jangan hapus file yang sudah ada. Tambahkan app/Core, BaseService, BaseRepository, dan module route loader. Setiap module di app/Modules harus punya struktur Controllers, Models, Services, Repositories, Requests, Policies, Actions, Data, Resources, routes.php. Laravel tetap satu core untuk website publik, admin panel, dashboard role, API internal, report, queue, cache, dan upload file. Pastikan route:list, migrate:fresh --seed, npm run build, dan test berhasil.
```
