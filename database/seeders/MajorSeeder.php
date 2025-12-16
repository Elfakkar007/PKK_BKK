<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            [
                'code' => 'TKJ',
                'name' => 'Teknik Komputer dan Jaringan',
                'description' => 'Program keahlian yang mempelajari tentang instalasi, konfigurasi, dan pemeliharaan jaringan komputer.',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'code' => 'RPL',
                'name' => 'Rekayasa Perangkat Lunak',
                'description' => 'Program keahlian yang mempelajari tentang pembuatan dan pengembangan aplikasi perangkat lunak.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'code' => 'DKV',
                'name' => 'Multimedia',
                'description' => 'Program keahlian yang mempelajari tentang desain grafis, animasi, dan produksi multimedia.',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'code' => 'MKT',
                'name' => 'Mekatronika',
                'description' => 'Program keahlian yang mempelajari tentang integrasi mekanika, elektronika, dan komputer untuk sistem otomatisasi.',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'code' => 'TPM',
                'name' => 'Teknik Pemesinan',
                'description' => 'Program keahlian yang mempelajari tentang proses pembuatan komponen mesin menggunakan berbagai peralatan pemesinan.',
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }

        $this->command->info('Majors seeded successfully!');
    }
}