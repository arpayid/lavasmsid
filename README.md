# LavaSMSID

LavaSMSID is a Laravel-based school management system for SMK operations. The project uses a Hybrid Modular Monolith architecture: one Laravel application contains the public website, admin panel, dashboards, internal APIs, reports, queues, cache, and file uploads.

Docker Compose and Portainer are the primary deployment path for this repository. Legacy host-service deployment is not the documented primary path.

## Stack

- Laravel 12
- PHP-FPM container
- MySQL 8 container
- Redis container
- Nginx container
- Docker Compose
- Portainer Community Edition
- Blade, Tailwind CSS, Vite, Alpine.js, and Chart.js
- Spatie Laravel Permission
- PHPUnit/Pest-compatible Laravel tests

## Repository deployment files

- `Dockerfile` — application PHP-FPM image.
- `docker-compose.yml` — Compose stack for app, Nginx, MySQL, Redis, worker, and scheduler.
- `.env.docker.example` — Docker development environment example.
- `.env.production.example` — Docker/Portainer production environment example with placeholders.
- `docker/nginx/default.conf` — Nginx container configuration.
- `docker/php/local.ini` — PHP container configuration.
- `DOCKER_PORTAINER_GUIDE.md` — detailed Docker and Portainer guide.
- `DEPLOYMENT.md` — production-safe Docker/Portainer deployment notes.

## Docker-first quick start

```bash
git clone https://github.com/arpayid/lavasmsid.git
cd lavasmsid
cp .env.docker.example .env
docker compose build
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

Open the application at:

```text
http://SERVER-IP:8080
```

For local development, use:

```text
http://localhost:8080
```

If `APP_PORT` is changed in `.env` or Portainer variables, use that port instead.

## Portainer deployment summary

1. Open Portainer and choose the target Docker environment.
2. Go to **Stacks** and select **Add stack**.
3. Use **Git Repository** for the simplest deployment flow.
4. Set Repository URL to `https://github.com/arpayid/lavasmsid.git`.
5. Set branch to `main` or the intended release branch.
6. Set Compose path to `docker-compose.yml`.
7. Provide environment variables from `.env.docker.example` for development or `.env.production.example` for production.
8. Deploy the stack.
9. Open the `app` container console and run first-time setup commands.

See `DOCKER_PORTAINER_GUIDE.md` for detailed Portainer steps and `DEPLOYMENT.md` for production-safe Docker/Portainer notes.

## First-time setup commands

Run these from the repository root on a Docker host, or through the `app` container console in Portainer:

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

For development resets only, you may use:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Do not run `migrate:fresh --seed` against production data.

## Common Docker commands

```bash
docker compose ps
docker compose logs -f
docker compose logs -f app
docker compose exec app bash
docker compose exec app php artisan about
docker compose exec app php artisan route:list
docker compose down
docker compose up -d --build
```

## Testing and build inside the container

```bash
docker compose exec app php artisan test
docker compose exec app npm run build
docker compose exec app ./vendor/bin/pint --test
```

## Architecture

- Laravel remains the single core application.
- Public website and admin panel live in the same project.
- Major features are organized by domain under `app/Modules`.
- Shared application logic belongs in `app/Core`, `app/Services`, or module-specific service layers.
- Controllers should stay thin; business logic belongs in Services or Actions.
- Authorization uses Policies, Gates, middleware, roles, and permissions.

## Main modules

- Website public pages
- Admin dashboard
- User and role management
- Master data
- Academic management
- Attendance
- Grades and reports
- Finance
- PPDB
- PKL, BKK, alumni, and industry partners
- Communication and notifications
- Operational reports

## Documentation

- `DOCKER_PORTAINER_GUIDE.md` — Docker Compose and Portainer operations.
- `DEPLOYMENT.md` — production-safe Docker/Portainer deployment.
- `ADMIN_GUIDE.md` — admin operations.
- `USER_GUIDE.md` — user guide.
- `ROLE_PERMISSION_MATRIX.md` — roles and permissions.
- `BACKUP_RESTORE.md` — backup/restore concepts.
- `docs/ARCHITECTURE.md` — architecture details.
- `docs/HYBRID_MODULAR_MONOLITH.md` — architecture model.
