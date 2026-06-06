# Checklist Progress Tubes CaloriMate (Web Platform)

## 1. Setup & Arsitektur Dasar
- [ ] [cite_start]Inisialisasi *project* Laravel (Backend & REST API) dan Tailwind CSS (Frontend).
- [ ] [cite_start]Implementasi *database* MySQL sesuai rancangan.
- [ ] [cite_start]Setup autentikasi menggunakan JWT Token / Laravel Sanctum.

## 2. Fitur Role: Regular User (Web)
- [ ] **Autentikasi & Profil**
  - [ ] [cite_start]Fitur Registrasi dan Login[cite: 178].
  - [ ] [cite_start]Form kelola profil pengguna: nama, berat, tinggi, usia, dan target kalori[cite: 178].
- [ ] **Modul Makanan & Kalori**
  - [ ] [cite_start]Fitur catat/input makanan berdasarkan kategori waktu makan (sarapan, makan siang, makan malam, *snack*)[cite: 55, 179].
  - [ ] [cite_start]Kalkulasi otomatis total kalori harian dari makanan yang diinput[cite: 179].
- [ ] **Modul Latihan**
  - [ ] [cite_start]Halaman untuk melihat dan mendaftar (*enroll*) ke Program Latihan yang tersedia[cite: 59, 179].
- [ ] **Dashboard & Statistik**
  - [ ] [cite_start]Grafik perbandingan kalori masuk vs. target kalori harian[cite: 57, 179].
  - [ ] [cite_start]Halaman *History* riwayat lengkap aktivitas (konsumsi makanan dan olahraga)[cite: 57, 179].

## 3. Fitur Role: Admin / Trainer (Web)
- [ ] **Autentikasi Khusus**
  - [ ] [cite_start]Sistem *login* melalui *endpoint* khusus (hanya Trainer yang bisa menambah/mengubah konten)[cite: 74, 181].
- [ ] **Manajemen Konten (CRUD)**
  - [ ] [cite_start]**Kelola Data Makanan:** Form tambah, edit, hapus, dan lihat daftar makanan dengan atribut spesifik: nama, gramasi, porsi, kalori, lemak, karbohidrat, dan protein[cite: 71, 181].
  - [ ] [cite_start]**Kelola Item Latihan:** Form tambah gerakan, atur durasi, dan *setting* estimasi kalori yang terbakar[cite: 73, 181].
  - [ ] [cite_start]**Kelola Program Latihan:** Form buat program baru, edit deskripsi, dan atur tingkat kesulitan[cite: 76, 181].
- [ ] **Dashboard Statistik Global**
  - [ ] [cite_start]Laporan agregat jumlah pengguna aktif[cite: 78, 181].
  - [ ] [cite_start]Menampilkan daftar makanan paling populer / sering dicatat[cite: 78, 181].
  - [ ] [cite_start]Menampilkan daftar program latihan terpopuler[cite: 78, 181].

## 4. Intelligent System / Rule-Based Engine (Backend PHP)
- [ ] [cite_start]*Engine* pembaca data BMI, target kalori, riwayat, dan level aktivitas[cite: 103].
- [ ] [cite_start]**Rekomendasi Menu Cerdas:** *Logic* untuk menyarankan kombinasi menu berdasarkan sisa kuota kalori hari ini, preferensi historis, dan nilai gizi[cite: 61, 105, 179].
- [ ] [cite_start]**Rekomendasi Latihan Cerdas:** *Logic* untuk menyarankan jenis dan intensitas latihan berdasarkan BMI pengguna, aktual surplus/defisit kalori, dan target[cite: 64, 108, 179].
- [ ] [cite_start]**Evaluasi Progress Mingguan:** Sistem agregasi *summary* pola makan dan olahraga 7 hari terakhir beserta *output* saran perbaikan konkret[cite: 65, 111, 179].

## 5. Kesiapan REST API (Untuk Integrasi Mobile Selanjutnya)
[cite_start]*Pastikan endpoint berikut sudah siap dikonsumsi oleh tim Frontend Mobile (Flutter)*[cite: 81, 183].
- [ ] [cite_start]*Endpoint* API CRUD Profil & Autentikasi (JWT)[cite: 50, 186].
- [ ] [cite_start]*Endpoint* API Input & Hapus Log Makanan[cite: 85, 187].
- [x] [cite_start]*Endpoint* API untuk mengambil data Rekomendasi (Intelligent System).
- [ ] [cite_start]*Endpoint* API Kalkulasi/Statistik Kalori[cite: 86, 187].