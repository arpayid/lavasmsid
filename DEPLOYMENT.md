# Deployment Guide — LavaSMSID

## Production Readiness Checklist

### 1. Environment Setup

```bash
# Clone repository
git clone https://github.com/your-org/lavasmsid.git
cd lavasmsid

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
npm ci

# Build production assets
npm run build

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Configure Environment (.env)

| Variable | Production Value | Notes |
|----------|----------------|-------|
| APP_ENV | production | |
| APP_DEBUG | false | Critical: do not set to true |
| APP_URL | https://your-domain.com | Must use HTTPS |
| LOG_LEVEL | error | Warning is also acceptable |
| DB_* | (your production credentials) | Set secure password |
| SESSION_DRIVER | database | Already configured |
| QUEUE_CONNECTION | database | Already configured |
| CACHE_STORE | database | Already configured |
| FILESYSTEM_DISK | public | Used by uploads |
| MAIL_* | (your SMTP credentials) | Set real mail server |

### 3. Database Migration

```bash
# Run migrations
php artisan migrate --force

# Seed initial roles and permissions (only if fresh database)
php artisan db:seed --force

# Create storage symlink
php artisan storage:link
```

### 4. Cache Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache if needed
php artisan optimize:clear
```

### 5. Queue Worker

Queue worker is required for notification broadcasting and other background tasks:
```bash
# Start queue worker (production)
php artisan queue:work --daemon

# Or use Supervisor to keep it running
# Example supervisor config at /etc/supervisor/conf.d/lavasmsid-worker.conf
```

Sample Supervisor configuration:
```ini
[program:lavasmsid-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
```

### 6. Scheduler (Cron)

Add this entry to crontab:
```
* * * * * cd /path/to/lavasmsid && php artisan schedule:run >> /dev/null 2>&1
```

### 7. File Permissions

```bash
# Recursive ownership
chown -R www-data:www-data storage bootstrap/cache

# Directory permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/storage

# Important: NEVER use 777 for any directory or file.
# 775 (owner+group write) is sufficient for most setups.

# Verify permission
ls -ld storage bootstrap/cache public/storage
```

### 8. Security Checklist

- [ ] APP_DEBUG=false
- [ ] APP_KEY generated (php artisan key:generate)
- [ ] HTTPS enabled
- [ ] APP_URL matches production domain
- [ ] Database credentials use strong password
- [ ] storage:link executed
- [ ] Symfony requirements satisfied
- [ ] Secret .env file never committed to git
- [ ].gitignore includes .env
- [ ] Composer auth.json not committed

### 9. Backup

Lihat juga `BACKUP_RESTORE.md` untuk prosedur backup terjadwal, restore, retensi, dan checklist uji pemulihan.

**Before any deploy:**

```bash
# Backup database
mysqldump -u username -p database_name > backups/db_$(date +%Y%m%d_%H%M%S).sql

# Backup uploaded files
rsync -av storage/app/public/ backups/uploads_$(date +%Y%m%d)/
```

**Restore:**

```bash
# Restore database
mysql -u username -p database_name < backups/db_20260101_120000.sql

# Restore uploaded files
rsync -av backups/uploads_20260101/ storage/app/public/
```

**Before deploy checklist:**
- [ ] Database backup completed
- [ ] Storage/files backup completed
- [ ] Current release tagged or noted
- [ ] Maintenance mode enabled: `php artisan down`

### 10. Post-Deploy Validation

```bash
# Verify environment
php artisan about

# Verify routes load correctly
php artisan route:list

# Run tests
php artisan test

# Verify caching works
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify frontend builds
npm run build

# Check application logs
tail -f storage/logs/laravel.log

# Verify scheduler is working
php artisan schedule:list
```

## Important Security Reminders

1. **Never commit .env** to the repository.
2. **Never commit real credentials** (database passwords, API keys).
3. **Keep APP_DEBUG=false** in production to prevent sensitive stack trace exposure.
4. **Use HTTPS** for all traffic. Set SESSION_SECURE_COOKIE=true if using HTTPS.
5. **Set correct APP_URL** to prevent broken links and CSRF issues.
6. **Run storage:link** so uploaded images appear in the public directory.
7. **Verify PHP upload_max_filesize** and post_max_size in php.ini for file uploads.
8. **Set up regular backups** for database and uploaded files.
9. **Monitor storage/logs/laravel.log** for errors and warnings.
10. **Update dependencies regularly**: `composer update` and `npm update`.
