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

## Fresh VPS deployment

For a full production deployment on a fresh VPS, follow the steps in `DEPLOYMENT.md`. Key commands:

```bash
git clone https://github.com/arpayid/lavasmsid.git
cd lavasmsid
cp .env.production.example .env
# edit .env with production values
docker compose build
docker compose up -d
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

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

## Quality checks and CI/CD

The project runs automated quality checks on every push and pull request via GitHub Actions:

| Check | Command | Description |
|-------|---------|-------------|
| Lint | `composer lint` | `pint --test` — checks Blade/PHP style (blocking) |
| Format | `composer format` | `pint` — auto-fixes style |
| Analyse | `composer analyse` | `phpstan analyse` — static analysis (level 1) |
| Test | `composer test` | `php artisan test` — runs test suite |
| Audit | `composer audit` | checks for known security advisories |
| Validate | `composer validate --strict` | validates `composer.json` |
| Build | `npm run build` | builds frontend assets via Vite |

```bash
# Run all quality checks locally (via Docker)
docker compose exec app composer validate --strict
docker compose exec app composer audit
docker compose exec app composer lint
docker compose exec app composer analyse
docker compose exec app composer test
docker compose exec app npm ci
docker compose exec app npm run build
```

## Verification commands

After deployment, verify the application:

```bash
docker compose ps
docker compose exec app php artisan about
docker compose exec app php artisan route:list
docker compose exec app php artisan test
docker compose exec app composer audit
docker compose exec app vendor/bin/pint --test
docker compose exec app vendor/bin/phpstan analyse
```

## Production environment checklist

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generated and never leaked
- [ ] `DB_PASSWORD` and `MYSQL_ROOT_PASSWORD` are strong, unique values
- [ ] `CORS_ALLOWED_ORIGINS` set to specific allowed domains
- [ ] HTTPS/SSL enabled via reverse proxy
- [ ] `php artisan storage:link` executed
- [ ] `php artisan config:cache` executed
- [ ] `php artisan route:cache` executed
- [ ] `php artisan view:cache` executed
- [ ] Queue worker container is running
- [ ] Scheduler container is running
- [ ] Database backup configured and tested
- [ ] MySQL port not exposed publicly
- [ ] `.env` file permissions restricted (`chmod 600`)

## Security notes

- Never commit `.env` files, database dumps, or credentials to Git.
- Rotate `APP_KEY` immediately if it is ever exposed: `docker compose exec app php artisan key:generate --force`
- Rotate `DB_PASSWORD` and `MYSQL_ROOT_PASSWORD` if they are ever exposed.
- Restrict `CORS_ALLOWED_ORIGINS` to only the domains that need access.
- Keep `APP_DEBUG=false` in production to prevent information disclosure.
- Do not expose MySQL (port 3306) or Redis (port 6379) to the public internet.

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
- `CHANGELOG.md` — changelog.
- `docs/ARCHITECTURE.md` — architecture details.
- `docs/HYBRID_MODULAR_MONOLITH.md` — architecture model.
