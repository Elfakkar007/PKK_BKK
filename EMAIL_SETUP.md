# Konfigurasi Email Notifications

Fitur email notifications telah diaktifkan untuk semua operasi yang membutuhkan notifikasi antar user. Berikut adalah panduan setup:

## Email Notifications yang Tersedia

### 1. **Registration Notification**
- **Dipicu saat:** User baru melakukan registrasi
- **Dikirim ke:** User yang baru terdaftar
- **Isi:** Konfirmasi registrasi dan informasi status verifikasi

### 2. **Account Approved Notification**
- **Dipicu saat:** Admin menyetujui akun user
- **Dikirim ke:** User yang disetujui
- **Isi:** Notifikasi approval dan link ke dashboard

### 3. **Account Rejected Notification**
- **Dipicu saat:** Admin menolak akun user
- **Dikirim ke:** User yang ditolak
- **Isi:** Notifikasi rejection dengan alasan penolakan

### 4. **Application Submitted Notification**
- **Dipicu saat:** Student submit lamaran ke job vacancy
- **Dikirim ke:** Perusahaan (company user)
- **Isi:** Notifikasi lamaran baru dengan detail pelamar

### 5. **Application Status Notification**
- **Dipicu saat:** Perusahaan mengubah status lamaran (reviewed/accepted/rejected)
- **Dikirim ke:** Student yang melamar
- **Isi:** Update status lamaran dengan catatan dari perusahaan

### 6. **Vacancy Approved Notification**
- **Dipicu saat:** Admin menyetujui lowongan pekerjaan
- **Dikirim ke:** Perusahaan pemilik lowongan
- **Isi:** Notifikasi approval lowongan

### 7. **Vacancy Rejected Notification**
- **Dipicu saat:** Admin menolak lowongan pekerjaan
- **Dikirim ke:** Perusahaan pemilik lowongan
- **Isi:** Notifikasi rejection dengan alasan penolakan

### 8. **Contact Form Mail**
- **Dipicu saat:** User mengisi form kontak di website
- **Dikirim ke:** Admin email
- **Isi:** Pesan dari user dengan detail kontak mereka

## Konfigurasi SMTP

### Opsi 1: Gmail (Recommended)

1. Enable "Less secure app access" di akun Gmail Anda
2. Atau generate "App Password" jika menggunakan 2-Factor Authentication:
   - Buka https://myaccount.google.com/apppasswords
   - Pilih "Mail" dan "Windows Computer"
   - Copy password yang ditampilkan

3. Update `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="BKK SMKN 1 PURWOSARI"
```

### Opsi 2: Mailtrap (Testing)

1. Daftar di https://mailtrap.io
2. Buat inbox baru
3. Copy SMTP credentials dari "Email Delivery" > "SMTP"

4. Update `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="BKK SMKN 1 PURWOSARI"
```

### Opsi 3: Hosted SMTP Service Lainnya

Gunakan credentials dari provider SMTP Anda (SendGrid, AWS SES, dll)

## Queue Configuration

Semua notifications menggunakan queue untuk mengirim email secara asynchronous (non-blocking).

### Setup Queue Worker

1. Konfigurasi `.env`:
```
QUEUE_CONNECTION=database
```

2. Jalankan migration untuk queue:
```bash
php artisan queue:table
php artisan migrate
```

3. Jalankan queue worker:
```bash
php artisan queue:work
```

Untuk production, gunakan supervisor atau systemd untuk keep queue worker running.

## Testing Email

### Test via Artisan Command

```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Notifications\RegistrationNotification;

$user = User::first();
$user->notify(new RegistrationNotification());
```

### Check Email Log

Jika masih menggunakan `MAIL_MAILER=log`, lihat email yang dikirim di:
```
storage/logs/laravel.log
```

## Troubleshooting

### Email tidak terkirim
- Pastikan `MAIL_MAILER=smtp` bukan `log`
- Cek `.env` credentials sudah benar
- Jalankan queue worker: `php artisan queue:work`
- Cek `jobs` table apakah ada pending jobs

### Queue jobs tidak diproses
- Pastikan `QUEUE_CONNECTION=database` di `.env`
- Jalankan `php artisan queue:work` di terminal terpisah
- Cek database table `jobs` untuk jobs yang failed

### SMTP Connection Error
- Pastikan MAIL_PORT sesuai dengan encryption method:
  - TLS: port 587
  - SSL: port 465
- Cek firewall tidak memblokir SMTP port

## Admin Email Configuration

Admin email untuk menerima contact form dapat dikonfigurasi di:
- `app/Mail/ContactFormMail.php` - ubah recipient
- Atau tambahkan ke `.env` dengan `ADMIN_EMAIL`

## Environment-Specific Configuration

### Development
- Gunakan Mailtrap atau local SMTP (MailHog)
- `MAIL_MAILER=smtp`

### Staging
- Gunakan real SMTP service
- `MAIL_MAILER=smtp`

### Production
- Gunakan SMTP service dengan high deliverability (SendGrid, AWS SES, dll)
- Pastikan queue worker running di background

---

**Status:** ✅ Email notifications telah diaktifkan di seluruh aplikasi
**Last Updated:** December 16, 2025
