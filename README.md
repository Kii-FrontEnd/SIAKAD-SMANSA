# SIAKAD-SMANSA

Sistem Informasi Akademik (SIAKAD) untuk Sekolah Menengah Atas Negeri 1 (SMANSA). Proyek ini adalah aplikasi berbasis web yang dirancang untuk memfasilitasi pengelolaan data akademik sekolah secara terpusat dan efisien.

---

## 🚀 Fitur Utama

Proyek SIAKAD ini mencakup modul-modul utama yang mendukung kegiatan akademik sekolah:

* **Manajemen Akun Bertingkat:** Memisahkan akses fungsionalitas untuk tiga jenis pengguna utama:
    * **Admin:** Pengelolaan sistem, data master (kelas, mata pelajaran, tahun ajaran), dan pengguna.
    * **Guru:** Input dan pengelolaan nilai siswa, pembuatan jadwal, dan rekapitulasi kehadiran.
    * **Siswa:** Melihat informasi akademik pribadi seperti jadwal pelajaran, daftar nilai (KHS/Raport), dan data kehadiran.
* **Pengelolaan Nilai:** Modul untuk memasukkan, mengedit, dan memfinalisasi nilai akhir siswa.
* **Jadwal Pelajaran:** Fitur untuk membuat, menyimpan, dan menampilkan jadwal pelajaran per kelas.
* **Data Master Lengkap:** Pengelolaan data dasar sekolah seperti data siswa, data guru, mata pelajaran, dan kelas.
* **Laporan Akademik:** Mampu menghasilkan laporan-laporan penting seperti Raport siswa dalam format cetak.

---

## 🛠️ Teknologi yang Digunakan

Proyek ini dikembangkan menggunakan tumpukan teknologi yang didominasi oleh ekosistem PHP dan Laravel, mengadopsi pola arsitektur **Model-View-Controller (MVC)**.

* **Backend Framework:** **Laravel** (PHP)
* **Bahasa Pemrograman:** PHP, JavaScript
* **Database:** MySQL / MariaDB (Direkomendasikan)
* **Templating Engine:** Blade
* **Frontend:** HTML5, CSS3, JavaScript (Aset dikompilasi menggunakan **Webpack Mix**)

---

## ⚙️ Persyaratan Instalasi

Untuk menjalankan proyek ini di lingkungan lokal Anda, pastikan Anda telah menginstal perangkat lunak berikut:

* **Web Server:** Apache atau Nginx (Direkomendasikan menggunakan XAMPP, WAMP, atau Laragon).
* **PHP:** Versi 8.0 atau lebih tinggi.
* **Composer:** Manajer dependensi PHP.
* **Node.js & npm:** Untuk menginstal dan mengkompilasi aset frontend.

### Langkah-Langkah Instalasi

1.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/Kii-FrontEnd/SIAKAD-SMANSA.git](https://github.com/Kii-FrontEnd/SIAKAD-SMANSA.git)
    cd SIAKAD-SMANSA
    ```

2.  **Instal Dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment:**
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan atur detail koneksi database Anda (contoh):

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=user_database_anda
    DB_PASSWORD=password_database_anda
    ```

4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database:**
    ```bash
    php artisan migrate
    # Jika ada data awal (seeder), jalankan:
    # php artisan db:seed
    ```

6.  **Instal dan Kompilasi Aset Frontend:**
    ```bash
    npm install
    npm run dev  # Untuk pengembangan
    # npm run prod # Untuk produksi
    ```

---

## 📁 Susunan Project

Struktur folder utama proyek mengikuti standar kerangka kerja Laravel:

````

.
├── app/                  \# Logika inti aplikasi (Model, Controller, Providers)
├── bootstrap/            \# File bootstrapping framework
├── config/               \# File konfigurasi sistem
├── database/             \# Migrasi, Seeder, Factory
├── public/               \# Direktori root yang dapat diakses publik
├── resources/            \# Berisi View (Blade) dan aset mentah (CSS/JS)
├── routes/               \# Semua definisi route (web, api)
├── storage/              \# Cache, sesi, log, dan file yang diunggah
├── tests/                \# Unit dan fitur test
├── vendor/               \# Dependensi Composer
├── artisan               \# Command line interface Laravel
├── composer.json         \# Daftar dependensi PHP
└── package.json          \# Daftar dependensi Node.js/npm

````

---

## 🖥️ Contoh Penggunaan

Setelah instalasi selesai, Anda dapat menjalankan aplikasi menggunakan *server* pengembangan bawaan Laravel:

```bash
php artisan serve
````

Aplikasi akan tersedia di *browser* Anda pada alamat `http://localhost:8000`.

-----

## 🤝 Kontribusi

Kontribusi dalam bentuk *pull request* (PR), laporan *bug*, atau saran fitur sangat kami hargai.

Untuk berkontribusi, ikuti langkah-langkah berikut:

1.  *Fork* repositori ini.
2.  Buat *branch* baru untuk fitur Anda (`git checkout -b feature/nama-fitur-baru`).
3.  Lakukan *commit* perubahan Anda (`git commit -m 'feat: Tambahkan fitur X'`).
4.  *Push* ke *branch* tersebut (`git push origin feature/nama-fitur-baru`).
5.  Buka *Pull Request* baru (PR).

-----

## 📜 Lisensi

Proyek ini dirilis di bawah Lisensi **MIT**.

```
MIT License

Copyright (c) 2022 Pascal Adnan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

```
