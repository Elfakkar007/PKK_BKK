## FIX: Error CV Saat Apply dengan Checkbox "Gunakan CV Tersimpan"

### 🐛 MASALAH
Saat siswa apply lamaran dengan mencentang checkbox "Gunakan CV yang sudah tersimpan", sistem tetap menunjukkan error:
> "CV harus diupload" atau "CV required"

Padahal seharusnya, jika checkbox dicentang, siswa tidak perlu upload CV baru.

---

### ✅ SOLUSI YANG DITERAPKAN

#### **1. Controller - StudentDashboardController.php**
**Perubahan di method `submitApplication()`:**

Sebelum:
```php
$validated = $request->validate([
    'full_name' => ['required', 'string', 'max:255'],
    'address' => ['required', 'string'],
    // ...
    'cv' => ['required', 'mimes:pdf', 'max:5120'],  // ❌ Selalu required
]);

$cvPath = $request->file('cv')->store('application-cvs', 'public');
```

Sekarang:
```php
// Cek apakah checkbox "gunakan CV tersimpan" dicentang
$useExistingCv = $request->has('use_existing_cv');

// Validation rule CONDITIONAL
$rules = [
    'full_name' => ['required', 'string', 'max:255'],
    // ...
];

// CV hanya REQUIRED jika TIDAK menggunakan CV tersimpan
if (!$useExistingCv) {
    $rules['cv'] = ['required', 'mimes:pdf', 'max:5120'];
}

$validated = $request->validate($rules);

// Gunakan CV yang sesuai
if ($useExistingCv) {
    $cvPath = $student->cv_path;  // ✅ Pakai CV lama
} else {
    $cvPath = $request->file('cv')->store('application-cvs', 'public');  // ✅ Upload baru
}
```

**Keuntungan:**
- ✅ Validasi CV menjadi conditional (bergantung pada checkbox)
- ✅ Jika checkbox dicentang, CV dari profile digunakan
- ✅ Jika checkbox tidak dicentang, CV baru harus di-upload

---

#### **2. View - resources/views/student/apply.blade.php**
**Perubahan pada checkbox:**

Sebelum:
```php
<input class="form-check-input" type="checkbox" id="use_existing_cv" onchange="toggleCvUpload()">
```

Sekarang:
```php
<input class="form-check-input" type="checkbox" id="use_existing_cv" name="use_existing_cv" onchange="toggleCvUpload()">
```

**Penambahan:** Attribute `name="use_existing_cv"` agar checkbox value dikirim ke server

---

#### **3. JavaScript Validation - apply.blade.php**
**Perubahan di function `confirmSubmitApplication()`:**

Sebelum:
```javascript
if (!useExistingCV?.checked && !cvInput.files.length) {
    // Error
}
```

Sekarang:
```javascript
const hasNewCv = cvInput.files && cvInput.files.length > 0;
const useExistingCv = useExistingCV?.checked || false;

if (!hasNewCv && !useExistingCv) {
    // Error: Tidak ada CV baru dan checkbox juga tidak dicentang
}
```

**Keuntungan:**
- ✅ Validasi lebih robust
- ✅ Pesan error lebih jelas
- ✅ Mencegah submit tanpa CV (baik baru maupun tersimpan)

---

### 🧪 CARA TESTING

#### **Scenario 1: Gunakan CV Tersimpan** ✅
1. Login sebagai siswa yang sudah punya CV
2. Klik apply pada sebuah lowongan
3. **Centang** checkbox "Gunakan CV yang sudah tersimpan"
4. Input field CV akan disabled
5. Klik "Kirim Lamaran"
6. ✅ Lamaran berhasil dikirim (tanpa upload CV baru)

#### **Scenario 2: Upload CV Baru** ✅
1. Login sebagai siswa (punya atau tidak punya CV lama)
2. Klik apply pada sebuah lowongan
3. **Jangan centang** checkbox (atau tidak ada checkbox jika tidak punya CV lama)
4. Upload file CV (PDF)
5. Klik "Kirim Lamaran"
6. ✅ Lamaran berhasil dikirim dengan CV baru

#### **Scenario 3: Gabungan** ✅
1. Login sebagai siswa yang sudah punya CV
2. Klik apply pada sebuah lowongan
3. **Bisa pilih:**
   - Centang checkbox → pakai CV lama
   - Tidak centang + upload → pakai CV baru
4. ✅ Keduanya harus berfungsi

#### **Scenario 4: Error Handling** ✅
1. Login sebagai siswa yang punya CV
2. Klik apply
3. **Jangan** centang checkbox dan **jangan** upload CV
4. Klik "Kirim Lamaran"
5. ✅ Error: "CV Belum Dipilih - Mohon upload CV baru atau gunakan CV yang sudah tersimpan"

---

### 📊 SUMMARY PERUBAHAN

| File | Perubahan |
|------|-----------|
| `StudentDashboardController.php` | ✅ Tambah logic conditional CV + cek checkbox |
| `apply.blade.php` (checkbox) | ✅ Tambah attribute `name="use_existing_cv"` |
| `apply.blade.php` (JavaScript) | ✅ Improve validasi di function `confirmSubmitApplication()` |

---

### 🎯 KEY POINTS

1. **Checkbox harus punya attribute `name`** agar value dikirim ke server
2. **Validasi harus conditional** di controller, bukan hardcoded
3. **JavaScript validation** harus match dengan server-side logic
4. **Error handling** harus jelas jika siswa tidak pilih CV (baik baru maupun lama)

---

**Fixed:** 16 December 2025
