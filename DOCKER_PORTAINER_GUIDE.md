# Panduan Development Docker & Portainer LavaSMSID

Panduan ini dibuat untuk menjalankan LavaSMSID di lingkungan **development lokal atau server development** memakai Docker Compose dan Portainer. Konfigurasi ini bukan panduan production.

> Catatan: gunakan kredensial pada `.env.docker.example` hanya untuk development. Jangan memakai nilai contoh ini untuk server production.

---

## 1. Kebutuhan

- Git.
- Docker Engine dan Docker Compose plugin.
- Portainer Community Edition bila ingin deploy lewat UI Portainer.
- Akses terminal ke server atau komputer development.
- Port aplikasi yang tersedia, default `8080`.
- Port MySQL host yang tersedia, default `33066`.

---

## 2. Ringkasan Instalasi Docker dan Portainer

### Docker

Instal Docker Engine sesuai sistem operasi yang dipakai. Setelah instalasi, pastikan perintah berikut berjalan:

```bash
docker --version
docker compose version
```

### Portainer

Portainer dapat dijalankan sebagai container. Contoh ringkas untuk development:

```bash
docker volume create portainer_data
docker run -d \
  -p 8000:8000 \
  -p 9443:9443 \
  --name portainer \
  --restart=always \
  -v /var/run/docker.sock:/var/run/docker.sock \
  -v portainer_data:/data \
  portainer/portainer-ce:latest
```

Buka Portainer melalui:

```text
https://SERVER-IP:9443
```

---

## 3. Clone Repository

```bash
git clone https://github.com/arpayid/lavasmsid.git
cd lavasmsid
```

---

## 4. Siapkan File Environment

Salin file environment Docker ke `.env`:

```bash
cp .env.docker.example .env
```

Nilai default development:

```text
APP_URL=http://localhost:8080
APP_PORT=8080
MYSQL_PORT=33066
DB_HOST=mysql
DB_DATABASE=lavasmsid
DB_USERNAME=lavasmsid
DB_PASSWORD=secret
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

Jika memakai server, sesuaikan `APP_URL`, misalnya:

```text
APP_URL=http://SERVER-IP:8080
```

---

## 5. Deploy dari Portainer Stack Menggunakan Git Repository

1. Masuk ke Portainer.
2. Pilih environment Docker yang akan dipakai.
3. Buka menu **Stacks**.
4. Klik **Add stack**.
5. Isi nama stack, misalnya `lavasmsid-dev`.
6. Pilih metode **Git Repository**.
7. Isi Repository URL:

   ```text
   https://github.com/arpayid/lavasmsid.git
   ```

8. Isi branch sesuai kebutuhan, misalnya:

   ```text
   main
   ```

9. Isi Compose path:

   ```text
   docker-compose.yml
   ```

10. Pada bagian environment variables Portainer, tambahkan bila ingin override nilai default:

    ```text
    APP_PORT=8080
    MYSQL_PORT=33066
    DB_DATABASE=lavasmsid
    DB_USERNAME=lavasmsid
    DB_PASSWORD=secret
    MYSQL_ROOT_PASSWORD=rootsecret
    ```

11. Klik **Deploy the stack**.
12. Setelah container berjalan, buka terminal/console container `app` di Portainer untuk menjalankan perintah first-time setup.

---

## 6. Deploy dari Portainer Stack Menggunakan Web Editor

1. Clone repository di server, atau buka isi `docker-compose.yml` dari repository.
2. Masuk ke Portainer.
3. Pilih **Stacks** lalu klik **Add stack**.
4. Isi nama stack, misalnya `lavasmsid-dev`.
5. Pilih **Web editor**.
6. Paste isi `docker-compose.yml`.
7. Tambahkan environment variables bila perlu:

   ```text
   APP_PORT=8080
   MYSQL_PORT=33066
   DB_DATABASE=lavasmsid
   DB_USERNAME=lavasmsid
   DB_PASSWORD=secret
   MYSQL_ROOT_PASSWORD=rootsecret
   ```

8. Klik **Deploy the stack**.
9. Jalankan perintah setup awal melalui console container `app` atau terminal server.

> Penting: bila memakai Web editor, pastikan build context dan file project tersedia pada Docker host. Untuk alur yang lebih sederhana, gunakan metode Git Repository.

---

## 7. First-time Setup dari Terminal

Jalankan perintah berikut dari root repository:

```bash
docker compose build
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link
docker compose exec app npm install
docker compose exec app npm run build
```

Penjelasan singkat:

- `composer install` mengisi dependency PHP ke volume `vendor_data`.
- `npm install` mengisi dependency frontend ke volume `node_modules_data`.
- `migrate:fresh --seed` menghapus dan membuat ulang tabel database development.
- `storage:link` membuat symlink storage publik Laravel.

---

## 8. Akses Aplikasi

Buka aplikasi melalui browser:

```text
http://SERVER-IP:8080
```

Untuk komputer lokal:

```text
http://localhost:8080
```

Jika `APP_PORT` diubah, sesuaikan URL dengan port tersebut.

---

## 9. Perintah Umum

Melihat status container:

```bash
docker compose ps
```

Melihat log semua service:

```bash
docker compose logs -f
```

Masuk ke shell container aplikasi:

```bash
docker compose exec app bash
```

Menjalankan test Laravel:

```bash
docker compose exec app php artisan test
```

Menghentikan stack tanpa menghapus volume:

```bash
docker compose down
```

Menghentikan stack dan menghapus volume database/dependency:

```bash
docker compose down -v
```

---

## 10. Troubleshooting

### Permission issue pada `storage` atau `bootstrap/cache`

Gejala:

- Laravel tidak bisa menulis cache.
- Upload gagal.
- Log tidak bisa dibuat.

Solusi:

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R ug+rwX storage bootstrap/cache
```

### Port conflict

Gejala:

- `Bind for 0.0.0.0:8080 failed`.
- MySQL host port `33066` sudah dipakai.

Solusi:

Ubah nilai di `.env` atau environment variables Portainer:

```text
APP_PORT=8081
MYSQL_PORT=33067
```

Lalu recreate stack:

```bash
docker compose up -d --force-recreate
```

### Database connection refused

Gejala:

- Laravel gagal konek ke database.
- Pesan error `SQLSTATE[HY000] [2002] Connection refused`.

Solusi:

1. Pastikan service MySQL sehat:

   ```bash
   docker compose ps
   ```

2. Pastikan konfigurasi database memakai host service Docker:

   ```text
   DB_HOST=mysql
   DB_PORT=3306
   ```

3. Tunggu MySQL selesai start, lalu ulangi migration:

   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

### APP_KEY belum ada

Gejala:

- Laravel menampilkan error key aplikasi belum tersedia.

Solusi:

```bash
docker compose exec app php artisan key:generate
```

### Storage link bermasalah

Gejala:

- File publik dari storage tidak tampil.

Solusi:

```bash
docker compose exec app php artisan storage:link
```

Jika link sudah ada tetapi rusak:

```bash
docker compose exec app rm -f public/storage
docker compose exec app php artisan storage:link
```

### NPM build issue

Gejala:

- Asset Vite tidak ditemukan.
- `npm run build` gagal karena dependency belum ada.

Solusi:

```bash
docker compose exec app npm install
docker compose exec app npm run build
```

Jika masih gagal, hapus volume dependency frontend lalu install ulang:

```bash
docker compose down
docker volume ls | grep node_modules
docker compose up -d
docker compose exec app npm install
docker compose exec app npm run build
```

---

## 11. Catatan Redis

Service Redis disediakan untuk development bila suatu saat cache, session, atau queue ingin diarahkan ke Redis. Konfigurasi default tetap memakai database:

```text
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

PHP Redis extension sudah tersedia di image aplikasi. Untuk memakai Redis, ubah environment secara sadar, misalnya:

```text
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
```
