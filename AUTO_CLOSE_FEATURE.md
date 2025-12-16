## FITUR AUTO-CLOSE LOWONGAN

### Deskripsi
Lowongan akan otomatis berubah status menjadi "CLOSED" (Tertutup) jika:
1. **Deadline sudah terlewat** (tanggal deadline lebih kecil dari hari ini)
2. **Posisi sudah terpenuhi** (jumlah aplikasi yang diterima sudah mencapai quota)

Ketika lowongan di-close:
- Status berubah menjadi "closed"
- Bidang `is_active` menjadi `false`
- Lowongan **hilang dari halaman publik** (tidak ditampilkan di halaman lowongan untuk siswa)
- Lowongan tetap **terlihat di panel manajemen** (perusahaan dan admin)

### Penggunaan

#### 1. Di Code (Controller)
Auto-close dipicu secara otomatis ketika:
- Perusahaan membuka halaman kelola lowongan: `/company/vacancies`
- Admin membuka halaman manajemen lowongan: `/admin/vacancies`

```php
// Auto-close logic ada di method vacancies() di controller
$allVacancies = JobVacancy::where('company_id', $company->id)->get();
foreach ($allVacancies as $vacancy) {
    $vacancy->autoCloseLowongan();
}
```

#### 2. Menggunakan Artisan Command (Manual/Scheduled)
```bash
php artisan vacancies:auto-close
```

Perintah ini akan:
- Mencari semua lowongan yang belum di-close
- Memeriksa apakah mereka harus di-close
- Mengubah status menjadi "closed" jika perlu

#### 3. Scheduled (Optional)
Untuk menjalankan auto-close secara berkala, tambahkan ke `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('vacancies:auto-close')->hourly();
    // atau:
    // $schedule->command('vacancies:auto-close')->daily();
}
```

### Model Methods

#### Pengecekan Status
```php
$vacancy = JobVacancy::find(1);

// Cek apakah sudah expired
$vacancy->isExpired();

// Cek apakah posisi penuh
$vacancy->isFull();

// Cek apakah sudah di-close
$vacancy->isClosed();

// Cek apakah harus di-close
$vacancy->shouldBeClosed();
```

#### Operasi
```php
// Tutup lowongan secara manual
$vacancy->markAsClosed();

// Auto-close jika memenuhi kriteria
$vacancy->autoCloseLowongan();
```

### Filter Di UI

#### Perusahaan (Company)
Tab filter di halaman `/company/vacancies`:
- Semua
- Pending Approval
- Aktif (Approved)
- Ditolak (Rejected)
- **Tertutup (Closed)** ← NEW

#### Admin
Dropdown filter di halaman `/admin/vacancies`:
- Semua Status
- Pending
- Approved
- Rejected
- **Closed** ← NEW

### Database
Enum `status` di tabel `job_vacancies` sudah mendukung:
- pending
- approved
- rejected
- closed ✓

### Halaman Publik
Lowongan dengan status "closed" **TIDAK** ditampilkan di:
- `/lowongan` (daftar lowongan publik)
- `/lowongan/{id}` (detail lowongan publik)

Karena scope `active()` di JobVacancy model mengecualikan status yang bukan "approved" dan deadline yang sudah terlewat.

---
**Last Updated:** 16 December 2025
