
<?php

namespace App\Imports;

use App\Models\Nilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class NilaiImport implements ToCollection, WithHeadingRow
{
    protected $kelas_id, $mapel_id, $semester, $tahun_ajaran, $kkm;

    public function __construct($kelas_id, $mapel_id, $semester, $tahun_ajaran, $kkm)
    {
        $this->kelas_id = $kelas_id;
        $this->mapel_id = $mapel_id;
        $this->semester = $semester;
        $this->tahun_ajaran = $tahun_ajaran;
        $this->kkm = $kkm;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Lewati jika ID Siswa kosong
            if (!isset($row['id_siswa_jangan_diubah'])) continue;

            $siswaId = $row['id_siswa_jangan_diubah'];
            
            // Ambil data dari Excel
            $rataUH = $row['rata_uh_a'] ?? 0;
            $tugas = $row['tugas_b'] ?? 0;
            $pts = $row['pts_c'] ?? 0;
            $pas = $row['pas_d'] ?? 0;
            $keterampilan = $row['praktek_keterampilan'] ?? 0;

            // --- HITUNG LOGIC (Sama persis dengan Controller) ---
            
            // 1. Kognitif (N)
            $nilaiPengetahuan = ($rataUH + $tugas + $pts + $pas) / 4;
            $nilaiPengetahuanBulat = round($nilaiPengetahuan);

            // 2. Raport
            $nilaiRaport = ($nilaiPengetahuanBulat + $keterampilan) / 2;
            $nilaiRaportBulat = round($nilaiRaport);

            // 3. Predikat
            $predikat = 'D';
            if ($nilaiRaportBulat >= 95) $predikat = 'A+';
            elseif ($nilaiRaportBulat >= 90) $predikat = 'A';
            elseif ($nilaiRaportBulat >= 86) $predikat = 'B+';
            elseif ($nilaiRaportBulat >= 83) $predikat = 'B';
            elseif ($nilaiRaportBulat >= 79) $predikat = 'C+';
            elseif ($nilaiRaportBulat >= 75) $predikat = 'C';
            else $predikat = 'D';

            $status = ($nilaiRaportBulat >= $this->kkm) ? 'Tuntas' : 'Remedial';

            // --- SIMPAN KE DB ---
            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'mapel_id' => $this->mapel_id,
                    'semester' => $this->semester,
                    'tahun_ajaran' => $this->tahun_ajaran,
                ],
                [
                    'kelas_id' => $this->kelas_id,
                    'guru_id' => Auth::id(),
                    'rata_uh' => $rataUH,
                    'tugas' => $tugas,
                    'uts' => $pts, // Mapping DB
                    'uas' => $pas, // Mapping DB
                    'keterampilan' => $keterampilan,
                    'nilai_akhir' => $nilaiPengetahuanBulat,
                    'nilai_raport' => number_format($nilaiRaport, 1),
                    'predikat' => $predikat,
                    'status' => $status,
                ]
            );
        }
    }
}