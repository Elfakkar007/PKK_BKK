<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => ':attribute harus diterima.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus tanggal setelah :date.',
    'after_or_equal' => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, dash dan underscore.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa array.',
    'before' => ':attribute harus tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':attribute harus antara :min dan :max.',
        'file' => ':attribute harus antara :min dan :max kilobytes.',
        'string' => ':attribute harus antara :min dan :max karakter.',
        'array' => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean' => ':attribute harus berupa true atau false.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus tanggal yang sama dengan :date.',
    'date_format' => ':attribute tidak cocok dengan format :format.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus :digits digit.',
    'digits_between' => ':attribute harus antara :min dan :max digit.',
    'dimensions' => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ':attribute memiliki nilai yang duplikat.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file' => ':attribute harus lebih besar dari :value kilobytes.',
        'string' => ':attribute harus lebih besar dari :value karakter.',
        'array' => ':attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => ':attribute harus lebih besar atau sama dengan :value.',
        'file' => ':attribute harus lebih besar atau sama dengan :value kilobytes.',
        'string' => ':attribute harus lebih besar atau sama dengan :value karakter.',
        'array' => ':attribute harus memiliki :value item atau lebih.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'in_array' => ':attribute tidak ada di :other.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus berupa alamat IP yang valid.',
    'ipv4' => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => ':attribute harus berupa alamat IPv6 yang valid.',
    'json' => ':attribute harus berupa JSON string yang valid.',
    'lt' => [
        'numeric' => ':attribute harus kurang dari :value.',
        'file' => ':attribute harus kurang dari :value kilobytes.',
        'string' => ':attribute harus kurang dari :value karakter.',
        'array' => ':attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'file' => ':attribute harus kurang dari atau sama dengan :value kilobytes.',
        'string' => ':attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => ':attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'max' => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file' => ':attribute tidak boleh lebih dari :max kilobytes.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
        'array' => ':attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes' => ':attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => ':attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'numeric' => ':attribute minimal harus :min.',
        'file' => ':attribute minimal harus :min kilobytes.',
        'string' => ':attribute minimal harus :min karakter.',
        'array' => ':attribute minimal harus memiliki :min item.',
    ],
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => 'Password salah.',
    'present' => ':attribute harus ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_if' => ':attribute wajib diisi ketika :other adalah :value.',
    'required_unless' => ':attribute wajib diisi kecuali :other ada di :values.',
    'required_with' => ':attribute wajib diisi ketika :values ada.',
    'required_with_all' => ':attribute wajib diisi ketika :values ada.',
    'required_without' => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada :values.',
    'same' => ':attribute dan :other harus cocok.',
    'size' => [
        'numeric' => ':attribute harus :size.',
        'file' => ':attribute harus :size kilobytes.',
        'string' => ':attribute harus :size karakter.',
        'array' => ':attribute harus mengandung :size item.',
    ],
    'starts_with' => ':attribute harus dimulai dengan salah satu dari berikut: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => ':attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah terdaftar.',
    'uploaded' => ':attribute gagal diunggah.',
    'url' => 'Format :attribute tidak valid.',
    'uuid' => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'email' => [
            'required' => 'Email wajib diisi.',
            'email' => 'Format email tidak valid.',
            'unique' => 'Email sudah terdaftar.',
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
            'min' => 'Password minimal 8 karakter.',
            'confirmed' => 'Konfirmasi password tidak cocok.',
        ],
        'nis' => [
            'required' => 'NIS wajib diisi.',
            'unique' => 'NIS sudah terdaftar.',
        ],
        'full_name' => [
            'required' => 'Nama lengkap wajib diisi.',
        ],
        'name' => [
            'required' => 'Nama wajib diisi.',
        ],
        'company_type' => [
            'required' => 'Tipe perusahaan wajib dipilih.',
        ],
        'industry_sector' => [
            'required' => 'Sektor industri wajib dipilih.',
        ],
        'logo' => [
            'image' => 'Logo harus berupa gambar.',
            'max' => 'Ukuran logo maksimal 2MB.',
        ],
        'cv_path' => [
            'mimes' => 'CV harus berupa file PDF.',
            'max' => 'Ukuran CV maksimal 5MB.',
        ],
        'cv' => [
            'required' => 'CV wajib diunggah.',
            'mimes' => 'CV harus berupa file PDF.',
            'max' => 'Ukuran CV maksimal 5MB.',
        ],
        'photo' => [
            'image' => 'Foto harus berupa gambar.',
            'max' => 'Ukuran foto maksimal 2MB.',
        ],
        'featured_image' => [
            'image' => 'Gambar harus berupa file gambar.',
            'max' => 'Ukuran gambar maksimal 2MB.',
        ],
        'deadline' => [
            'required' => 'Batas akhir lamaran wajib diisi.',
            'after' => 'Batas akhir lamaran harus setelah hari ini.',
        ],
        'quota' => [
            'required' => 'Kuota posisi wajib diisi.',
            'min' => 'Kuota minimal 1 posisi.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    /*
    CATATAN: Untuk mengaktifkan validasi bahasa Indonesia, 
    update file config/app.php:
    'locale' => 'id',
    'fallback_locale' => 'id',
    */

    'attributes' => [
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Konfirmasi Password',
        'full_name' => 'Nama Lengkap',
        'name' => 'Nama',
        'nis' => 'NIS',
        'gender' => 'Jenis Kelamin',
        'status' => 'Status',
        'graduation_year' => 'Tahun Kelulusan',
        'class' => 'Kelas',
        'major' => 'Jurusan',
        'birth_date' => 'Tanggal Lahir',
        'birth_place' => 'Tempat Lahir',
        'address' => 'Alamat',
        'phone' => 'No. HP/WhatsApp',
        'photo' => 'Foto',
        'cv_path' => 'CV',
        'cv' => 'CV',
        'company_type' => 'Tipe Perusahaan',
        'industry_sector' => 'Sektor Industri',
        'description' => 'Deskripsi',
        'head_office_address' => 'Alamat Kantor Pusat',
        'pic_name' => 'Nama PIC',
        'pic_phone' => 'No. Telepon PIC',
        'pic_email' => 'Email PIC',
        'website' => 'Website',
        'logo' => 'Logo',
        'title' => 'Judul',
        'type' => 'Tipe',
        'location' => 'Lokasi',
        'requirements' => 'Persyaratan',
        'salary_min' => 'Gaji Minimum',
        'salary_max' => 'Gaji Maximum',
        'quota' => 'Kuota',
        'deadline' => 'Batas Akhir',
        'category' => 'Kategori',
        'content' => 'Konten',
        'featured_image' => 'Gambar',
        'rejection_reason' => 'Alasan Penolakan',
    ],
];