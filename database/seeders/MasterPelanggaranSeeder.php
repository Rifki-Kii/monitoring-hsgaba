<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPelanggaran;

class MasterPelanggaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['jenis_pelanggaran' => 'Terlambat Datang', 'poin' => 2],
            ['jenis_pelanggaran' => 'Tidak Memakai Seragam Lengkap', 'poin' => 2],
            ['jenis_pelanggaran' => 'Tidak Tertib di Kelas', 'poin' => 4],
            ['jenis_pelanggaran' => 'Membuang Sampah Sembarangan', 'poin' => 5],
            ['jenis_pelanggaran' => 'Bertengkar / Berkelahi', 'poin' => 20], // Langsung Sanksi
            ['jenis_pelanggaran' => 'Bolos Sekolah', 'poin' => 10],
        ];

        foreach ($data as $d) {
            MasterPelanggaran::create($d);
        }
    }
}