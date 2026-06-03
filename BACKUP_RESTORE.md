# Backup & Restore Guide — LavaSMSID

Panduan ini menjelaskan backup dan restore database serta file upload untuk lingkungan production dengan pendekatan Docker-first.

## Prinsip Utama

- Backup database dan `storage/app/public` secara rutin.
- Simpan backup di lokasi aman, terpisah dari server aplikasi bila memungkinkan.
- Enkripsi backup yang berisi data pribadi.
- Uji restore secara berkala di lingkungan non-production.
- Jangan menyimpan password database dalam crontab atau command history.

## Backup Manual Database MySQL (Docker)

```bash
mkdir -p backups
docker compose exec mysql mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > backups/db_$(date +%Y%m%d_%H%M%S).sql
```

Ganti `$DB_USERNAME`, `$DB_PASSWORD`, dan `$DB_DATABASE` dengan nilai dari environment file production.

## Backup File Upload (Docker)

```bash
docker compose exec app tar -czf /tmp/lavasmsid-storage.tar.gz storage/app/public
cp /tmp/lavasmsid-storage.tar.gz backups/
```

## Backup Terjadwal dengan Script

Gunakan cron yang memanggil script backup tanpa password inline:

```cron
0 1 * * * /path/to/lavasmsid-backup.sh >> /var/log/lavasmsid-backup.log 2>&1
```

Contoh isi script:

```bash
#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/path/to/lavasmsid
BACKUP_DIR=/path/to/backups/lavasmsid
STAMP=$(date +%Y%m%d_%H%M%S)

cd "$APP_DIR"

mkdir -p "$BACKUP_DIR"

# Backup database (password diambil dari .env)
source .env
docker compose exec mysql mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$BACKUP_DIR/db_$STAMP.sql"

# Backup file upload
docker compose exec app tar -czf "/tmp/uploads_$STAMP.tar.gz" storage/app/public
cp "/tmp/uploads_$STAMP.tar.gz" "$BACKUP_DIR/"

# Hapus backup lebih dari 14 hari
find "$BACKUP_DIR" -type f -name 'db_*.sql' -mtime +14 -delete
find "$BACKUP_DIR" -type f -name 'uploads_*.tar.gz' -mtime +14 -delete
```

> Peringatan: jangan pernah menyimpan plain DB password di crontab, command history, repository, atau dokumen publik.

## Restore Database MySQL (Docker)

Jalankan restore hanya setelah memastikan target database benar.

```bash
docker compose exec -T mysql mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < backups/db_YYYYMMDD_HHMMSS.sql
```

## Restore File Upload (Docker)

```bash
docker compose exec -T app tar -xzf - -C / < backups/uploads_YYYYMMDD_HHMMSS.tar.gz
docker compose exec app php artisan storage:link
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

## Referensi

Lihat `DEPLOYMENT.md` untuk production deployment checklist dan verification commands.
