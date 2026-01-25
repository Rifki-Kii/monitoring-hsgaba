<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        // Data Mapel sesuai Jadwal
        $dataMapel = [
            // --- KATEGORI: TSAQAFAH ISLAM ---
            ['nama_mapel' => 'U + T (Ummi & Tahfidz)', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Bahasa Arab', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Adab dan Do\'a', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Siroh Nabawiyah', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Tabi\'in dan Panglima Tak Terlupakan', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Api Sejarah', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Kisah Sahabat', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Aqidah dan Akhlak', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'PAI', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],
            ['nama_mapel' => 'Para Pemikir Islam (Projek)', 'kategori' => 'Tsaqafah Islam', 'kode_prefix'=> 'TI'],

            // --- KATEGORI: PENGETAHUAN UMUM (Disesuaikan dengan ENUM) ---
            ['nama_mapel' => 'Sains', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'Matematika (MTK)', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'Bahasa Inggris (English)', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'Personality', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'Knowledge', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'Bahasa Indonesia', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'IPS', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'PPKN', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],
            ['nama_mapel' => 'Geografi (Kata Mendunia)', 'kategori' => 'Pengetahuan Umum', 'kode_prefix'=> 'PU'],

            // --- KATEGORI: KETERAMPILAN ---
            ['nama_mapel' => 'Taekwondo', 'kategori' => 'Keterampilan', 'kode_prefix'=> 'KT'],
            ['nama_mapel' => 'Adzan, Iqomah, Ceramah', 'kategori' => 'Keterampilan', 'kode_prefix'=> 'KT'],
            ['nama_mapel' => 'Renang', 'kategori' => 'Keterampilan', 'kode_prefix'=> 'KT'],
            ['nama_mapel' => 'Panahan', 'kategori' => 'Keterampilan', 'kode_prefix'=> 'KT'],
            ['nama_mapel' => 'LDK (Latihan Dasar Kepemimpinan)', 'kategori' => 'Keterampilan', 'kode_prefix'=> 'KT'],
        ];

        foreach ($dataMapel as $index => $item) {
            // Generate Kode (TI-001, PU-002, dst)
            $number = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $kode = $item['kode_prefix'] . '-' . $number;

            Mapel::create([
                'kode_mapel' => $kode,
                'nama_mapel' => $item['nama_mapel'],
                'kategori'   => $item['kategori'], // Pastikan ini sesuai ENUM
                'kkm'        => 75,
            ]);
        }
    }
}