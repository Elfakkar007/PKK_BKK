# 🚀 Quick Start Guide - Email Notifications

## Aktivasi Cepat (5 Menit)

### 1️⃣ Setup Gmail SMTP

Edit `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=BKK SMKN 1 PURWOSARI
```

### 2️⃣ Setup Database Queue

```bash
php artisan queue:table
php artisan migrate
php artisan config:clear
php artisan cache:clear
```

### 3️⃣ Jalankan Queue Worker

Di terminal terpisah:
```bash
php artisan queue:work
```

### 4️⃣ Test Email

```bash
php artisan tinker
```

Kemudian:
```php
use App\Models\User;
use App\Notifications\RegistrationNotification;

$user = User::first();
$user->notify(new RegistrationNotification());
```

Cek email user untuk menerima notifikasi!

---

## ✅ Status Aktivasi

- ✅ **8 Email Notifications** sudah integrated
- ✅ **Semua notifications** menggunakan queue
- ✅ **SMTP Config** siap digunakan
- ✅ **Contact Form Email** aktif
- ✅ **Database Queue** configured

## 🔗 Email Notifications yang Aktif

| # | Notification | Trigger | Dikirim ke |
|---|---|---|---|
| 1 | Registration | User baru registrasi | User |
| 2 | Account Approved | Admin approve akun | User |
| 3 | Account Rejected | Admin reject akun | User |
| 4 | Application Submitted | Student submit lamaran | Perusahaan |
| 5 | Application Status | Company update status | Student |
| 6 | Vacancy Approved | Admin approve vacancy | Perusahaan |
| 7 | Vacancy Rejected | Admin reject vacancy | Perusahaan |
| 8 | Contact Form | User kirim pesan kontak | Admin |

## 🐛 Troubleshooting Cepat

### Email tidak terkirim?
```bash
# 1. Cek queue worker berjalan
ps aux | grep "artisan queue:work"

# 2. Cek jobs di database
php artisan queue:failed

# 3. Lihat logs
tail -f storage/logs/laravel.log

# 4. Retry failed jobs
php artisan queue:retry all
```

### SMTP Connection Error?
```bash
# Test SMTP credentials
php artisan tinker

# Verify mail config
php artisan config:show mail
```

### Queue jobs tidak diproses?
```bash
# Cek queue driver
grep QUEUE_CONNECTION .env

# Jalankan queue worker
php artisan queue:work --verbose
```

## 📚 Dokumentasi Lengkap

- `EMAIL_SETUP.md` - Konfigurasi SMTP detail
- `QUEUE_EMAIL_SETUP.md` - Setup queue & production
- `setup-email-notifications.sh` - Setup script (Linux/Mac)
- `setup-email-notifications.bat` - Setup script (Windows)

## 🎯 Next Steps

1. ✅ Selesaikan setup di atas
2. 🔧 Adjust MAIL_FROM_ADDRESS sesuai domain
3. 📧 Test semua notifications
4. 🚀 Deploy ke production dengan supervisor/systemd
5. 📊 Monitor queue status regularly

---

**Sistem Email Notifications AKTIF ✅**
Semua operasi yang membutuhkan notifikasi antar user sudah dikonfigurasi!
