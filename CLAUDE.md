# CLAUDE.md

This file provides guidance to Claude Code when working in this repository.

## High-level architecture

LavaSMSID uses a Hybrid Modular Monolith Laravel architecture. The public website, custom admin panel, dashboards, internal APIs, reports, queues, cache, and file uploads live in one Laravel application.

Key rules:

- Global logic belongs in `app/Core`, `app/Services`, or shared support classes.
- Domain features belong in `app/Modules`.
- Controllers stay thin and delegate business logic to Services or Actions.
- Complex queries belong in Repositories.
- Validation uses Form Requests.
- Authorization uses Policies, Gates, middleware, roles, and Spatie Permission.
- Frontend is Blade, Tailwind CSS, Vite, Alpine.js, and Chart.js.
- Do not create a separate frontend application unless explicitly requested.

## Default operational context

Docker Compose and Portainer are the default operational context. Prefer container commands over legacy host-service instructions.

Use `docker compose exec app ...` for Laravel, Composer, and NPM commands.

## Common Docker commands

```bash
docker compose build
docker compose up -d
docker compose ps
docker compose logs -f
docker compose logs -f app
docker compose exec app bash
docker compose down
```

## Laravel commands inside the app container

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app php artisan route:list
docker compose exec app php artisan about
docker compose exec app php artisan optimize:clear
```

Development-only database reset:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Never recommend `migrate:fresh --seed` for production data.

## Frontend and tests inside the app container

```bash
docker compose exec app npm install
docker compose exec app npm run build
docker compose exec app php artisan test
docker compose exec app ./vendor/bin/pint --test
```

## Deployment documentation

Use these Docker/Portainer-first documents:

- `DOCKER_PORTAINER_GUIDE.md` for Docker Compose and Portainer operations.
- `DEPLOYMENT.md` for production-safe Docker/Portainer deployment.

Do not promote legacy host-service deployment as the primary path. If a server is mentioned, assume Docker is installed on that server and the application runs through the Compose stack.

## Global coding rules

- Follow the active task scope.
- Do not delete existing Laravel source/runtime files without an explicit task.
- Do not change business logic unless the user asks for implementation work.
- Keep controllers thin.
- Keep reusable business logic in Services, Actions, or Repositories.
- Use Form Requests for validation.
- Protect admin routes with auth and permission middleware.
- Validate all uploads by MIME and size.
- Protect sensitive data with policies.
- Log important actions to an audit log where supported.
- Use soft deletes for data that must be preserved.
- Every menu item should have a route, controller, view, and permission.
- After major changes, prefer containerized validation with `docker compose exec app php artisan test` and `docker compose exec app npm run build`.

## Custom skills

The `.claude/skills/` directory may contain historical or specialized skills. Use only skills that match the current Docker/Portainer-first direction and the user's task. Do not use non-Docker deployment guidance unless the user explicitly asks for it.
