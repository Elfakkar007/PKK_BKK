# Setup Email Notifications & Queue Configuration

## Panduan Aktivasi Email Notifications

Email notifications telah dikonfigurasi di seluruh aplikasi BKK SMKN 1 Purwosari. Berikut langkah-langkah untuk mengaktifkannya:

### Step 1: Konfigurasi SMTP di .env

Edit file `.env` dan ubah konfigurasi email sesuai dengan provider SMTP yang Anda gunakan:

#### Opsi A: Gmail (Recommended)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="BKK SMKN 1 PURWOSARI"
```

**Cara mendapatkan App Password Gmail:**
1. Buka https://myaccount.google.com/apppasswords
2. Pilih "Mail" dan "Windows Computer"
3. Google akan generate password khusus, copy password tersebut ke `MAIL_PASSWORD`
4. Pastikan 2-Factor Authentication sudah diaktifkan di akun Gmail

#### Opsi B: Mailtrap (Untuk Testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="BKK SMKN 1 PURWOSARI"
```

Daftar di https://mailtrap.io dan copy SMTP credentials dari dashboard.

#### Opsi C: SendGrid (Production)

```env
MAIL_MAILER=sendmail
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -bs"
MAIL_FROM_ADDRESS=noreply@bkk.smkn1purwosari.sch.id
MAIL_FROM_NAME="BKK SMKN 1 PURWOSARI"
```

### Step 2: Konfigurasi Queue Database

Semua notifications menggunakan queue untuk non-blocking email delivery.

1. Jalankan migration untuk queue:
```bash
php artisan queue:table
php artisan migrate
```

2. Pastikan `.env` sudah terkonfigurasi:
```env
QUEUE_CONNECTION=database
```

### Step 3: Jalankan Queue Worker

Untuk mengirim email yang tertunda di queue:

```bash
php artisan queue:work
```

**Untuk Production, gunakan supervisor atau systemd untuk keep queue worker running:**

#### Menggunakan Supervisor

1. Install supervisor:
```bash
sudo apt-get install supervisor
```

2. Buat file konfigurasi di `/etc/supervisor/conf.d/bkk-queue.conf`:
```ini
[program:bkk-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/bkk/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
stderr_logfile=/var/log/bkk-queue.err.log
stdout_logfile=/var/log/bkk-queue.out.log
```

3. Jalankan supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start bkk-queue:*
```

#### Menggunakan Systemd

1. Buat file di `/etc/systemd/system/bkk-queue.service`:
```ini
[Unit]
Description=BKK Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/path/to/bkk
ExecStart=/usr/bin/php artisan queue:work
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

2. Enable dan start:
```bash
sudo systemctl daemon-reload
sudo systemctl enable bkk-queue
sudo systemctl start bkk-queue
```

### Step 4: Clear Cache (Penting!)

Setelah mengubah konfigurasi:
```bash
php artisan config:clear
php artisan cache:clear
```

## Email Notifications yang Diaktifkan

### 1. Registration Notification (Saat User Baru Registrasi)
- **Dikirim ke:** Email user yang baru terdaftar
- **Trigger:** Setelah registrasi berhasil
- **File:** `AuthController::registerStudent()`, `AuthController::registerCompany()`
- **Notification:** `app/Notifications/RegistrationNotification.php`

### 2. Account Approval (Saat Admin Approve Akun)
- **Dikirim ke:** Email user yang di-approve
- **Trigger:** Admin approve user di `/admin/users/pending`
- **File:** `UserManagementController::approve()`
- **Notification:** `app/Notifications/AccountApprovedNotification.php`

### 3. Account Rejection (Saat Admin Tolak Akun)
- **Dikirim ke:** Email user yang ditolak
- **Trigger:** Admin reject user dengan alasan
- **File:** `UserManagementController::reject()`
- **Notification:** `app/Notifications/AccountRejectedNotification.php`

### 4. Application Submitted (Saat Student Submit Lamaran)
- **Dikirim ke:** Email perusahaan penerima lamaran
- **Trigger:** Student submit aplikasi untuk job vacancy
- **File:** `StudentDashboardController::submitApplication()`
- **Notification:** `app/Notifications/ApplicationSubmittedNotification.php`

### 5. Application Status Update (Saat Company Update Status Lamaran)
- **Dikirim ke:** Email student yang melamar
- **Trigger:** Perusahaan mengubah status lamaran (reviewed, interview, accepted, rejected)
- **File:** `CompanyDashboardController::applicationUpdateStatus()`
- **Notification:** `app/Notifications/ApplicationStatusNotification.php`

### 6. Vacancy Approval (Saat Admin Approve Lowongan)
- **Dikirim ke:** Email perusahaan pemilik lowongan
- **Trigger:** Admin approve vacancy
- **File:** `VacancyManagementController::approve()`
- **Notification:** `app/Notifications/VacancyApprovedNotification.php`

### 7. Vacancy Rejection (Saat Admin Tolak Lowongan)
- **Dikirim ke:** Email perusahaan pemilik lowongan
- **Trigger:** Admin reject vacancy dengan alasan
- **File:** `VacancyManagementController::reject()`
- **Notification:** `app/Notifications/VacancyRejectedNotification.php`

### 8. Contact Form Email (Saat User Submit Form Kontak)
- **Dikirim ke:** Email admin (dari `setting.contact_email`)
- **Trigger:** Public user submit form kontak
- **File:** `ContactController::store()`
- **Mail:** `app/Mail/ContactFormMail.php`

## Testing Email Notifications

### Test Via Artisan Tinker

```bash
php artisan tinker
```

Kemudian jalankan salah satu command berikut:

**Test Registration Notification:**
```php
use App\Models\User;
use App\Notifications\RegistrationNotification;

$user = User::find(1);
$user->notify(new RegistrationNotification());
```

**Test Account Approved:**
```php
use App\Models\User;
use App\Notifications\AccountApprovedNotification;

$user = User::find(1);
$user->notify(new AccountApprovedNotification());
```

**Test Application Status:**
```php
use App\Models\Application;
use App\Notifications\ApplicationStatusNotification;

$application = Application::find(1);
$application->student->user->notify(new ApplicationStatusNotification($application));
```

### Check Queue Jobs

Cek database untuk pending jobs:
```bash
php artisan queue:failed
```

Monitor queue processing:
```bash
php artisan queue:monitor
```

Retry failed jobs:
```bash
php artisan queue:retry all
```

## Troubleshooting

### Email tidak terkirim
1. Pastikan SMTP credentials di `.env` benar
2. Cek `php artisan queue:work` sedang berjalan
3. Cek logs di `storage/logs/laravel.log`
4. Pastikan MAIL_MAILER bukan 'log' tapi 'smtp'

### Queue jobs tidak diproses
1. Pastikan `QUEUE_CONNECTION=database` di `.env`
2. Pastikan migration untuk jobs table sudah dijalankan: `php artisan queue:table && php artisan migrate`
3. Jalankan `php artisan queue:work` di terminal terpisah
4. Cek database `jobs` table untuk pending jobs

### SMTP Connection Error
1. Pastikan MAIL_PORT sesuai dengan encryption:
   - TLS: 587
   - SSL: 465
2. Pastikan firewall tidak memblokir SMTP port
3. Cek MAIL_USERNAME dan MAIL_PASSWORD
4. Jika menggunakan Gmail, pastikan sudah enable "Less secure app access" atau use App Password

### Admin Email untuk Contact Form
Pastikan setting contact_email sudah diset di admin settings, atau update di `app/Http/Controllers/ContactController.php` line 29:
```php
Mail::to('admin@example.com')->send(new ContactFormMail($contactRequest));
```

## Development Environment Tips

Untuk development, Anda bisa menggunakan Mailtrap untuk melihat email tanpa benar-benar mengirimnya:

1. Daftar di https://mailtrap.io
2. Copy SMTP credentials ke `.env`
3. Semua email akan ter-capture di Mailtrap inbox
4. Tidak akan mengirim email asli ke user

## Status Implementasi

- ✅ Email notifications sudah diintegrasikan di seluruh controllers
- ✅ Semua notifications menggunakan ShouldQueue untuk non-blocking delivery
- ✅ Queue jobs configured dengan database driver
- ✅ Email templates sudah tersedia
- ✅ ContactFormMail sudah diaktifkan
- ✅ Fallback mailer configured (SMTP -> Log)

## Konfigurasi Lanjutan

### Custom Email Template

Email template dapat dikustomisasi di `resources/views/vendor/notifications/` atau dibuat Mailable class di `app/Mail/`

### Failed Job Handling

Jobs yang gagal dapat di-retry:
```php
// Dalam notification class
class MyNotification extends Notification implements ShouldQueue
{
    public $tries = 3;           // Retry 3 kali
    public $timeout = 60;        // Timeout 60 detik
}
```

### Queue Prioritization

Prioritaskan tertentu jobs:
```php
$user->notify(new ImportantNotification()); // Default: low priority
// Ubah di notification class jika perlu
```

---

**Dokumentasi Dibuat:** December 16, 2025
**Status:** ✅ AKTIF - Email notifications siap digunakan
