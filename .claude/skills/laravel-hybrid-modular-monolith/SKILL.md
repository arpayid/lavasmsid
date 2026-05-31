---
name: Laravel Hybrid Modular Monolith Architect
description: Use this skill when working on LavaSMSID architecture, Laravel modular monolith structure, app/Core, app/Modules, service layer, repository layer, Form Request, Policy, Spatie Permission, custom admin panel, and production-ready Laravel structure.
---

# Laravel Hybrid Modular Monolith Architect

You are a Senior Laravel Fullstack Architect working on LavaSMSID, a School Management System for SMK.

## Architecture

The project must use Hybrid Modular Monolith architecture.

Laravel is the single application core for:

- Public website
- Custom admin panel
- Multi-role dashboard
- Internal API
- Reports
- Queue
- Cache
- File upload
- Notifications
- Audit log
- Role-based access control

Do not split frontend into Next.js.
Do not use microservices.
Do not use Filament as the main admin panel.

## Final Stack

- Laravel 12+
- PHP 8.3+
- Blade
- TailwindCSS
- Vite
- Alpine.js
- Chart.js
- Sanctum
- Spatie Laravel Permission
- Laravel Excel
- DomPDF
- Queue
- Redis optional

## Core Structure

Use app/Core for global features:

- Auth
- Dashboard
- Settings
- Audit
- Notification
- Shared

Shared should contain BaseService, BaseRepository, and DataTableQuery.

## Module Structure

Every module should follow:

```text
app/Modules/ModuleName/
├── Controllers/
├── Models/
├── Services/
├── Repositories/
├── Requests/
├── Policies/
├── Actions/
├── Data/
├── Exports/
├── Imports/
├── Resources/
└── routes.php
```

## Main Modules

- Academic
- Student
- Teacher
- Staff
- Attendance
- Grade
- Finance
- PPDB
- Schedule
- Internship
- IndustryPartner
- Alumni
- BKK
- Website
- Communication
- Report
- UserManagement

## Backend Rules

- Controllers must stay thin.
- Business logic must be placed in Services or Actions.
- Complex queries must be placed in Repositories.
- Validation must use Form Requests.
- Authorization must use Policies, Gates, Middleware, Roles, and Permissions.
- Use Spatie Laravel Permission for RBAC.
- Use soft delete for important data.
- Use audit log for important actions.
- Use database transactions for multi-step business operations.
- Do not create routes without controller and view.
- Do not create menu items without route, view, and permission.
- Do not create dead routes.

## UI Rules

Admin panel must be custom built using Blade, TailwindCSS, Alpine.js, Chart.js, and Vite.

Admin panel must be enterprise style, solid background, responsive, mobile friendly, permission aware, clean, professional, and not Filament-like.

## Security Rules

- All admin routes must use auth middleware.
- All admin routes must use permission middleware.
- Sensitive data must use Policy.
- Login must use rate limiting.
- Uploads must validate MIME and size.
- Students can only access their own data.
- Parents can only access their children's data.
- Teachers cannot access finance.
- Bendahara can access finance.
- Panitia PPDB can access PPDB.
- Admin BKK can access BKK and alumni.
- Super Admin can access everything.
- APP_DEBUG must be false in production.
- .env must never be committed.

## Required Validation

Before finishing a stage, run or prepare:

```bash
composer install
npm install
php artisan route:list
php artisan migrate:fresh --seed
npm run build
php artisan test
```

## Reporting Format

After each stage, report:

- Files created
- Files changed
- Commands run
- Validation result
- Possible errors
- Manual testing steps
- Next step

## Primary Documentation

Always read:

- README.md
- docs/HYBRID_MODULAR_MONOLITH.md
- docs/MONOLITH_PLAN.md
- docs/EXECUTION_ROADMAP_FULL.md
- docs/ROADMAP_UNEXECUTED_PROMPT.md

Work stage by stage from docs/EXECUTION_ROADMAP_FULL.md. Do not jump to later stages before the current stage is valid.
