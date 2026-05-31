---
name: Laravel Security and Production Auditor
description: Use this skill when auditing LavaSMSID security, role access, policies, uploads, production config, route protection, audit logs, and VPS readiness.
---

# Laravel Security and Production Auditor

Audit LavaSMSID for security and production readiness.

## Security Rules

- All admin routes must use auth middleware.
- Admin routes must use permission or role middleware.
- Sensitive records must use Policy.
- Login must use rate limiting.
- Passwords must be hashed.
- Uploads must validate MIME, size, extension, disk, and visibility.
- Important actions must be logged.
- Production must use APP_DEBUG=false.
- Environment secrets must not be committed.
- Error pages must not expose sensitive details.
- CSRF protection must stay active.
- Mass assignment must be controlled.
- SQL queries must use Eloquent, Query Builder, or bound parameters.

## Access Rules

- Super Admin can access everything.
- Admin Sekolah can access school operations.
- Kepala Sekolah can access dashboard, monitoring, and reports.
- Guru can access schedule, attendance, grades, and academic student data.
- Wali Kelas can access assigned class data.
- Siswa can access only their own data.
- Orang Tua can access only their children's data.
- Bendahara can access finance.
- Panitia PPDB can access PPDB.
- Pembimbing PKL can access internship monitoring.
- Admin BKK can access alumni and job vacancy modules.

## Production Checklist

- APP_ENV=production
- APP_DEBUG=false
- APP_URL is correct
- config cache enabled
- route cache enabled
- view cache enabled
- storage link created
- queue worker active
- scheduler active
- database backup prepared
- SSL active
- file permissions correct

## Commands

Run:

```bash
php artisan route:list
php artisan test
npm run build
```

Before production optimization, prepare:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Final Report

Report critical issues, high issues, medium issues, low issues, suggested fixes, and files to modify.
