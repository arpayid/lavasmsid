# Docker/Portainer Deployment — LavaSMSID

This is the canonical deployment document for LavaSMSID. The primary deployment path is Docker Compose or Portainer Stack. Legacy host-service process setup is not the primary path for this repository.

## 1. Fresh server requirements

Install these on the deployment server:

- Git
- Docker Engine
- Docker Compose plugin
- Portainer Community Edition, if deploying through the Portainer UI

The application stack itself provides:

- PHP runtime container
- Nginx web container
- MySQL 8 container
- Redis container
- Queue worker container
- Scheduler container

## 2. Clone repository

```bash
git clone https://github.com/arpayid/lavasmsid.git
cd lavasmsid
```

## 3. Environment file

For development:

```bash
cp .env.docker.example .env
```

For production, use `.env.production.example` as a placeholder reference and create a real `.env` or Portainer environment variables. Do not commit real secrets.

Important production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
DB_HOST=mysql
DB_PORT=3306
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CORS_ALLOWED_ORIGINS=https://your-domain.example
```

If deploying without a domain, set `APP_URL=http://SERVER-IP:8080` and adjust `APP_PORT` as needed.

## 4. Build and start stack

```bash
docker compose build
docker compose up -d
```

Check services:

```bash
docker compose ps
docker compose logs -f
```

## 5. First-time application setup

Run these commands after containers are running:

```bash
docker compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

`php artisan migrate --force` is the production-safe migration command. Run the cache commands inside the `app` container after production environment variables are final so Laravel writes optimized files under `bootstrap/cache`.

Frontend assets are pre-built in CI. If you need to rebuild on the server:

```bash
docker compose exec app npm ci
docker compose exec app npm run build
```

Development-only database reset:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Do not use `migrate:fresh --seed` in production because it drops existing data.

## 6. Portainer Stack deployment

Recommended method:

1. Open Portainer.
2. Select the Docker environment.
3. Open **Stacks** and click **Add stack**.
4. Choose **Git Repository**.
5. Repository URL: `https://github.com/arpayid/lavasmsid.git`.
6. Branch: `main` or the intended release branch.
7. Compose path: `docker-compose.yml`.
8. Add environment variables from `.env.docker.example` for development or `.env.production.example` for production.
9. Deploy the stack.
10. Open the `app` container console and run the first-time setup commands.

See `DOCKER_PORTAINER_GUIDE.md` for detailed Portainer instructions.

## 7. Queue worker and scheduler

The Compose stack includes separate containers for background work:

- `worker` runs `php artisan queue:work --sleep=3 --tries=3`.
- `scheduler` runs `php artisan schedule:work`.

For a one-shot scheduler check during troubleshooting, run `docker compose exec app php artisan schedule:run`. Keep the long-running Docker scheduler service on `schedule:work` for normal operation.

Check logs with:

```bash
docker compose logs -f worker
docker compose logs -f scheduler
```

Restart containers after configuration changes:

```bash
docker compose up -d --force-recreate
```

## 8. Volumes and persistence

`docker-compose.yml` defines persistent volumes for:

- `mysql_data` — database files.
- `redis_data` — Redis data.
- `vendor_data` — Composer dependencies inside the stack.
- `node_modules_data` — Node dependencies inside the stack.

Do not run `docker compose down -v` in production unless you intentionally want to remove persistent data volumes.

## 9. Logs

```bash
docker compose logs -f
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f mysql
docker compose logs -f worker
docker compose logs -f scheduler
```

Laravel logs are available inside the app container:

```bash
docker compose exec app tail -f storage/logs/laravel.log
```

## 10. Backup and restore

Backup database regularly before migrations, upgrades, and any destructive maintenance.

Create a database backup from the MySQL container:

```bash
docker compose exec mysql mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > lavasmsid_backup.sql
```

If shell environment variables are not exported on the Docker host, replace them with the non-secret values from your deployment environment. Do not commit generated backups.

Restore a database backup:

```bash
docker compose exec -T mysql mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < lavasmsid_backup.sql
```

Back up uploaded files from the application storage path:

```bash
docker compose exec app tar -czf /tmp/lavasmsid-storage.tar.gz storage/app/public
```

Copy archives off the server according to your infrastructure backup policy.

See `BACKUP_RESTORE.md` for automated backup script examples and restore checklist.

## 11. Reverse proxy and HTTPS

For production with a domain, place a TLS-capable reverse proxy in front of the Compose stack or configure the hosting platform to terminate HTTPS. The Compose stack exposes the Nginx container on `APP_PORT` and serves HTTP internally.

Production guidance:

- Set `APP_URL=https://your-domain.example` when HTTPS is active.
- Set `CORS_ALLOWED_ORIGINS=https://your-domain.example` to restrict cross-origin requests.
- After environment or route changes, refresh Laravel optimization files with `docker compose exec app php artisan config:cache`, `docker compose exec app php artisan route:cache`, and `docker compose exec app php artisan view:cache`; use `docker compose exec app php artisan optimize:clear` first if stale files in `bootstrap/cache` need to be removed.
- Set secure session/cookie values in the real production environment as needed.
- Do not expose MySQL publicly unless there is a specific secured operational need.
- Keep secrets in Portainer environment variables or a protected `.env` file, not in Git.

## 12. Troubleshooting HTTP 500

Check the following:

```bash
docker compose ps
docker compose logs -f app
docker compose logs -f nginx
docker compose exec app php artisan about
docker compose exec app php artisan route:list
docker compose exec app php artisan optimize:clear
docker compose exec app tail -f storage/logs/laravel.log
```

Common causes:

- `APP_KEY` has not been generated.
- Database container is not healthy yet.
- `.env` uses the wrong `DB_HOST`; inside Docker it should be `mysql`.
- Migrations have not been run.
- Storage permissions or `storage:link` are missing.
- Frontend assets have not been built.

## 13. Production environment checklist

Before going live, verify each item:

- [ ] `APP_ENV=production` — disables debug mode.
- [ ] `APP_DEBUG=false` — prevents information disclosure.
- [ ] `APP_KEY` generated via `php artisan key:generate --force`.
- [ ] `DB_PASSWORD` set to a strong, unique value.
- [ ] `MYSQL_ROOT_PASSWORD` set to a strong, unique value.
- [ ] `CORS_ALLOWED_ORIGINS` set to the specific domain(s) that need access.
- [ ] HTTPS/SSL enabled via reverse proxy.
- [ ] `php artisan storage:link` executed — public storage accessible.
- [ ] `php artisan config:cache` executed — config files optimized.
- [ ] `php artisan route:cache` executed — route files optimized.
- [ ] `php artisan view:cache` executed — Blade templates compiled.
- [ ] Queue worker container is running (`docker compose ps`).
- [ ] Scheduler container is running (`docker compose ps`).
- [ ] Database backup configured and tested.
- [ ] MySQL port not exposed publicly.
- [ ] `.env` file permissions restricted (`chmod 600`).

## 14. Verification commands

After deployment, run these to confirm the application is healthy:

```bash
# Check container status
docker compose ps

# Check application configuration
docker compose exec app php artisan about

# Check registered routes
docker compose exec app php artisan route:list

# Run test suite (optional on production, recommended on staging)
docker compose exec app php artisan test

# Security audit
docker compose exec app composer audit

# Code style check
docker compose exec app vendor/bin/pint --test

# Static analysis
docker compose exec app vendor/bin/phpstan analyse
```

## 15. Security notes

- Never commit `.env` files, database dumps, or credentials to Git.
- Rotate `APP_KEY` immediately if it is ever exposed: `docker compose exec app php artisan key:generate --force`
- Rotate `DB_PASSWORD` and `MYSQL_ROOT_PASSWORD` if they are ever exposed.
- Restrict `CORS_ALLOWED_ORIGINS` to only the domains that need access.
- Keep `APP_DEBUG=false` in production to prevent information disclosure.
- Do not expose MySQL (port 3306) or Redis (port 6379) to the public internet.
- Use strong, unique passwords for all database users.
- Regularly review Portainer environment variables and access logs.

## 16. CI/CD quality checks

The project runs the following automated checks on every push and pull request:

| Check | Command | Blocking |
|-------|---------|----------|
| Composer validation | `composer validate --strict` | Yes |
| Security audit | `composer audit` | Yes (blocks on advisories) |
| Code style | `vendor/bin/pint --test` | Yes |
| Static analysis | `vendor/bin/phpstan analyse` (level 1) | Yes |
| Test suite | `php artisan test` | Yes |
| Frontend build | `npm run build` | Yes |
| Docker config | `docker compose config` | Yes |

Composer scripts available locally:

```bash
composer lint      # pint --test (code style check)
composer format    # pint (auto-fix code style)
composer analyse   # phpstan analyse (static analysis)
composer test      # php artisan test (test suite)
```

See `README.md` for local quality check commands.
