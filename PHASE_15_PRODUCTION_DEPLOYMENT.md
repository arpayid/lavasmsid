# Phase 15 Production Deployment Checklist — LavaSMSID v1.0.0

Dokumen ini adalah checklist executable untuk deployment production LavaSMSID v1.0.0 ke VPS Debian/Ubuntu. Gunakan placeholder saja; jangan menulis domain, password, token, atau secret nyata ke repository.

## 1. Deployment Assumptions

- OS: Debian/Ubuntu production VPS.
- Web server: Nginx.
- PHP: 8.3 atau lebih baru.
- Database: MySQL direkomendasikan untuk deployment production pertama; PostgreSQL opsional.
- Queue: database queue.
- Cache/session: database.
- SSL: Certbot.
- Process manager: Supervisor.
- App path placeholder: `/var/www/lavasmsid`.
- Backup path placeholder: `/var/backups/lavasmsid`.
- Domain placeholder: `example.com`.

## 2. Placeholder Table

| Placeholder | Ganti dengan |
|---|---|
| `example.com` | Domain production |
| `/var/www/lavasmsid` | Path aplikasi |
| `lavasmsid` | Nama database |
| `lavasmsid_user` | User database |
| `CHANGE_WITH_STRONG_PASSWORD` | Password database kuat |
| `/var/backups/lavasmsid` | Path backup |
| `/root/.my.cnf-lavasmsid` | File credential MySQL aman |

## 3. Server Preparation Commands

Jalankan sebagai user yang memiliki akses `sudo`.

### 3.1 Update OS

```bash
sudo apt update
sudo apt upgrade -y
```

### 3.2 Install base packages

```bash
sudo apt install -y \
  nginx \
  supervisor \
  git \
  curl \
  unzip \
  certbot \
  python3-certbot-nginx \
  ufw \
  ca-certificates \
  software-properties-common
```

### 3.3 Install PHP 8.3 and Laravel extensions

Ubuntu dapat memakai repository PHP yang tersedia di OS. Jika PHP 8.3 belum tersedia, gunakan repository resmi/terpercaya sesuai SOP server.

```bash
sudo apt install -y \
  php8.3 \
  php8.3-cli \
  php8.3-fpm \
  php8.3-mysql \
  php8.3-pgsql \
  php8.3-mbstring \
  php8.3-xml \
  php8.3-curl \
  php8.3-zip \
  php8.3-bcmath \
  php8.3-gd \
  php8.3-intl \
  php8.3-readline
```

Verifikasi:

```bash
php -v
php -m | grep -E 'bcmath|curl|gd|intl|mbstring|mysqli|pdo_mysql|pdo_pgsql|xml|zip'
```

### 3.4 Install Composer

```bash
cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
rm -f composer-setup.php
composer --version
```

### 3.5 Install Node.js LTS and npm

```bash
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs
node --version
npm --version
```

### 3.6 Create deploy user

```bash
id deploy || sudo adduser --disabled-password --gecos "" deploy
sudo usermod -aG www-data deploy
sudo mkdir -p /home/deploy/.ssh
sudo chown -R deploy:deploy /home/deploy/.ssh
sudo chmod 700 /home/deploy/.ssh
```

Tambahkan public SSH key deployment ke `/home/deploy/.ssh/authorized_keys` sesuai SOP akses server.

### 3.7 Create directories and safe permissions

```bash
sudo mkdir -p /var/www/lavasmsid
sudo mkdir -p /var/backups/lavasmsid
sudo chown -R deploy:www-data /var/www/lavasmsid
sudo chown -R deploy:deploy /var/backups/lavasmsid
sudo chmod 2755 /var/www/lavasmsid
sudo chmod 750 /var/backups/lavasmsid
ls -ld /var/www/lavasmsid /var/backups/lavasmsid
```

### 3.8 Enable services and firewall

Pilih service database sesuai keputusan pada bagian database.

```bash
sudo systemctl enable --now nginx
sudo systemctl enable --now php8.3-fpm
sudo systemctl enable --now supervisor
sudo ufw allow OpenSSH
sudo ufw allow "Nginx Full"
sudo ufw --force enable
sudo ufw status verbose
```

## 4. Database Setup

Gunakan salah satu: MySQL atau PostgreSQL. MySQL direkomendasikan untuk deployment production pertama.

### 4.1 MySQL recommended

```bash
sudo apt install -y mysql-server
sudo systemctl enable --now mysql
sudo mysql
```

Jalankan SQL berikut di prompt MySQL:

```sql
CREATE DATABASE lavasmsid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lavasmsid_user'@'127.0.0.1' IDENTIFIED BY 'CHANGE_WITH_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON lavasmsid.* TO 'lavasmsid_user'@'127.0.0.1';
FLUSH PRIVILEGES;
EXIT;
```

Verifikasi koneksi:

```bash
mysql -h 127.0.0.1 -u lavasmsid_user -p lavasmsid -e "SELECT 1;"
```

### 4.2 PostgreSQL optional

```bash
sudo apt install -y postgresql postgresql-contrib
sudo systemctl enable --now postgresql
sudo -iu postgres psql
```

Jalankan SQL berikut di prompt PostgreSQL:

```sql
CREATE DATABASE lavasmsid;
CREATE USER lavasmsid_user WITH ENCRYPTED PASSWORD 'CHANGE_WITH_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON DATABASE lavasmsid TO lavasmsid_user;
\q
```

Gunakan `DB_CONNECTION=pgsql`, `DB_PORT=5432`, dan credential PostgreSQL pada `.env` bila opsi ini dipilih.

## 5. Clone Release v1.0.0

```bash
sudo -iu deploy
cd /var/www
git clone git@github.com:arpayid/lavasmsid.git lavasmsid
cd /var/www/lavasmsid
git fetch --tags
git checkout v1.0.0
git status
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

## 6. `.env` Production Setup

```bash
cd /var/www/lavasmsid
cp .env.example .env
nano .env
```

Template aman:

```env
APP_NAME="LavaSMSID"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://example.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=Asia/Makassar

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavasmsid
DB_USERNAME=lavasmsid_user
DB_PASSWORD=CHANGE_WITH_STRONG_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=CHANGE_WITH_SMTP_HOST
MAIL_PORT=587
MAIL_USERNAME=CHANGE_WITH_SMTP_USER
MAIL_PASSWORD=CHANGE_WITH_SMTP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Catatan:

- Biarkan `APP_KEY` kosong sampai `php artisan key:generate` dijalankan di server.
- Set `SESSION_SECURE_COOKIE=true` setelah SSL aktif.
- Jangan commit `.env`.

## 7. App Setup

```bash
cd /var/www/lavasmsid
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 8. Nginx Server Block

Buat file `/etc/nginx/sites-available/lavasmsid`:

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name example.com www.example.com;
    root /var/www/lavasmsid/public;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 20M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }

    location ~* \.(env|log|sql|bak|backup|old)$ {
        deny all;
    }
}
```

Aktifkan site:

```bash
sudo ln -s /etc/nginx/sites-available/lavasmsid /etc/nginx/sites-enabled/lavasmsid
sudo nginx -t
sudo systemctl reload nginx
```

## 9. SSL Setup

```bash
sudo certbot --nginx -d example.com -d www.example.com
sudo certbot renew --dry-run
sudo nginx -t
sudo systemctl reload nginx
```

Update `.env` setelah SSL aktif:

```env
APP_URL=https://example.com
SESSION_SECURE_COOKIE=true
```

Refresh cache config:

```bash
cd /var/www/lavasmsid
php artisan optimize:clear
php artisan config:cache
```

## 10. Supervisor Queue Worker

Buat `/etc/supervisor/conf.d/lavasmsid-worker.conf`:

```ini
[program:lavasmsid-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/lavasmsid/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
directory=/var/www/lavasmsid
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/lavasmsid/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start lavasmsid-worker:*
sudo supervisorctl status
```

Restart queue setelah deploy:

```bash
cd /var/www/lavasmsid
php artisan queue:restart
sudo supervisorctl restart lavasmsid-worker:*
```

## 11. Scheduler Cron

Edit crontab user web:

```bash
sudo crontab -u www-data -e
```

Tambahkan:

```cron
* * * * * cd /var/www/lavasmsid && php artisan schedule:run >> /dev/null 2>&1
```

Verifikasi:

```bash
cd /var/www/lavasmsid
php artisan schedule:list
```

## 12. File Permissions

```bash
sudo chown -R deploy:www-data /var/www/lavasmsid
sudo chown -R www-data:www-data /var/www/lavasmsid/storage /var/www/lavasmsid/bootstrap/cache
sudo find /var/www/lavasmsid -type d -exec chmod 755 {} \;
sudo find /var/www/lavasmsid -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/lavasmsid/storage /var/www/lavasmsid/bootstrap/cache
sudo chmod 640 /var/www/lavasmsid/.env
ls -ld /var/www/lavasmsid/storage /var/www/lavasmsid/bootstrap/cache /var/www/lavasmsid/public/storage
ls -l /var/www/lavasmsid/.env
```

Peringatan: jangan pernah memakai `chmod 777` untuk file atau direktori production.

## 13. Backup Before and After Deploy

### 13.1 Safe MySQL credential file

Buat `/root/.my.cnf-lavasmsid`:

```ini
[client]
user=lavasmsid_user
password=CHANGE_WITH_STRONG_PASSWORD
host=127.0.0.1
```

```bash
sudo chmod 600 /root/.my.cnf-lavasmsid
```

Peringatan: jangan pernah menyimpan password database langsung di crontab, command history, repository, atau dokumen publik.

### 13.2 Backup database and storage

Before deploy:

```bash
sudo mkdir -p /var/backups/lavasmsid/pre-deploy
sudo mysqldump --defaults-extra-file=/root/.my.cnf-lavasmsid lavasmsid \
  > /var/backups/lavasmsid/pre-deploy/db_$(date +%Y%m%d_%H%M%S).sql
sudo rsync -a /var/www/lavasmsid/storage/app/public/ \
  /var/backups/lavasmsid/pre-deploy/uploads_$(date +%Y%m%d_%H%M%S)/
```

After deploy:

```bash
sudo mkdir -p /var/backups/lavasmsid/post-deploy
sudo mysqldump --defaults-extra-file=/root/.my.cnf-lavasmsid lavasmsid \
  > /var/backups/lavasmsid/post-deploy/db_$(date +%Y%m%d_%H%M%S).sql
sudo rsync -a /var/www/lavasmsid/storage/app/public/ \
  /var/backups/lavasmsid/post-deploy/uploads_$(date +%Y%m%d_%H%M%S)/
```

### 13.3 Scheduled backup script

Buat `/usr/local/bin/lavasmsid-backup.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/lavasmsid
BACKUP_DIR=/var/backups/lavasmsid/scheduled
MYSQL_CNF=/root/.my.cnf-lavasmsid
DB_NAME=lavasmsid
STAMP=$(date +%Y%m%d_%H%M%S)

mkdir -p "$BACKUP_DIR"
mysqldump --defaults-extra-file="$MYSQL_CNF" "$DB_NAME" > "$BACKUP_DIR/db_$STAMP.sql"
rsync -a "$APP_DIR/storage/app/public/" "$BACKUP_DIR/uploads_$STAMP/"
find "$BACKUP_DIR" -type f -name 'db_*.sql' -mtime +14 -delete
find "$BACKUP_DIR" -maxdepth 1 -type d -name 'uploads_*' -mtime +14 -exec rm -rf {} +
```

```bash
sudo chmod 750 /usr/local/bin/lavasmsid-backup.sh
sudo crontab -e
```

Cron backup:

```cron
0 1 * * * /usr/local/bin/lavasmsid-backup.sh >> /var/log/lavasmsid-backup.log 2>&1
```

## 14. Post-Deploy Validation

```bash
cd /var/www/lavasmsid
php artisan about
php artisan route:list
php artisan schedule:list
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
sudo supervisorctl status
sudo systemctl status nginx --no-pager
sudo systemctl status php8.3-fpm --no-pager
curl -I https://example.com
curl -I https://example.com/login
tail -n 100 storage/logs/laravel.log
```

Expected checks:

- `php artisan about`, `route:list`, dan `schedule:list` berjalan tanpa error.
- Cache commands berhasil.
- Build frontend berhasil.
- Supervisor worker `RUNNING`.
- Nginx dan PHP-FPM `active (running)`.
- `curl -I` mengembalikan status HTTP yang sesuai.
- Tidak ada error Laravel baru pada log.

## 15. Rollback Plan

Catat commit/tag aktif sebelum deploy:

```bash
cd /var/www/lavasmsid
git rev-parse HEAD
```

Rollback code:

```bash
cd /var/www/lavasmsid
php artisan down --retry=60
git fetch --tags
git checkout PREVIOUS_TAG_OR_COMMIT
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
sudo supervisorctl restart lavasmsid-worker:*
sudo systemctl reload php8.3-fpm
sudo nginx -t
sudo systemctl reload nginx
php artisan up
```

Database restore hanya jika migration mengubah schema/data dan restore sudah disetujui:

```bash
php artisan down --retry=60
mysql --defaults-extra-file=/root/.my.cnf-lavasmsid lavasmsid \
  < /var/backups/lavasmsid/pre-deploy/db_YYYYMMDD_HHMMSS.sql
rsync -a /var/backups/lavasmsid/pre-deploy/uploads_YYYYMMDD_HHMMSS/ \
  /var/www/lavasmsid/storage/app/public/
php artisan storage:link
php artisan up
```

## 16. Final Checklist

- [ ] Git tag `v1.0.0` deployed.
- [ ] `.env` production completed.
- [ ] `APP_DEBUG=false`.
- [ ] HTTPS active.
- [ ] `APP_URL=https://example.com` replaced with production domain.
- [ ] `SESSION_SECURE_COOKIE=true` after SSL.
- [ ] Queue worker active.
- [ ] Scheduler active.
- [ ] Backup before deploy completed.
- [ ] Backup after deploy completed.
- [ ] Backup restore tested in non-production.
- [ ] Admin login tested.
- [ ] Public website tested.
- [ ] CSV export smoke tested.
- [ ] No new Laravel log errors.
- [ ] No `chmod 777` used.
- [ ] No real password/token/domain committed.
