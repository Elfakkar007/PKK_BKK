## TESTING GUIDE - AUTO-CLOSE LOWONGAN FEATURE

### Scenario 1: Lowongan Expired (Deadline Terlewat)

**Setup:**
1. Login sebagai perusahaan
2. Buat lowongan baru dengan deadline masa depan (e.g., 2025-12-20)
3. Approve lowongan via admin panel
4. Pastikan lowongan tampil di `/lowongan` (halaman publik)

**Test:**
1. Update database manually untuk deadline yang sudah terlewat:
   ```sql
   UPDATE job_vacancies SET deadline = '2025-12-01' WHERE id = X;
   ```
2. Kunjungi `/company/vacancies`
3. Status lowongan akan berubah otomatis menjadi "closed"
4. Lowongan hilang dari halaman publik `/lowongan`

**Expected Result:** ✓ Lowongan berstatus "closed" dan tidak terlihat di halaman publik

---

### Scenario 2: Posisi Terpenuhi (Quota Penuh)

**Setup:**
1. Login sebagai perusahaan
2. Buat lowongan dengan quota: **1** (satu posisi)
3. Approve lowongan via admin

**Test:**
1. Login sebagai siswa (3+ siswa berbeda)
2. Semua siswa apply untuk lowongan tersebut
3. Perusahaan approve/terima aplikasi dari siswa pertama
4. Kunjungi `/company/vacancies`
5. Status lowongan berubah otomatis menjadi "closed" (karena 1/1 quota penuh)

**Expected Result:** ✓ Lowongan berstatus "closed" setelah quota penuh

---

### Scenario 3: Filter "Closed" di Perusahaan

**Test:**
1. Login sebagai perusahaan
2. Kunjungi `/company/vacancies`
3. Klik tab **"Tertutup"** (warna abu-abu)
4. Hanya lowongan dengan status `closed` yang ditampilkan

**Expected Result:** ✓ Filter bekerja dengan baik

---

### Scenario 4: Filter "Closed" di Admin

**Test:**
1. Login sebagai admin
2. Kunjungi `/admin/vacancies`
3. Pilih "Closed" dari dropdown "Semua Status"
4. Hanya lowongan dengan status `closed` yang ditampilkan

**Expected Result:** ✓ Filter bekerja dengan baik

---

### Scenario 5: Lowongan Tidak Tampil di Publik

**Test:**
1. Buat lowongan dengan deadline terlewat atau quota penuh
2. Status berubah ke "closed"
3. Kunjungi `/lowongan` sebagai user umum atau siswa
4. Lowongan tidak ada di daftar

**Expected Result:** ✓ Lowongan tidak terlihat di halaman publik

---

### Scenario 6: Pagination Dengan Filter

**Test:**
1. Filter lowongan berdasarkan status (e.g., "Tertutup")
2. Jika ada lebih dari 10 data, pindah ke halaman berikutnya
3. Filter status tetap aktif

**Expected Result:** ✓ Parameter status tetap di-pass

---

### Using Artisan Command

**Test:**
```bash
cd d:\Hammam\Produktif\Kelas\ 12\BKK
php artisan vacancies:auto-close
```

**Expected Output:**
```
Successfully closed X vacancies.
```

---

### Database Query (Verification)

```sql
-- Lihat semua lowongan yang closed
SELECT id, title, status, deadline, is_active FROM job_vacancies WHERE status = 'closed';

-- Lihat lowongan yang masih active
SELECT id, title, status, deadline, is_active FROM job_vacancies 
WHERE status = 'approved' AND is_active = true AND deadline >= CURRENT_DATE;

-- Lihat lowongan dengan quota penuh
SELECT jv.id, jv.title, jv.quota, 
       COUNT(a.id) as accepted_count
FROM job_vacancies jv
LEFT JOIN applications a ON jv.id = a.job_vacancy_id AND a.status = 'accepted'
GROUP BY jv.id
HAVING COUNT(a.id) >= jv.quota;
```

---

## Summary Perubahan

### Files Modified:
1. ✓ `app/Models/JobVacancy.php` - Tambah scope & methods untuk closed
2. ✓ `app/Http/Controllers/Company/CompanyDashboardController.php` - Auto-close & filter
3. ✓ `app/Http/Controllers/Admin/VacancyManagementController.php` - Auto-close & filter
4. ✓ `resources/views/company/vacancies/index.blade.php` - Tab filter closed
5. ✓ `resources/views/admin/vacancies/index.blade.php` - Dropdown filter closed

### Files Created:
1. ✓ `app/Console/Commands/AutoCloseVacancies.php` - Artisan command
2. ✓ `AUTO_CLOSE_FEATURE.md` - Dokumentasi fitur

### Database:
- Tidak perlu migration tambahan (status enum sudah ada)
- Hanya perlu data pada lowongan yang sudah expired/full

---
**Created:** 16 December 2025
