---
name: Laravel Database Migration and Seeder Architect
description: Use this skill when designing LavaSMSID database tables, migrations, seeders, factories, foreign keys, indexes, statuses, relationships, and migrate:fresh --seed safety.
---

# Laravel Database Migration and Seeder Architect

Design database schema for LavaSMSID.

## General Rules

- Use foreign keys.
- Use indexes for searchable fields.
- Use timestamps.
- Use soft deletes for important data.
- Use enum/status columns carefully.
- Avoid nullable fields unless needed.
- Use unique constraints when needed.
- Make seeders repeatable.
- Do not break migrate:fresh --seed.
- Do not create duplicate tables.
- Do not create circular foreign keys without reason.
- Use clear table names.
- Use consistent column naming.

## Required Base Tables

- users
- password_reset_tokens
- sessions
- cache
- jobs
- failed_jobs
- personal_access_tokens
- roles
- permissions
- model_has_roles
- model_has_permissions
- role_has_permissions

## School Core Tables

- school_profiles
- academic_years
- semesters
- departments
- competencies
- classrooms
- rooms
- subjects
- extracurriculars
- academic_calendars

## People Tables

- students
- teachers
- staffs
- guardians
- student_guardians

## Academic Tables

- schedules
- schedule_items
- exam_schedules
- classroom_students
- teacher_subjects
- homeroom_teachers
- student_promotions
- student_graduations

## Attendance Tables

- student_attendances
- subject_attendances
- teacher_attendances
- attendance_summaries

## Grade Tables

- grade_components
- grades
- grade_weights
- report_cards
- report_card_items
- attitude_scores
- skill_scores
- knowledge_scores

## Finance Tables

- payment_categories
- payment_types
- invoices
- invoice_items
- payments
- payment_installments
- expenses
- cash_books
- payment_receipts

## PPDB Tables

- ppdb_periods
- ppdb_registrations
- ppdb_documents
- ppdb_verifications
- ppdb_selection_results

## SMK Specialist Tables

- industry_partners
- internships
- internship_students
- internship_supervisors
- internship_logs
- internship_monitorings
- internship_scores
- teaching_factory_projects
- competency_certifications
- ukk_results

## BKK and Alumni Tables

- alumni
- tracer_studies
- companies
- job_vacancies
- job_applications
- alumni_statistics

## Website CMS Tables

- pages
- news
- agendas
- galleries
- gallery_items
- facilities
- achievements
- sliders
- contacts
- seo_metas

## Communication Tables

- announcements
- messages
- message_recipients
- notifications
- notification_logs
- broadcasts

## Audit Tables

- audit_logs
- activity_logs
- login_logs

## Index Rules

Add indexes for NIS, NISN, NIP, email, status, academic_year_id, semester_id, department_id, classroom_id, student_id, teacher_id, date, created_at, and slug.

## Seeder Rules

Seeder must create default Super Admin, default roles, default permissions, school profile placeholder, academic year, semester, departments, competencies, classrooms, payment categories, and example subjects.

Default user:

- Email: admin@lavasmsid.local
- Password: password
- Role: Super Admin

## Migration Validation

Always run:

```bash
php artisan migrate:fresh --seed
```

## Factory Rules

Create factories for User, Student, Teacher, Staff, Classroom, Subject, Invoice, Payment, PPDBRegistration, Internship, and Alumni.

## Final Rule

Never add a migration without considering seeder, model, relationship, and rollback.
