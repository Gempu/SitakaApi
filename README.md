# Dokumentasi Sitaka API

## Cara Menjalankan Proyek

1. **Clone Repository**  
   Clone repository dari GitHub ke lokal dengan perintah berikut:
   ```bash
   git clone https://github.com/Gempu/SitakaApi.git
   ```

2. **Masuk ke Direktori Proyek**  
   Setelah proses clone selesai, masuk ke direktori proyek:
   ```bash
   cd repository-name
   ```

3. **Install Dependencies**  
   Jalankan perintah berikut untuk menginstal dependencies Laravel:
   ```bash
   composer install
   ```
   Jika Composer belum terinstal, silakan unduh dan instal terlebih dahulu: [Composer Download](https://getcomposer.org/download/).

4. **Buat File `.env`**  
   Salin file `.env` dari template `.env.example`:
   ```bash
   cp .env.example .env
   ```

5. **Generate APP_KEY**  
   Laravel memerlukan kunci aplikasi untuk enkripsi data. Buat kunci aplikasi dengan perintah berikut:
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database**  
   Edit file `.env` untuk menyesuaikan konfigurasi database Anda. Contoh:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_sitaka
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Membuat Database**  
   Jika belum memiliki database, buat database dengan langkah berikut:
   - Buka **phpMyAdmin**.
   - Buat database dengan nama `db_sitaka`.
   - Import file database yang dapat anda mintakan pada admin:  
     [Database File](https://drive.google.com).

8. **Mengedit Database**  
   Setelah import selesai, jalankan migrasi berikut untuk membuat tabel - tabel yang dibutuhkan :
   ```bash
   php artisan migrate --path=./database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php
   ```
   ```bash
   php artisan migrate --path=./database/migrations/2024_11_21_052920_create_ratings_table.php
   ```
   ```bash
   php artisan migrate --path=./database/migrations/2024_11_21_054941_create_notifications_table.php
   ```

   **Membuat Password Terenkripsi**  
   Jalankan perintah berikut di Tinker untuk menghasilkan password terenkripsi:
   ```bash
   php artisan tinker
   use Illuminate\Support\Facades\Hash;
   echo Hash::make('password');
   ```
   Salin hasil enkripsi password dan masukkan ke kolom `mpasswd` di tabel `member`.

   **Menambahkan Kolom Skor**  
   Tambahkan kolom `score` ke tabel `member` dengan default `0`:
   - Buka tabel `member`.
   - Pilih menu **Struktur**.
   - Tambahkan kolom baru bernama `score` dengan tipe integer dan default `0`.
   - Simpan perubahan.

   **Menambahkan Kolom Cover**  
   Tambahkan kolom `cover` ke tabel `biblio` dengan default `null`:
   - Buka tabel `biblio`.
   - Pilih menu **Struktur**.
   - Tambahkan kolom baru bernama `cover` dengan tipe varchar dan default `null`.
   - Simpan perubahan.     

9. **Jalankan Aplikasi Laravel**  
   Untuk menjalankan aplikasi Laravel, gunakan perintah berikut:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://127.0.0.1:8000`.

   Untuk aplikasi mobile, gunakan host komputer Anda:
   ```bash
   php artisan serve --host=<host-komputer> --port=8000
   ```
   Aplikasi dapat diakses di `http://<host-komputer>:8000`.

---

## API Response

### 1. **Login**  
**Endpoint:**  
```http
POST http://127.0.0.1:8000/api/login
```

**Request:**
```json
{
    "email": "alwandanny01@gmail.com",
    "password": "password"
}
```

**Response:**
```json
{
    "code": "200",
    "status": "OK",
    "data": {
        "member": "Alwan Danny Latif",
        "token": "9|gPYVAj5yQRmg6Q0KMS802147i7OKmD7UrLi57yKQa8162847"
    }
}
```

---

### 2. **Home**  
**Endpoint:**  
```http
GET http://127.0.0.1:8000/api/home
```

**Header:**
```json
{
    "Authorization": "Bearer <token>"
}
```

**Response:**  
(Custom sesuai data aplikasi Anda)

---

### 3. **Daftar Buku**  
**Endpoint:**  
```http
GET http://127.0.0.1:8000/api/book
```

**Response:**  
(Custom sesuai data aplikasi Anda)

---

### 4. **Detail Buku**  
**Endpoint:**  
```http
GET http://127.0.0.1:8000/api/book/<idbuku>
```

---

### 5. **Peringkat**  
**Endpoint:**  
```http
GET http://127.0.0.1:8000/api/ranking
```

---

### 6. **Ganti Password**  
**Endpoint:**  
```http
POST http://127.0.0.1:8000/api/change-password
```

**Request:**
```json
{
    "current_password": "password_lama",
    "new_password": "password_baru",
    "new_password_confirmation": "password_baru"
}
```

**Response:**
```json
{
    "code": "200",
    "status": "OK",
    "data": {
        "message": "Password changed successfully"
    }
}
```

---
