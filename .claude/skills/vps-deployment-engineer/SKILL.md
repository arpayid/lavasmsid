---
name: Laravel VPS Deployment Engineer
description: Use this skill when preparing LavaSMSID deployment to Ubuntu or Debian VPS with PHP, Composer, Node.js, database, Redis, Nginx, PHP-FPM, SSL, Supervisor, queue worker, scheduler, backup, and production optimization.
---

# Laravel VPS Deployment Engineer

Prepare LavaSMSID for production VPS deployment.

## Supported Server

- Ubuntu 22.04+
- Ubuntu 24.04+
- Debian 12+
- Debian 13+

## Required Stack

- PHP 8.3+
- Composer
- Node.js LTS
- MySQL or PostgreSQL
- Redis optional
- Nginx
- PHP-FPM
- Certbot SSL
- Supervisor
- Cron scheduler
- Git

## Required Docs

Create:

- docs/DEPLOYMENT_VPS.md
- docs/NGINX_EXAMPLE.conf
- docs/SUPERVISOR_QUEUE.conf
- docs/CRON_SCHEDULER.md
- docs/BACKUP_DATABASE.md

## Production Commands

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## Permission Rules

Set writable permissions for storage and bootstrap/cache.

## Scheduler

Prepare cron for Laravel scheduler:

```text
* * * * * cd /var/www/lavasmsid && php artisan schedule:run >> /dev/null 2>&1
```

## Queue Worker

Use Supervisor for queue worker:

```text
php artisan queue:work --sleep=3 --tries=3 --timeout=120
```

## Nginx Requirements

Nginx must use public as root, index.php, try_files, PHP-FPM handler, deny environment file access, deny hidden files, security headers, and upload size config if needed.

## Backup

Prepare backup for database, storage uploads, and environment file separately.

## Final Validation

Check:

```bash
php artisan about
php artisan route:list
php artisan migrate:status
npm run build
```

Never deploy with APP_DEBUG=true.
