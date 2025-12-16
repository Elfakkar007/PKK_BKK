## FITUR: Semi-Automatic Status Update untuk Lamaran

### 📋 DESKRIPSI FITUR

Sistem status lamaran sekarang bekerja semi-otomatis:

1. **Auto-Review (Pending → Reviewed)**
   - Ketika perusahaan membuka detail lamaran yang masih berstatus "Menunggu" (pending)
   - Status otomatis berubah menjadi "Sedang Ditinjau" (reviewed)
   - Timestamp `reviewed_at` dicatat otomatis

2. **Manual Decision (Reviewed → Accepted/Rejected)**
   - Perusahaan dapat memilih untuk menerima atau menolak lamaran
   - Menggunakan radio button inline (bukan dropdown)
   - Hanya tersedia opsi: **Diterima** dan **Ditolak**

---

### ✅ **IMPLEMENTASI**

#### **1. Controller - CompanyDashboardController.php**

**Method `applicationShow()` - Auto-change ke Reviewed:**
```php
public function applicationShow($id)
{
    $company = Auth::user()->company;
    
    $application = Application::whereHas('jobVacancy', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })
        ->with(['jobVacancy', 'student.user'])
        ->findOrFail($id);

    // Auto-change status to "reviewed" jika masih pending
    if ($application->status === 'pending') {
        $application->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);
    }

    return view('company.applications.show', compact('application'));
}
```

**Method `applicationUpdateStatus()` - Hanya Accept/Reject:**
```php
$validated = $request->validate([
    'status' => ['required', 'in:accepted,rejected'],  // ← Hanya 2 opsi
    'company_notes' => ['nullable', 'string'],
]);
```

---

#### **2. View - company/applications/show.blade.php**

**A. Info Alert (Auto-change notification):**
```php
@if($application->status === 'reviewed')
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Status Otomatis Diubah</strong><br>
        <small>Status lamaran otomatis berubah menjadi "Sedang Ditinjau" setelah Anda membuka detail lamaran ini.</small>
    </div>
@endif
```

**B. Radio Button Inline:**
```php
<div class="mb-3">
    <label class="form-label">Keputusan <span class="text-danger">*</span></label>
    <div class="btn-group w-100" role="group">
        <!-- Radio 1: Diterima -->
        <input type="radio" class="btn-check" name="status" id="status_accepted" 
               value="accepted" {{ $application->status == 'accepted' ? 'checked' : '' }} required>
        <label class="btn btn-outline-success" for="status_accepted">
            <i class="bi bi-check-circle me-2"></i>Diterima
        </label>

        <!-- Radio 2: Ditolak -->
        <input type="radio" class="btn-check" name="status" id="status_rejected" 
               value="rejected" {{ $application->status == 'rejected' ? 'checked' : '' }} required>
        <label class="btn btn-outline-danger" for="status_rejected">
            <i class="bi bi-x-circle me-2"></i>Ditolak
        </label>
    </div>
</div>
```

**Styling Notes:**
- Menggunakan Bootstrap's button group (`.btn-group`)
- Radio buttons tersembunyi (`.btn-check`)
- Label menjadi button yang bisa diklik
- Inline layout dengan full width (`w-100`)
- Warna: Green (accepted), Red (rejected)

---

### 🔄 **ALUR KERJA**

```
┌─────────────────────────────────────────────────────────┐
│                   LAMARAN MASUK                          │
│                   Status: PENDING                        │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ↓
        ┌──────────────────────────┐
        │ Perusahaan buka lamaran  │
        │ (klik Review button)     │
        └──────────────────────────┘
                       │
                       ↓ (OTOMATIS)
        ┌──────────────────────────┐
        │   Status: REVIEWED       │
        │   reviewed_at: now()     │
        └──────────────────────────┘
                       │
                       ↓
        ┌──────────────────────────────────┐
        │ Perusahaan lihat detail lamaran  │
        │ dan pilih keputusan               │
        └──────────────────────────────────┘
                       │
          ┌────────────┴────────────┐
          ↓                         ↓
    ┌──────────────┐         ┌──────────────┐
    │  Diterima    │         │   Ditolak    │
    │  (ACCEPTED)  │         │ (REJECTED)   │
    └──────────────┘         └──────────────┘
          │                         │
          └────────────┬────────────┘
                       ↓
            ┌────────────────────┐
            │ Notification sent  │
            │ to student         │
            └────────────────────┘
```

---

### 🧪 **TESTING SCENARIOS**

#### **Test 1: Auto-Review Pending → Reviewed**
1. Login sebagai perusahaan
2. Buat lamaran baru dari siswa
3. Status lamaran: `pending`
4. Buka detail lamaran (klik "Review")
5. ✅ **Expected:** Status otomatis berubah ke `reviewed`
6. Muncul info alert: "Status Otomatis Diubah"

#### **Test 2: Radio Button Display**
1. Detail lamaran terbuka (status: reviewed)
2. Scroll ke section "Update Status Lamaran"
3. ✅ **Expected:** 
   - 2 radio buttons: "Diterima" (hijau) dan "Ditolak" (merah)
   - Tidak ada dropdown
   - Inline layout (horizontal)

#### **Test 3: Accept Application**
1. Lamaran status: reviewed
2. Klik radio button "Diterima"
3. Tambahkan catatan (optional)
4. Klik "Update Status"
5. ✅ **Expected:**
   - Status berubah ke `accepted`
   - Notifikasi dikirim ke siswa
   - Success message muncul

#### **Test 4: Reject Application**
1. Lamaran status: reviewed
2. Klik radio button "Ditolak"
3. Tambahkan catatan (opsional tapi recommended)
4. Klik "Update Status"
5. ✅ **Expected:**
   - Status berubah ke `rejected`
   - Notifikasi dikirim ke siswa
   - Success message muncul

#### **Test 5: Cannot Change to Pending/Reviewed**
1. Lamaran status: reviewed
2. Update melalui DB atau API request dengan status `pending`
3. ✅ **Expected:** Validation error - only `accepted` or `rejected` allowed

---

### 🎯 **KEY FEATURES**

| Fitur | Sebelum | Sekarang |
|-------|---------|----------|
| **Status change ke Reviewed** | Manual (dropdown) | ✅ **Otomatis** (saat buka detail) |
| **Status Diterima/Ditolak** | Dropdown (4 opsi) | ✅ **Radio Button** (2 opsi) |
| **UI Style** | Dropdown | ✅ **Inline button group** |
| **Notification** | Ada | ✅ **Masih ada** |
| **Timestamp** | Dicatat saat update | ✅ **Dicatat saat auto-review & update** |

---

### 📝 **NOTES**

1. **Cascade Update:**
   - Pending → Reviewed: Otomatis saat detail dibuka
   - Reviewed → Accepted/Rejected: Manual via radio button

2. **Validation:**
   - Backend hanya accept `accepted` atau `rejected`
   - Tidak bisa kembali ke `pending` atau `reviewed`

3. **User Experience:**
   - Info alert membantu user memahami status otomatis
   - Radio button lebih intuitif untuk keputusan yes/no
   - Full-width button group lebih mudah diklik

4. **Backward Compatibility:**
   - Lamaran yang sudah `reviewed` tidak auto-update lagi
   - Hanya lamaran `pending` yang auto-update ke `reviewed`

---

**Implemented:** 16 December 2025
