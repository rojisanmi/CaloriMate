# CaloriMate

![Project Badge](https://img.shields.io/badge/Status-Work%20in%20Progress-orange)
![License](https://img.shields.io/badge/License-MIT-blue)

## 📌 Ringkasan
CaloriMate adalah aplikasi manajemen nutrisi & latihan berbasis web yang dibangun dengan Laravel (PHP). Aplikasi ini dibuat untuk:
- membantu trainer membuat dan mengelola database makanan, program olahraga, dan item latihan;
- membantu client mencatat konsumsi makanan, memantau histori, dan menjalankan program olahraga secara lebih terstruktur;
- memberi metrik kemajuan kesehatan dan aktivitas fisik.

## 🎯 Fitur Utama
1. Autentikasi dan otorisasi:
   - role `trainer` & `client`
   - middleware `auth.required`, `role:1` (client), `role:2` (trainer)
2. Trainer:
   - CRUD `foods`
   - CRUD `programs`
   - CRUD `program items` (item latihan per program)
   - Profil trainer (update data trainer)
3. Client:
   - Dashboard client (`/client/home`)
   - Diary makanan:
     - tambah catatan makanan (`/client/diary/add/{category}`)
     - simpan / hapus makanan harian
   - Riwayat konsumsi (`/client/history`)
   - Statistik kalori / aktivitas (`/client/statistic`)
   - Program olahraga interaktif:
     - daftar latihan (`/client/exercise`)
     - detail latihan (`/client/exercise/{id}`)
     - mulai / play latihan
   - Profil client (update data pengguna)
4. Guest:
   - Beranda umum (`/`)
   - Login dan register (umum, trainer, client)

## 🗂️ Struktur Utama
- `app/Models`: `User`, `Client`, `Trainer`, `Food`, `Program`, `ProgramItem`, `FoodConsumption`, `History`
- `app/Http/Controllers`: pengelolaan otentikasi, profil, diary, history, statistik, exercise dll.
- `resources/views`: blade view untuk semua interface (auth, client, trainer, formulir CRUD, dll)
- `routes/web.php`: routing utama app
- `database/migrations`: skema DB untuk users, clients, trainers, foods, programs, program_items, food_consumptions, histories, notifications, sessions

## 🛠️ Instalasi & Setup (lokal)
1. Clone repositori:
   - `git clone <url-repo>`
   - `cd CaloriMate`
2. Install dependensi PHP & JS:
   - `composer install`
   - `npm install`
3. Copy `.env`:
   - `cp .env.example .env` (atau `copy .env.example .env` di Windows)
   - set `APP_URL`, `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
4. Generate key aplikasi:
   - `php artisan key:generate`
5. Migrasi database & seeding (opsional):
   - `php artisan migrate`
   - `php artisan db:seed` (jika data sample ada/diatur)
6. Jalankan build aset:
   - `npm run dev`
7. Jalankan server:
   - `php artisan serve`
   - buka `http://127.0.0.1:8000`

## ⚙️ Roles dan Akses
- `role=1` => Client
- `role=2` => Trainer
- Akses proteksi ada di middleware:
  - `auth.required`
  - `role:1`, `role:2`

## 🧪 Testing
- Jalankan unit/feature test:
  - `php artisan test`
- file test ada di `tests/Feature` dan `tests/Unit`

## 🧾 Kontribusi
1. Fork repo ini.
2. Buat branch fitur: `feature/nama-fitur`
3. Commit perubahan dengan pesan jelas.
4. Push dan buat Pull Request.

## ✅ Tips Pengembangan
- Gunakan `php artisan route:list` untuk cek route.
- Gunakan `php artisan migrate:fresh --seed` untuk reset DB selama pengembangan.
- Untuk debugging tampilan, periksa `resources/views` dan `app/Http/Controllers`.

## 📄 Lisensi
Proyek ini dirilis di bawah lisensi MIT.

---
> CaloriMate memudahkan trainer dan client berkolaborasi untuk mencapai target nutrisi dan kebugaran dengan cara yang terstruktur, modern, dan user-friendly.

