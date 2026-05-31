---
name: Laravel Module CRUD Generator
description: Use this skill when creating CRUD modules for LavaSMSID with Controller, Model, Service, Repository, Request, Policy, routes, views, permissions, search, filter, pagination, import, export, and soft delete.
---

# Laravel Module CRUD Generator

Generate CRUD modules for LavaSMSID using Hybrid Modular Monolith rules.

## Required CRUD Files

Every CRUD must include Controller, Model, Service, Repository, Store Request, Update Request, Policy, Action classes when needed, routes.php, index view, create view, edit view, show view, migration, seeder if needed, and permission mapping.

## Standard Module Structure

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

## Standard View Structure

```text
resources/views/modules/module-name/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
├── show.blade.php
└── partials/
    ├── form.blade.php
    ├── filters.blade.php
    └── table.blade.php
```

## CRUD Features

Each CRUD must support index, create, store, show, edit, update, destroy, search, filter, sorting, pagination, empty state, success toast, error state, permission check, validation, and soft delete when needed.

## Import Export

For large master data, support Import Excel, Export Excel, and Export PDF if needed.

Important modules that need import/export: Student, Teacher, Staff, Subject, Classroom, Finance, PPDB, Attendance, Grade, Alumni, and Report.

## Controller Rule

Controller must only receive request, call Form Request, call Service or Action, and return view, redirect, JSON, or file download.

No heavy business logic in controller.

## Service Rule

Service handles business logic, database transactions, calling repository, dispatching events/jobs, calling notifications, and audit logs.

## Repository Rule

Repository handles search, filter, sorting, pagination, complex query, and report query.

## Request Rule

Form Request handles validation rules, attribute names, authorization when needed, and input normalization when needed.

## Policy Rule

Policy handles viewAny, view, create, update, delete, restore, forceDelete, export, import, print, approve, and verify.

## Permission Pattern

Use:

- module.view
- module.create
- module.update
- module.delete
- module.export
- module.import
- module.approve
- module.verify
- module.print

## Required Validation

After creating CRUD, run:

```bash
php artisan route:list
php artisan migrate:fresh --seed
npm run build
php artisan test
```

## Manual Test Checklist

For each CRUD: open index page, create data, edit data, show detail, delete data, search data, filter data, check pagination, check unauthorized access, check validation error, and check success notification.
