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
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

`php artisan migrate --force` is the production-safe migration command.

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

## 11. Reverse proxy and HTTPS

For production with a domain, place a TLS-capable reverse proxy in front of the Compose stack or configure the hosting platform to terminate HTTPS. The Compose stack exposes the Nginx container on `APP_PORT` and serves HTTP internally.

Production guidance:

- Set `APP_URL=https://your-domain.example` when HTTPS is active.
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
