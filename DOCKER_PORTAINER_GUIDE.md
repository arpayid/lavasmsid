# Docker and Portainer Guide — LavaSMSID

This guide explains how to run LavaSMSID with Docker Compose and Portainer. Docker Compose and Portainer are the primary operational path for this repository.

Use development defaults from `.env.docker.example` only for local or development stacks. For production, use placeholders from `.env.production.example` and provide real values through a protected `.env` file or Portainer environment variables. Never commit secrets.

## 1. Requirements

- Git
- Docker Engine
- Docker Compose plugin
- Portainer Community Edition, optional but recommended for UI-based stack management
- Available application port, default `8080`
- Available host MySQL port, default `33066` for development exposure

## 2. Development quick start

```bash
git clone https://github.com/arpayid/lavasmsid.git
cd lavasmsid
cp .env.docker.example .env
docker compose build
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

`migrate:fresh --seed` is for development only. It drops and recreates tables.

Open the application at:

```text
http://localhost:8080
```

On a server, use:

```text
http://SERVER-IP:8080
```

If `APP_PORT` changes, use the configured port.

## 3. Production-safe setup notes

For production, do not use the development reset command. Use:

```bash
docker compose exec app php artisan migrate --force
```

Recommended first-time production commands:

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

Set production values in `.env` or Portainer variables:

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

If a domain is not ready, use `APP_URL=http://SERVER-IP:8080`. When HTTPS is enabled through a reverse proxy, update `APP_URL` to the HTTPS domain.

## 4. APP_KEY and environment handling

- Keep `APP_KEY` empty in examples.
- Generate the real key inside the running `app` container.
- Store real `.env` values on the deployment server or in Portainer environment variables.
- Do not commit `.env`, production passwords, API tokens, database dumps, or backup archives.

Generate key:

```bash
docker compose exec app php artisan key:generate
```

## 5. Portainer Stack using Git Repository

1. Open Portainer.
2. Select the target Docker environment.
3. Go to **Stacks**.
4. Click **Add stack**.
5. Enter a stack name, for example `lavasmsid`.
6. Choose **Git Repository**.
7. Repository URL:

   ```text
   https://github.com/arpayid/lavasmsid.git
   ```

8. Branch:

   ```text
   main
   ```

9. Compose path:

   ```text
   docker-compose.yml
   ```

10. Add environment variables for the deployment environment.
11. Click **Deploy the stack**.
12. Open the `app` container console and run setup commands.

## 6. Portainer Stack using Web Editor

1. Open Portainer.
2. Select the target Docker environment.
3. Go to **Stacks** and click **Add stack**.
4. Choose **Web editor**.
5. Paste the contents of `docker-compose.yml`.
6. Add environment variables.
7. Deploy the stack.
8. Run setup commands from the `app` container console.

The Git Repository method is preferred because Portainer can pull the repository and use the correct Compose path.

## 7. Common commands

```bash
docker compose ps
docker compose logs -f
docker compose logs -f app
docker compose logs -f nginx
docker compose exec app bash
docker compose exec app php artisan about
docker compose exec app php artisan route:list
docker compose exec app php artisan test
docker compose exec app npm run build
docker compose down
docker compose up -d --build
```

Do not run this in production unless you intentionally want to delete persistent volumes:

```bash
docker compose down -v
```

## 8. Services

- `app` — Laravel PHP-FPM runtime.
- `nginx` — web server container.
- `mysql` — MySQL 8 database.
- `redis` — Redis service for optional cache/session/queue usage.
- `worker` — Laravel queue worker.
- `scheduler` — Laravel scheduler worker.

## 9. Logs and troubleshooting

Check container health:

```bash
docker compose ps
```

Read logs:

```bash
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f mysql
docker compose logs -f worker
docker compose logs -f scheduler
```

Check Laravel logs:

```bash
docker compose exec app tail -f storage/logs/laravel.log
```

Common issues:

- `APP_KEY` missing: run `docker compose exec app php artisan key:generate`.
- Database connection refused: confirm MySQL is healthy and `DB_HOST=mysql`.
- HTTP 500: inspect app logs and run `php artisan optimize:clear` inside the container.
- Missing assets: run `docker compose exec app npm install` and `docker compose exec app npm run build`.
- Storage files not visible: run `docker compose exec app php artisan storage:link`.

## 10. More deployment details

See `DEPLOYMENT.md` for production-safe migration, persistence, backup/restore, reverse proxy, HTTPS, and HTTP 500 troubleshooting guidance.
