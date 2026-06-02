# Backup & Restore Guide — LavaSMSID

Panduan ini menjelaskan backup dan restore database serta file upload untuk lingkungan production.

## Prinsip Utama

- Backup database dan `storage/app/public` secara rutin.
- Simpan backup di lokasi aman, terpisah dari server aplikasi bila memungkinkan.
- Enkripsi backup yang berisi data pribadi.
- Uji restore secara berkala di lingkungan non-production.
- Jangan menyimpan password database dalam crontab.

## Backup Manual Database MySQL

```bash
mkdir -p backups
mysqldump --defaults-extra-file=/path/to/.my.cnf database_name > backups/db_$(date +%Y%m%d_%H%M%S).sql
```

Contoh file `/path/to/.my.cnf` yang hanya dapat dibaca pemilik file:

```ini
[client]
user=database_user
password=database_password
host=127.0.0.1
```

```bash
chmod 600 /path/to/.my.cnf
```

> Peringatan: jangan pernah menyimpan plain DB password di crontab, command history, repository, atau dokumen publik.

## Backup File Upload

```bash
mkdir -p backups
rsync -a storage/app/public/ backups/uploads_$(date +%Y%m%d)/
```

## Backup Terjadwal MySQL

Gunakan cron yang memanggil script backup tanpa password inline:

```cron
0 1 * * * cd /path/to/lavasmsid && /usr/local/bin/lavasmsid-backup.sh >> storage/logs/backup.log 2>&1
```

Contoh isi script:

```bash
#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/path/to/lavasmsid
BACKUP_DIR=/path/to/backups/lavasmsid
MYSQL_CNF=/path/to/.my.cnf
DB_NAME=database_name
STAMP=$(date +%Y%m%d_%H%M%S)

mkdir -p "$BACKUP_DIR"
mysqldump --defaults-extra-file="$MYSQL_CNF" "$DB_NAME" > "$BACKUP_DIR/db_$STAMP.sql"
rsync -a "$APP_DIR/storage/app/public/" "$BACKUP_DIR/uploads_$STAMP/"
find "$BACKUP_DIR" -type f -name 'db_*.sql' -mtime +14 -delete
find "$BACKUP_DIR" -maxdepth 1 -type d -name 'uploads_*' -mtime +14 -exec rm -rf {} +
```

## Restore Database MySQL

Jalankan restore hanya setelah memastikan target database benar.

```bash
mysql --defaults-extra-file=/path/to/.my.cnf database_name < backups/db_YYYYMMDD_HHMMSS.sql
```

## Restore File Upload

```bash
rsync -a backups/uploads_YYYYMMDD_HHMMSS/ storage/app/public/
php artisan storage:link
```

## Checklist Restore

- [ ] Backup yang akan dipakai sudah diverifikasi.
- [ ] Restore dilakukan di environment yang benar.
- [ ] Maintenance mode aktif bila restore ke production.
- [ ] Database berhasil di-restore.
- [ ] File upload berhasil di-restore.
- [ ] Permission `storage` dan `bootstrap/cache` sudah benar.
- [ ] Aplikasi dapat login dan halaman utama terbuka.
- [ ] Log aplikasi diperiksa setelah restore.
