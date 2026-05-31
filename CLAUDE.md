# LavaSMSID Claude Instructions

Project: LavaSMSID — SMK Management System Professional

## Architecture

Hybrid Modular Monolith Laravel.

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

## Admin Panel

Use Custom Laravel Blade + TailwindCSS + Alpine.js.

Do not use Filament as the main admin panel.

## Main Documentation

Always read:

- README.md
- docs/HYBRID_MODULAR_MONOLITH.md
- docs/MONOLITH_PLAN.md
- docs/EXECUTION_ROADMAP_FULL.md
- docs/ROADMAP_UNEXECUTED_PROMPT.md

## Required Skills

Use relevant skills from:

- .claude/skills/laravel-hybrid-modular-monolith
- .claude/skills/custom-admin-panel
- .claude/skills/module-crud-generator
- .claude/skills/database-migration-seeder
- .claude/skills/security-production-auditor
- .claude/skills/testing-engineer
- .claude/skills/vps-deployment-engineer

## Global Rules

- Start from docs/EXECUTION_ROADMAP_FULL.md.
- Work stage by stage.
- Do not delete existing files without clear reason.
- Keep controllers thin.
- Put business logic in Service or Action.
- Put complex queries in Repository.
- Use Form Request for validation.
- Use Policy and Spatie Permission for authorization.
- Use custom Blade admin panel, not Filament.
- Every menu must have route, controller, view, and permission.
- Every admin route must use auth and permission middleware.
- Every upload must validate MIME and size.
- Sensitive data must be protected by policy.
- Important actions must be logged to audit log.

## Required Validation

After every major stage, run:

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

```text
TAHAP SELESAI: <stage name>

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

## First Task

Begin from Tahap 0 — Validasi Foundation Laravel.

Do not continue to the next stage until Tahap 0 is valid.
