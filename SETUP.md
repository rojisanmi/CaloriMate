# CaloriMate — Panduan Setup & Menjalankan

Panduan setup untuk **backend web (Laravel)** dan **aplikasi mobile (Flutter)**.
Ada beberapa hal baru yang **wajib** diperhatikan: **tabel database baru** (migration),
**Firebase Cloud Messaging (FCM)** untuk push notification, dan **scheduler** untuk reminder.

> Repo:
> - Web: `https://github.com/rojisanmi/CaloriMate`
> - Mobile: `https://github.com/rojisanmi/CaloriMate_Mobile`

---

## A. Backend Web (Laravel)

### 1. Prasyarat
- PHP **8.2+** (mis. XAMPP)
- Composer
- MySQL (buat database kosong bernama `calorimate`)

### 2. Clone & install dependency
```bash
git clone https://github.com/rojisanmi/CaloriMate
cd CaloriMate
git checkout iqbal          # branch yang berisi fitur FCM (atau branch terbaru)
composer install
```

### 3. Konfigurasi `.env`
```bash
cp .env.example .env
php artisan key:generate
```
Lalu edit `.env`, pastikan minimal seperti ini:
```env
APP_TIMEZONE=Asia/Jakarta      # PENTING: jangan UTC, agar jam reminder benar

DB_DATABASE=calorimate
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=database           # butuh tabel cache (dibuat oleh migration)
QUEUE_CONNECTION=database

# Firebase (push notification) — lihat langkah 5
FIREBASE_CREDENTIALS=storage/app/firebase/firebase_credentials.json
```

### 4. Migrasi database (ADA TABEL BARU)
```bash
php artisan migrate
```
Migration akan membuat/menyesuaikan tabel, termasuk yang **baru**:
| Tabel / kolom | Fungsi |
|---|---|
| `cache`, `cache_locks` | dibutuhkan `CACHE_STORE=database` & scheduler (`withoutOverlapping`) |
| `device_tokens` | menyimpan token FCM tiap perangkat (untuk push) |
| `history_programs` | relasi histori program latihan |
| kolom reminder di `clients` | `food_reminder_time`, `exercise_reminder_time` |
| kolom di `notifications` | `type`, `title`, `icon` |

> Kalau DB masih kosong total dan ada seeder: `php artisan migrate --seed`.

### 5. Kredensial Firebase (WAJIB untuk push notification)
File **service account** Firebase **tidak ada di repo** (rahasia, di-`.gitignore`).
Minta file ini ke ketua tim, lalu:
1. Buat folder `storage/app/firebase/` (kalau belum ada).
2. Taruh file di `storage/app/firebase/firebase_credentials.json`.
3. Pastikan baris `FIREBASE_CREDENTIALS=...` ada di `.env` (lihat langkah 3).

> Alternatif (kalau punya akses Firebase Console project `calorimate-6f8b0`):
> Project Settings → Service accounts → **Generate new private key** → rename jadi
> `firebase_credentials.json` → taruh di `storage/app/firebase/`.

### 6. Jalankan (butuh 2 terminal)
**Terminal 1 — web server** (pakai `0.0.0.0` agar HP bisa akses):
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
**Terminal 2 — scheduler** (WAJIB agar reminder & push terkirim tiap menit):
```bash
php artisan schedule:work
```
> ⚠️ Tanpa `schedule:work`, notifikasi pengingat **tidak akan terkirim**.
> Jalankan hanya **satu** instance (jangan dobel — bisa bikin notif duplikat).

Cek IP LAN laptopmu (untuk dipakai di mobile):
```bash
ipconfig        # cari IPv4 di adapter Wi-Fi/Ethernet, mis. 192.168.x.x
```

---

## B. Aplikasi Mobile (Flutter)

### 1. Prasyarat
- Flutter SDK (Dart **3.11+**)
- Android device/emulator (FCM saat ini **Android-only**)
- `google-services.json` **sudah ada di repo** (`android/app/`), jadi tidak perlu setup Firebase manual.

### 2. Clone & install
```bash
git clone https://github.com/rojisanmi/CaloriMate_Mobile
cd CaloriMate_Mobile
git checkout iqbal
flutter pub get
```

### 3. Arahkan ke server (WAJIB diganti)
Edit `lib/config/api_config.dart`, ganti IP ke **IP LAN laptop yang menjalankan Laravel**
(dari `ipconfig` di langkah A.6):
```dart
class ApiConfig {
  static const String baseUrl = 'http://192.168.x.x:8000/api/';
  static const String storageUrl = 'http://192.168.x.x:8000/storage/';
}
```
> HP dan laptop harus berada di **jaringan/Wi-Fi yang sama**.
> Untuk emulator Android, pakai `http://10.0.2.2:8000/...`.

### 4. Jalankan
```bash
flutter run
```
Atau build APK: `flutter build apk --debug`.

---

## C. Cara kerja notifikasi (ringkas)
- Saat client set waktu reminder (web/mobile), waktunya disimpan di server.
- `schedule:work` menjalankan `notifications:generate` **tiap menit**; saat jam cocok,
  ia membuat notifikasi (muncul di lonceng/web) **dan** mengirim **push FCM** ke HP.
- Push muncul di HP walau app tertutup. **Tap** notifikasi membuka tab terkait
  (makan → Diary, olahraga → Exercise).
- Satu reminder per tipe **per waktu** (ganti jam = tetap dapat; jam sama tidak dobel).

---

## D. Checklist cepat
**Web**
- [ ] `composer install`
- [ ] `.env` dikonfigurasi (`APP_TIMEZONE=Asia/Jakarta`, DB, `FIREBASE_CREDENTIALS`)
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate`
- [ ] `firebase_credentials.json` ada di `storage/app/firebase/`
- [ ] `php artisan serve --host=0.0.0.0 --port=8000`
- [ ] `php artisan schedule:work`

**Mobile**
- [ ] `flutter pub get`
- [ ] IP server diisi di `lib/config/api_config.dart`
- [ ] HP & laptop satu Wi-Fi
- [ ] `flutter run`

---

## E. Troubleshooting
| Masalah | Penyebab / solusi |
|---|---|
| HP tidak bisa konek ke API | Pakai `--host=0.0.0.0`; cek IP di `api_config.dart`; HP & laptop satu Wi-Fi; izinkan port 8000 di firewall |
| Push tidak masuk | `schedule:work` belum jalan; `firebase_credentials.json` tidak ada/salah; HP belum login (token belum terdaftar) |
| Notifikasi dobel | Ada **lebih dari satu** `schedule:work` berjalan — sisakan satu |
| Error `cache_locks` doesn't exist | Lupa `php artisan migrate` (tabel cache belum dibuat) |
| Jam reminder meleset | `APP_TIMEZONE` belum `Asia/Jakarta` |
| `flutter run` error dependency | `flutter pub get`, pastikan Flutter/Dart versi terbaru |
