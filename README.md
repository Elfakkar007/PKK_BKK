# Website Bursa Kerja Khusus SMKN 1 Purwosari

Portal kerja yang menghubungkan alumni/siswa dengan perusahaan yang bekerja sama dengan sekolah.

## Fitur Utama

### Untuk Pengunjung Umum
- Melihat informasi BKK (berita, dokumentasi, panduan)
- Melihat profil perusahaan mitra
- Melihat lowongan pekerjaan/magang yang tersedia
- Melihat visi misi, program kerja, dan struktur organisasi BKK

### Untuk Siswa/Alumni
- Registrasi dan login
- Melamar lowongan pekerjaan/magang
- Melacak status lamaran
- Mengelola profil dan CV
- Menerima notifikasi email

### Untuk Perusahaan
- Registrasi dan login
- Mengelola profil perusahaan
- Upload lowongan pekerjaan/magang
- Mengelola lamaran yang masuk
- Memberikan feedback kepada pelamar
- Menerima notifikasi email

### Untuk Admin
- Approve/Reject registrasi user
- Approve/Reject lowongan yang di-upload perusahaan
- CRUD konten (berita, dokumentasi, panduan)
- Mengelola halaman Tentang (visi misi, program kerja, struktur organisasi)
- Mengelola highlights di beranda
- Mengatur settings website
- Dashboard statistik lengkap

## Tech Stack

- **Framework**: Laravel 11
- **Database**: PostgreSQL
- **Frontend**: Bootstrap 5
- **Authentication**: Laravel Breeze/Custom
- **Email**: Laravel Mail + Queue
- **File Storage**: Laravel Storage (public disk)

## Requirements

- PHP 8.2 or higher
- Composer
- PostgreSQL 14 or higher
- Node.js & NPM (untuk asset compilation)

## Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd bkk-smkn1-purwosari
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bkk_smkn1_purwosari
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Konfigurasi email (contoh menggunakan Gmail):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bkk-smkn1purwosari.sch.id"
```

### 4. Create Database

```bash
createdb bkk_smkn1_purwosari
```

Atau melalui PostgreSQL:

```sql
CREATE DATABASE bkk_smkn1_purwosari;
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Admin User

```bash
php artisan db:seed --class=AdminSeeder
```

Default admin credentials:
- Email: `admin@bkk-smkn1purwosari.sch.id`
- Password: `password123`

**PENTING**: Segera ganti password setelah login pertama kali!

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Compile Assets

Development:
```bash
npm run dev
```

Production:
```bash
npm run build
```

### 9. Setup Queue Worker (Opsional tapi Disarankan)

Untuk menjalankan email notifications:

```bash
php artisan queue:work
```

Untuk production, gunakan Supervisor atau systemd untuk menjaga queue worker tetap running.

### 10. Run Development Server

```bash
php artisan serve
```

Website dapat diakses di: `http://localhost:8000`

## Struktur Database

### Users & Authentication
- `users` - Tabel utama user (admin, student, company)
- `students` - Data detail siswa/alumni
- `companies` - Data detail perusahaan
- `password_reset_tokens` - Token reset password
- `sessions` - Session management

### Job System
- `job_vacancies` - Lowongan pekerjaan/magang
- `applications` - Lamaran yang masuk

### Content Management
- `posts` - Berita, dokumentasi, panduan
- `about_content` - Konten halaman Tentang
- `highlights` - Sorotan di beranda
- `settings` - Pengaturan website

### Notifications
- `notifications` - Notifikasi in-app
- `email_logs` - Log pengiriman email

## File Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php
│   │   ├── Admin/
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── UserManagementController.php
│   │   │   ├── VacancyManagementController.php
│   │   │   └── ...
│   │   ├── Company/
│   │   │   └── CompanyDashboardController.php
│   │   ├── Student/
│   │   │   └── StudentDashboardController.php
│   │   └── HomeController.php
│   └── Middleware/
│       ├── CheckRole.php
│       └── CheckApproved.php
├── Models/
│   ├── User.php
│   ├── Student.php
│   ├── Company.php
│   ├── JobVacancy.php
│   ├── Application.php
│   └── Post.php
└── Notifications/
    ├── RegistrationNotification.php
    ├── AccountApprovedNotification.php
    ├── ApplicationSubmittedNotification.php
    └── ...
```

## User Roles & Permissions

### Admin
- Full access ke semua fitur
- Approve/reject user registrations
- Approve/reject job vacancies
- Manage all content

### Company
- Manage company profile
- Create/edit/delete job vacancies
- Review applications
- Update application status

### Student
- Manage personal profile
- Apply for job vacancies
- Track application status
- Upload CV

## Email Notifications

System mengirim email otomatis untuk:

1. **Registrasi**
   - Konfirmasi registrasi berhasil
   - Menunggu approval

2. **Approval/Rejection**
   - Akun disetujui
   - Akun ditolak (dengan alasan)

3. **Lowongan**
   - Lowongan disetujui
   - Lowongan ditolak

4. **Lamaran**
   - Notifikasi ke company saat ada lamaran baru
   - Notifikasi ke siswa saat status lamaran berubah

## Queue Configuration

Edit `config/queue.php` jika diperlukan. Untuk production, gunakan Redis atau database driver.

Jalankan queue worker:

```bash
# Development
php artisan queue:work

# Production (dengan Supervisor)
# Buat file /etc/supervisor/conf.d/bkk-worker.conf
```

## Security Checklist

- [ ] Ganti APP_KEY di production
- [ ] Set APP_DEBUG=false di production
- [ ] Ganti password admin default
- [ ] Setup proper file permissions (storage, bootstrap/cache)
- [ ] Enable HTTPS
- [ ] Setup CORS jika diperlukan
- [ ] Configure rate limiting
- [ ] Setup backup database reguler

## Troubleshooting

### Storage Permission Error
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Queue Not Working
```bash
# Clear failed jobs
php artisan queue:flush

# Restart queue worker
php artisan queue:restart
```

### Migration Error
```bash
# Rollback and re-run
php artisan migrate:fresh --seed
``` 

## License

Proprietary - SMKN 1 Purwosari
