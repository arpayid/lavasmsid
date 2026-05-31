---
name: Laravel Custom Admin Panel Builder
description: Use this skill when building LavaSMSID custom admin panel using Laravel Blade, TailwindCSS, Alpine.js, Chart.js, reusable components, sidebar, topbar, dashboard, tables, forms, modals, toast, and permission-aware menus.
---

# Laravel Custom Admin Panel Builder

Build the LavaSMSID admin panel using custom Laravel Blade, TailwindCSS, Alpine.js, and Chart.js.

Do not use Filament as the main admin panel.

## Admin Panel Strategy

The admin panel must be a custom enterprise dashboard using Laravel Blade, TailwindCSS, Alpine.js, Chart.js, Vite, and Spatie Laravel Permission.

## Required Layout Files

- resources/views/layouts/admin.blade.php
- resources/views/admin/dashboard.blade.php
- resources/views/admin/profile.blade.php
- resources/views/admin/settings/index.blade.php

## Required Components

Create reusable components:

- sidebar
- topbar
- breadcrumb
- stat card
- data table
- search input
- filter dropdown
- pagination
- form input
- form select
- form textarea
- file upload
- badge
- modal
- toast
- empty state
- error state
- loading state
- notification dropdown

## UI Style

Use enterprise dashboard style, solid background, clean white/slate surfaces, slate/indigo/blue/emerald color system, rounded cards, soft shadow, clean tables, mobile-first responsive layout, professional typography, consistent spacing, clear action buttons, and permission-aware menus.

Avoid excessive glassmorphism, overly transparent panels, random colors, unclear icons, dead menu items, and Filament-looking UI.

## Sidebar Rules

Sidebar must support Dashboard, Master Data, Academic, Attendance, Grade, Finance, PPDB, Internship, BKK, Alumni, Website CMS, Communication, Report, User Management, and Settings.

Every sidebar item must have route, permission, icon, active state, and mobile behavior.

## Menu Permission Rules

Examples:

- student.view shows Data Siswa
- teacher.view shows Data Guru
- finance.view shows Keuangan
- ppdb.view shows PPDB
- report.view shows Laporan
- user.view shows User Management

Do not show menus to unauthorized roles.

## Dashboard Rules

Dashboard must be role-aware.

Super Admin dashboard includes total students, teachers, staff, PPDB registrations, attendance summary, finance summary, recent activities, and quick actions.

Kepala Sekolah dashboard includes academic summary, attendance summary, finance summary, teacher performance, and student performance.

Guru dashboard includes teaching schedule, assigned classes, attendance input, grade input, and announcements.

Siswa dashboard includes schedule, attendance summary, grades, invoices, and announcements.

Orang Tua dashboard includes child attendance, child grades, child invoices, and announcements.

Bendahara dashboard includes payments today, unpaid invoices, income chart, and finance report shortcut.

Panitia PPDB dashboard includes total applicants, verified applicants, accepted applicants, and rejected applicants.

## Form Rules

Every form must include CSRF, validation error display, old input value, required label marker, help text if needed, submit button, cancel/back button, and loading state if possible.

## Table Rules

Every table must include search, filter, pagination, empty state, action buttons, permission-aware buttons, status badge, and responsive horizontal scroll on mobile.

## Required Validation

Run:

```bash
php artisan route:list
npm run build
```

## Final Instruction

Make the admin panel look professional, not like a basic CRUD generator.
