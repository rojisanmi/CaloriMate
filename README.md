# CaloriMate

![Project Badge](https://img.shields.io/badge/Status-Active-brightgreen)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-%3E%3D8.2-blue)
![License](https://img.shields.io/badge/License-MIT-blue)

## 📌 Ringkasan

CaloriMate adalah aplikasi manajemen nutrisi & latihan berbasis web yang dibangun dengan **Laravel 11** (PHP). Aplikasi ini memiliki 3 role pengguna:

- **Admin** — mengelola data melalui dashboard admin
- **Trainer** — membuat dan mengelola database makanan, program olahraga, dan item latihan
- **Client** — mencatat konsumsi makanan, memantau histori kalori, dan menjalankan program olahraga

Aplikasi dilengkapi dengan **RESTful API** (Laravel Sanctum) untuk integrasi dengan aplikasi mobile Flutter.

---

## 🎯 Fitur Utama

### Autentikasi & Otorisasi
- Tiga role: Admin (`role=0`), Client (`role=1`), Trainer (`role=2`)
- Middleware proteksi: `auth.required`, `role:1` (client), `role:2` (trainer)
- Autentikasi API via **Laravel Sanctum** (Bearer Token)

### Trainer
- CRUD **Makanan** (`foods`) — nama, kalori per porsi, nutrisi
- CRUD **Program Latihan** (`programs`) — tipe, difficulty, deskripsi
- CRUD **Item Latihan** (`program_items`) — gerakan per program
- Profil trainer (update data + upload foto)

### Client
- **Dashboard** — BMI, ringkasan kalori, quick links
- **Diary Makanan** — input per kategori (sarapan, makan siang, makan malam, camilan)
- **Riwayat Konsumsi** — grafik harian/mingguan/bulanan
- **Statistik Kalori** — kalori masuk vs keluar, nutrisi (protein/karbo/lemak)
- **Program Olahraga** — lihat program, detail latihan, mulai program
- **Rekomendasi Cerdas** — saran makanan & latihan berbasis data pengguna
- **Notifikasi** — pengingat makanan & olahraga
- Profil client (update data + upload foto)

### Guest
- Beranda umum (`/`)
- Login & Register (client dan trainer)

### RESTful API
- Endpoint lengkap di `/api/` untuk integrasi mobile
- Auth: `POST /api/login`, `POST /api/register/client`, `POST /api/register/trainer`
- Client API: diary, history, statistic, exercise, recommendations, notifications
- Trainer API: profile, foods, programs, items

---

## 🛠️ Prasyarat (Requirements)

Pastikan software berikut sudah terinstal di komputer:

| Software | Versi Minimum | Keterangan |
|----------|--------------|------------|
| **PHP** | 8.2+ | Cek: `php -v` |
| **Composer** | 2.x | Cek: `composer -V` |
| **Node.js** | 18+ | Cek: `node -v` |
| **npm** | 9+ | Cek: `npm -v` |
| **XAMPP / Laragon** | Terbaru | Menyediakan Apache + MySQL + phpMyAdmin |

> 💡 **Rekomendasi**: Gunakan **XAMPP** atau **Laragon** agar Apache, MySQL, dan phpMyAdmin langsung tersedia.

---

## 🚀 Instalasi & Setup (Step by Step)

### 1. Clone Repositori

```bash
git clone <url-repo>
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi JavaScript

```bash
npm install
```

### 4. Konfigurasi File `.env`

Copy file `.env.example` menjadi `.env`:

```bash
# Linux / macOS
cp .env.example .env

# Windows (CMD)
copy .env.example .env
```

Lalu **buka file `.env`** dan sesuaikan konfigurasi berikut:

```env
# ── Aplikasi ──────────────────────────────────
APP_NAME=CaloriMate
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

# ── Database ──────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=calorimate
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ **Penting**:
> - `DB_DATABASE` = nama database yang akan dibuat di phpMyAdmin (lihat langkah 5)
> - `DB_USERNAME` = username MySQL, biasanya `root` di XAMPP/Laragon
> - `DB_PASSWORD` = password MySQL, biasanya **kosong** di XAMPP (biarkan kosong)
> - `APP_TIMEZONE` = sesuaikan zona waktu, contoh: `Asia/Jakarta`

### 5. Buat Database di phpMyAdmin

1. Buka **XAMPP Control Panel** → Start **Apache** dan **MySQL**
2. Buka browser, akses **http://localhost/phpmyadmin**
3. Klik tab **"Databases"** (atau "Basis Data")
4. Di kolom **"Create database"**, ketik: `calorimate`
5. Pilih collation: **`utf8mb4_unicode_ci`**
6. Klik **"Create"**

> Nama database harus sama persis dengan `DB_DATABASE` di file `.env`

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Jalankan Migrasi Database

```bash
php artisan migrate
```

Perintah ini akan membuat semua tabel yang dibutuhkan:
- `user` — data pengguna (admin, client, trainer)
- `client` — profil client (BB, TB, umur, gender, target kalori)
- `trainers` — profil trainer
- `foods` — database makanan
- `programs` — program latihan
- `program_items` — item/gerakan dalam program
- `food_consumptions` — catatan konsumsi makanan harian
- `histories` — riwayat aktivitas
- `notifications` — notifikasi pengguna
- `sessions` — session management
- `personal_access_tokens` — token API (Sanctum)

### 8. Jalankan Seeder (Data Sample)

```bash
php artisan db:seed
```

Seeder akan mengisi data awal:

| Seeder | Data |
|--------|------|
| `AdminSeeder` | 1 admin (`admin` / `admin123`) |
| `UserSeeder` | 2 trainer + 5 client (password: `password`) |
| `TrainerSeeder` | Profil trainer |
| `ClientSeeder` | Profil client (BB, TB, umur, gender) |
| `FoodSeeder` | 33 makanan Indonesia |
| `ProgramSeeder` | 6 program latihan + items |
| `HistorySeeder` | 14 hari riwayat per client |

> 💡 Untuk **reset database + re-seed** sekaligus:
> ```bash
> php artisan migrate:fresh --seed
> ```

### 9. Buat Symbolic Link untuk Storage

```bash
php artisan storage:link
```

> Ini diperlukan agar file upload (foto profil, dll) bisa diakses dari browser.

### 10. Build Aset Frontend

```bash
# Mode development (hot reload)
npm run dev

# Atau mode production
npm run build
```

### 11. Jalankan Server

**Opsi A** — Dua terminal terpisah (Recommended):
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (untuk hot reload CSS/JS)
npm run dev
```

**Opsi B** — Satu perintah (via Composer script):
```bash
composer dev
```

Buka browser dan akses: **http://localhost:8000**

---

## 🔑 Akun Default (Setelah Seeding)

| Role | Username | Email | Password |
|------|----------|-------|----------|
| Admin | `admin` | `admin@calorimate.com` | `admin123` |
| Trainer | `trainer1` | `trainer1@calorimate.com` | `password` |
| Trainer | `trainer2` | `trainer2@calorimate.com` | `password` |
| Client | `client1` | `client1@calorimate.com` | `password` |
| Client | `client2` | `client2@calorimate.com` | `password` |
| Client | `client3` | `client3@calorimate.com` | `password` |
| Client | `client4` | `client4@calorimate.com` | `password` |
| Client | `client5` | `client5@calorimate.com` | `password` |

---

## ⚙️ Roles dan Akses

| Role | Kode | Akses |
|------|------|-------|
| Admin | `role=0` | Dashboard admin, kelola semua data |
| Client | `role=1` | Dashboard client, diary, exercise, history, statistik |
| Trainer | `role=2` | CRUD foods, CRUD programs, CRUD items, profil |

Middleware proteksi:
- `auth.required` — harus login
- `role:1` — khusus client
- `role:2` — khusus trainer

---

## 📡 API Endpoints

Base URL: `http://localhost:8000/api`

### Public
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/login` | Login (return token) |
| POST | `/api/register/client` | Register client baru |
| POST | `/api/register/trainer` | Register trainer baru |

### Client (Bearer Token + role:1)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/client/profile` | Lihat profil |
| POST | `/api/client/profile` | Update profil (multipart) |
| GET | `/api/client/foods` | Cari makanan |
| GET | `/api/client/diary` | Diary hari ini |
| POST | `/api/client/diary` | Tambah makanan ke diary |
| DELETE | `/api/client/diary` | Hapus makanan dari diary |
| GET | `/api/client/history` | Riwayat (filter: period) |
| GET | `/api/client/statistic` | Statistik harian |
| GET | `/api/client/exercise` | Daftar program |
| GET | `/api/client/exercise/{id}` | Detail program |
| POST | `/api/client/exercise/{id}/start` | Mulai program |
| GET | `/api/client/notifications` | Daftar notifikasi |
| POST | `/api/client/notifications/read` | Tandai dibaca |
| GET | `/api/client/recommendations/food` | Rekomendasi makanan |
| GET | `/api/client/recommendations/exercise` | Rekomendasi latihan |
| GET | `/api/client/recommendations/weekly` | Ringkasan mingguan |

### Trainer (Bearer Token + role:2)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/trainer/profile` | Lihat profil |
| POST | `/api/trainer/profile` | Update profil (multipart) |
| GET/POST | `/api/trainer/foods` | List / Tambah makanan |
| GET/PUT/DELETE | `/api/trainer/foods/{id}` | Detail / Edit / Hapus makanan |
| GET/POST | `/api/trainer/programs` | List / Tambah program |
| GET/PUT/DELETE | `/api/trainer/programs/{id}` | Detail / Edit / Hapus program |
| GET/POST | `/api/trainer/programs/{id}/items` | List / Tambah item latihan |
| GET/PUT/DELETE | `/api/items/{id}` | Detail / Edit / Hapus item |

---

## 🗂️ Struktur Project

```
CaloriMate/
├── app/
│   ├── Http/Controllers/       # Controller web & API
│   │   ├── Api/
│   │   │   ├── AuthController.php
│   │   │   ├── Client/         # API client (diary, exercise, dll)
│   │   │   └── Trainer/        # API trainer (foods, programs, dll)
│   │   ├── Auth/               # Web auth controllers
│   │   ├── Client/             # Web client controllers
│   │   └── Trainer/            # Web trainer controllers
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Skema database (21 migration)
│   └── seeders/                # Data sample (8 seeder)
├── resources/views/            # Blade templates
├── routes/
│   ├── web.php                 # Routes halaman web
│   └── api.php                 # Routes API
├── .env.example                # Template environment
├── composer.json               # PHP dependencies
└── package.json                # JS dependencies
```

---

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Cek route yang terdaftar
php artisan route:list

# Cek route API saja
php artisan route:list --path=api
```

---

## 🔧 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| `SQLSTATE[HY000] [1049] Unknown database` | Pastikan database `calorimate` sudah dibuat di phpMyAdmin |
| `SQLSTATE[HY000] [2002] Connection refused` | Pastikan MySQL sudah running di XAMPP |
| `Vite manifest not found` | Jalankan `npm run dev` atau `npm run build` |
| `Class not found` | Jalankan `composer dump-autoload` |
| Foto profil tidak muncul | Jalankan `php artisan storage:link` |
| `APP_KEY is empty` | Jalankan `php artisan key:generate` |

---

## ✅ Tips

- Gunakan `php artisan route:list` untuk cek semua route
- Gunakan `php artisan migrate:fresh --seed` untuk reset DB selama pengembangan
- Untuk debugging tampilan, periksa `resources/views` dan `app/Http/Controllers`
- Gunakan `php artisan tinker` untuk test query database secara interaktif
- API testing bisa menggunakan **Postman** atau **Thunder Client** (VS Code extension)

---

> CaloriMate memudahkan trainer dan client berkolaborasi untuk mencapai target nutrisi dan kebugaran dengan cara yang terstruktur, modern, dan user-friendly.
