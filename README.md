# Application Booking Kursi Travel (Mobil)

Sistem pemesanan tiket travel berbasis web dengan fitur manajemen armada, pengaturan kursi dinamis, dan grouping penumpang.

## Fitur Utama

- **Cek Jadwal & Ketersediaan**: Pencarian jadwal berdasarkan Rute (Asal - Tujuan) dan Tanggal.
- **Seat Selection UI**:
  - Visualisasi layout kursi sesuai tipe mobil (HiAce, Avanza, dll).
  - Indikator posisi "SOPIR" (Driver) yang jelas.
  - Status kursi Real-time (Available, Booked, Check-in).
  - Support pemilihan kursi group/rombongan.
- **Manajemen Armada (Admin)**:
  - Input data mobil & plat nomor.
  - Pengaturan baris & kolom kursi dinamis (JSON based).
- **Manajemen Jadwal (Admin)**:
  - Pooling system: Penumpang masuk ke "Pool" sebelum di-assign ke armada.
  - Opsi menambahkan penumpang ke jadwal yang sudah berjalan (jika kapasitas tersedia).
- **Dashboard Admin**:
  - Statistik booking hari ini & besok.
  - Quick Actions.
- **Pengaturan**:
  - Setup nomor WhatsApp Admin dinamis untuk link konfirmasi/bantuan.

## Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL 8
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Infrastructure**: Docker & Nginx

## Cara Install (Deployment)

Aplikasi ini sudah dikonfigurasi menggunakan Docker untuk kemudahan deployment.

### 1. Clone Repository

```bash
cd /root
git clone https://github.com/Muzakie-ID/BOOKING-KURSI-MOBIL.git
cd BOOKING-KURSI-MOBIL
```

### 2. Konfigurasi Environment

Copy file `.env.example` dan sesuaikan konfigurasinya:

```bash
cp .env.example .env
nano .env
```

Pastikan pengaturan database di `.env` sesuai dengan server database Anda. Jika menggunakan network docker `db_master_shared` (sesuai `docker-compose.yml`), pastikan host DB mengarah ke service database yang benar.

### 3. Jalankan Docker

Bangun dan jalankan container:

```bash
docker compose up -d --build
```

### 4. Setup Aplikasi (Pertama Kali)

Masuk ke container aplikasi untuk instalasi dependencies dan migrasi database:

```bash
# Masuk ke container
docker compose exec app bash

# Di dalam container jalankan:
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
exit
```

### Akun Admin Default (Seeder)

Jika Anda menjalankan seed (`php artisan migrate --seed`), akun admin berikut akan dibuat:

- **Email**: `admin@travel.com`
- **Password**: `password123`

### 5. Akses Aplikasi

Buka browser dan akses IP server atau domain yang telah dikonfigurasi.

## Pengembangan Lokal

Jika ingin mengembangkan di local (Laragon/XAMPP):
1. `composer install`
2. `npm install && npm run build` (jika ada assets)
3. `php artisan migrate`
4. `php artisan serve`

## Troubleshooting

### Error: Permission denied (storage/framework/views)

Jika Anda menemui error `file_put_contents(...): Failed to open stream: Permission denied` saat mengakses aplikasi, hal ini disebabkan user web server di dalam container tidak memiliki hak akses tulis ke folder storage. Jalankan perintah berikut untuk memperbaikinya:

```bash
docker compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

## Lisensi

Private Property.
