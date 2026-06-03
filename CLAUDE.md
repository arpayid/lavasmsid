# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## High-Level Architecture

LavaSMSID employs a **Hybrid Modular Monolith Laravel** architecture. This means the entire application resides within a single Laravel project, with features modularized by business domain. Laravel serves as the core for the public website, custom admin panel, multi-role dashboards, internal APIs, reports, queues, caches, and file uploads.

**Key Architectural Principles:**
-   **Core Modules**: Global logic resides in `app/Core` (Auth, Dashboard, Settings, Audit, Notification, Shared base classes).
-   **Business Modules**: Domain-specific features are organized in `app/Modules` (e.g., `Academic`, `Student`, `Finance`, `PPDB`).
-   **Thin Controllers**: Controllers primarily handle requests, delegate business logic to Services/Actions, and return responses.
-   **Service Layer**: Contains core business logic, orchestrates repositories/models, handles database transactions, and manages queues/events. New services should be created in `app/Services/` or `app/Modules/{ModuleName}/Services/`.
-   **Repository Layer**: Encapsulates complex queries, search, filter, sorting, and pagination logic.
-   **Action Classes**: Handle specific use cases (e.g., `ConvertPpdbToStudentAction`).
-   **Form Requests**: Used for input validation and light authorization.
-   **Policies & Spatie Permission**: Essential for robust authorization, protecting sensitive data, and ensuring role-based access control.
-   **Frontend**: Built with Blade, TailwindCSS, Vite, Alpine.js, and Chart.js. No separate frontend project at this stage.
-   **Database**: Single primary database, with tables organized by domain.

## Common Commands

**Development:**
-   **Install dependencies**:
    ```bash
    composer install
    npm install
    ```
-   **Generate application key**:
    ```bash
    php artisan key:generate
    ```
-   **Run migrations and seeders (fresh database)**:
    ```bash
    php artisan migrate:fresh --seed
    ```
-   **List all routes**:
    ```bash
    php artisan route:list
    ```
-   **Run Vite development server**:
    ```bash
    npm run dev
    ```
-   **Build frontend assets for production**:
    ```bash
    npm run build
    ```
-   **Run tests**:
    ```bash
    php artisan test
    ```
-   **Run a single test file/case**:
    ```bash
    php artisan test --filter "YourTestClass"
    php artisan test "tests/Feature/YourFeatureTest.php"
    ```
-   **Lint code with Laravel Pint**:
    ```bash
    vendor/bin/pint
    ```
-   **Start local development server**:
    ```bash
    php artisan serve --host=0.0.0.0 --port=8000
    ```

**Production (VPS):**
-   **Install dependencies (production)**:
    ```bash
    composer install --no-dev --optimize-autoloader
    npm ci
    npm run build
    ```
-   **Run migrations (production)**:
    ```bash
    php artisan migrate --force
    ```
-   **Link storage**:
    ```bash
    php artisan storage:link
    ```
-   **Cache configurations**:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
-   **Restart queue workers**:
    ```bash
    php artisan queue:restart
    ```

## Phase Status

Phase 13 documentation is finalized for final audit. Keep future changes documentation-only unless a separate task explicitly asks for application code changes.

## Important Documentation

Always refer to these root documentation files for detailed information:
-   `README.md`
-   `DEPLOYMENT.md`
-   `ADMIN_GUIDE.md`
-   `USER_GUIDE.md`
-   `ROLE_PERMISSION_MATRIX.md`
-   `BACKUP_RESTORE.md`
-   `CHANGELOG.md`
-   `RELEASE_NOTES.md`

## Global Rules

-   Follow the active task scope and keep repository documentation aligned with implemented features.
-   Do not delete existing files without a clear reason.
-   Keep controllers thin; business logic belongs in Service or Action classes.
-   Complex queries belong in Repository classes.
-   Use Form Requests for input validation.
-   Implement authorization using Policies and Spatie Permission.
-   All admin routes must use `auth` and `permission` middleware.
-   All file uploads require MIME and size validation.
-   Sensitive data must be protected by policies.
-   Important actions must be logged to an audit log.
-   Data that needs to be preserved should use soft deletes.
-   Every menu item must have a corresponding route, controller, view, and permission.
-   Ensure all tests pass (`php artisan test`) and frontend assets build successfully (`npm run build`) after major changes.

## Custom Skills

Utilize the custom skills located in `.claude/skills/` for specialized tasks:
-   `laravel-hybrid-modular-monolith`
-   `custom-admin-panel`
-   `module-crud-generator`
-   `database-migration-seeder`
-   `security-production-auditor`
-   `testing-engineer`
-   `vps-deployment-engineer`
