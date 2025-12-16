## UPDATE: Extended Application Status dengan Interview & Technical Test

### 📋 DESKRIPSI FITUR

Sistem status lamaran sekarang memiliki 6 status lengkap untuk mendukung proses seleksi bertahap:

1. **Pending** (Menunggu) - Status awal
2. **Reviewed** (Sedang Ditinjau) - Auto-change saat buka detail
3. **Interview** (Wawancara) ⭐ **NEW** - Undangan wawancara
4. **Technical Test** (Tes Teknis) ⭐ **NEW** - Undangan tes teknis
5. **Accepted** (Diterima) - Lulus seleksi
6. **Rejected** (Ditolak) - Tidak lulus seleksi

---

### ✅ **IMPLEMENTASI**

#### **1. Database Migration - Tambah Status Baru**
[2025_12_16_000000_add_interview_and_technical_test_to_applications.php](database/migrations/2025_12_16_000000_add_interview_and_technical_test_to_applications.php)

```php
$table->enum('status', ['pending', 'reviewed', 'interview', 'technical_test', 'accepted', 'rejected'])
    ->default('pending')
    ->change();
```

**Sebelum:**
```
['pending', 'reviewed', 'accepted', 'rejected']
```

**Sekarang:**
```
['pending', 'reviewed', 'interview', 'technical_test', 'accepted', 'rejected']
```

---

#### **2. Model - Application.php**

**A. Tambah Scopes:**
```php
public function scopeInterview($query)
{
    return $query->where('status', 'interview');
}

public function scopeTechnicalTest($query)
{
    return $query->where('status', 'technical_test');
}
```

**B. Tambah Helper Methods:**
```php
public function isInterview()
{
    return $this->status === 'interview';
}

public function isTechnicalTest()
{
    return $this->status === 'technical_test';
}
```

**C. Update Status Label & Badge:**
```php
public function getStatusLabelAttribute()
{
    return match($this->status) {
        'pending' => 'Menunggu',
        'reviewed' => 'Sedang Ditinjau',
        'interview' => 'Wawancara',          // ⭐ NEW
        'technical_test' => 'Tes Teknis',    // ⭐ NEW
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
    };
}

public function getStatusBadgeAttribute()
{
    return match($this->status) {
        'pending' => 'warning',
        'reviewed' => 'info',
        'interview' => 'primary',            // ⭐ NEW - Blue
        'technical_test' => 'secondary',     // ⭐ NEW - Gray
        'accepted' => 'success',
        'rejected' => 'danger',
    };
}
```

---

#### **3. Controller - CompanyDashboardController.php**

**Update Validation:**
```php
$validated = $request->validate([
    'status' => ['required', 'in:interview,technical_test,accepted,rejected'],
    'company_notes' => ['nullable', 'string'],
]);
```

---

#### **4. View - company/applications/show.blade.php**

**4 Radio Buttons dengan Flex-wrap:**
```php
<div class="btn-group w-100 flex-wrap" role="group">
    <!-- Wawancara (Primary - Blue) -->
    <input type="radio" class="btn-check" name="status" id="status_interview" 
           value="interview" required>
    <label class="btn btn-outline-primary" for="status_interview">
        <i class="bi bi-chat-left-text me-2"></i>Wawancara
    </label>

    <!-- Tes Teknis (Secondary - Gray) -->
    <input type="radio" class="btn-check" name="status" id="status_technical_test" 
           value="technical_test" required>
    <label class="btn btn-outline-secondary" for="status_technical_test">
        <i class="bi bi-pencil-square me-2"></i>Tes Teknis
    </label>

    <!-- Diterima (Success - Green) -->
    <input type="radio" class="btn-check" name="status" id="status_accepted" 
           value="accepted" required>
    <label class="btn btn-outline-success" for="status_accepted">
        <i class="bi bi-check-circle me-2"></i>Diterima
    </label>

    <!-- Ditolak (Danger - Red) -->
    <input type="radio" class="btn-check" name="status" id="status_rejected" 
           value="rejected" required>
    <label class="btn btn-outline-danger" for="status_rejected">
        <i class="bi bi-x-circle me-2"></i>Ditolak
    </label>
</div>
```

**Key Features:**
- ✅ 4 radio buttons inline
- ✅ `.flex-wrap` agar wrap ke bawah jika terlalu sempit
- ✅ Color-coded: Blue (interview), Gray (test), Green (accept), Red (reject)
- ✅ Icon untuk setiap status

---

#### **5. View - company/applications/index.blade.php**

**7 Filter Tabs:**
- Semua
- Baru (pending)
- Ditinjau (reviewed)
- **Wawancara** (interview) ⭐ NEW
- **Tes Teknis** (technical_test) ⭐ NEW
- Diterima (accepted)
- Ditolak (rejected)

---

#### **6. Helpers - app/Helpers/helpers.php**

**A. get_status_label():**
```php
'interview' => 'Wawancara',
'technical_test' => 'Tes Teknis',
```

**B. get_status_badge_class():**
```php
'interview' => 'bg-primary',
'technical_test' => 'bg-secondary',
```

---

### 🔄 **ALUR SELEKSI BARU**

```
CANDIDATE APPLIES
      ↓
Status: PENDING
      ↓
[Perusahaan buka detail]
      ↓
Status: REVIEWED (Otomatis)
      ↓
KEPUTUSAN:
┌─────────────────────────────┐
│ 1. WAWANCARA (Interview)     │ → Undang ke interview
│ 2. TES TEKNIS (Tech Test)    │ → Undang tes teknis
│ 3. DITERIMA (Accepted)       │ → Lulus seleksi
│ 4. DITOLAK (Rejected)        │ → Tidak lulus
└─────────────────────────────┘
      ↓
NOTIFIKASI KE SISWA
      ↓
[Status berubah + Catatan dikirim]
```

---

### 🎯 **USE CASES**

#### **Scenario 1: Proses Interview**
1. Lamaran masuk → Status: Pending
2. HR review → Status: Reviewed (otomatis)
3. HR putuskan: "Panggil Interview" → Status: Interview
4. Siswa terima notif + jadwal wawancara
5. Setelah interview, update ke "Diterima" atau "Ditolak"

#### **Scenario 2: Proses Tes Teknis**
1. Lamaran masuk → Status: Pending
2. HR review → Status: Reviewed (otomatis)
3. HR putuskan: "Tes Teknis dulu" → Status: Technical Test
4. Siswa terima notif + soal tes
5. Setelah tes, update ke "Diterima" atau "Ditolak"

#### **Scenario 3: Direct Rejection**
1. Lamaran masuk → Status: Pending
2. HR review → Status: Reviewed (otomatis)
3. CV tidak memenuhi → "Ditolak"
4. Siswa terima notif penolakan

---

### 🧪 **TESTING CHECKLIST**

- [ ] Migration berjalan: `php artisan migrate`
- [ ] 4 radio buttons tampil di form decision
- [ ] Buttons wrap ke bawah jika layar kecil (mobile)
- [ ] 7 filter tabs tampil di applications index
- [ ] Filter interview: hanya lamaran status "interview"
- [ ] Filter technical_test: hanya lamaran status "technical_test"
- [ ] Status badge warna sesuai (blue, gray, green, red)
- [ ] Notifikasi dikirim dengan status label benar
- [ ] Catatan bisa ditambah untuk semua 4 status
- [ ] Validation reject hanya 4 status yang diizinkan

---

### 📊 **COLOR SCHEME**

| Status | Color | Badge | Icon |
|--------|-------|-------|------|
| Pending | - | `bg-warning` (yellow) | ⏳ |
| Reviewed | - | `bg-info` (cyan) | 👁️ |
| **Interview** | Primary | `bg-primary` (blue) | 💬 |
| **Technical Test** | Secondary | `bg-secondary` (gray) | ✏️ |
| Accepted | Success | `bg-success` (green) | ✅ |
| Rejected | Danger | `bg-danger` (red) | ❌ |

---

### 📁 **FILES YANG BERUBAH**

| File | Perubahan |
|------|-----------|
| Database | ✅ Migration baru (+ interview & technical_test) |
| [Application.php](app/Models/Application.php) | ✅ Scopes, methods, labels |
| [CompanyDashboardController.php](app/Http/Controllers/Company/CompanyDashboardController.php) | ✅ Validation (4 status) |
| [show.blade.php](resources/views/company/applications/show.blade.php) | ✅ 4 radio buttons |
| [index.blade.php](resources/views/company/applications/index.blade.php) | ✅ 7 filter tabs |
| [helpers.php](app/Helpers/helpers.php) | ✅ Labels & badges |

---

### 🚀 **CARA DEPLOY**

```bash
# 1. Run migration
php artisan migrate

# 2. Cache clear (optional)
php artisan cache:clear

# 3. Done! ✅
```

---

**Implemented:** 16 December 2025
