<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Nilai;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class StatistikAkademik extends Component
{
    // Filter State
    public $filterTahun;
    public $filterSemester;
    public $filterKelas = ''; // ID Kelas
    public $filterMapel = ''; // ID Mapel

    // Data untuk Dropdown
    public $listKelas;
    public $listMapel;

    public function mount()
    {
        $this->filterTahun = date('Y');
        $this->filterSemester = 'Ganjil';
        
        $this->listKelas = Kelas::orderBy('nama_kelas')->get();
        $this->listMapel = Mapel::orderBy('nama_mapel')->get();
    }

   public function render()
    {
        // RUMUS NILAI AKHIR (SQL)
        // (Rata UH + UTS + UAS) / 3
        $sqlNilai = '(COALESCE(rata_uh, 0) + COALESCE(uts, 0) + COALESCE(uas, 0)) / 3';

        // BASE QUERY
        $query = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id');

        // Terapkan Filter
        if ($this->filterTahun) {
             // Pastikan nama kolom tahun di DB sesuai, misal 'tahun_ajaran'
             // $query->where('nilais.tahun_ajaran', $this->filterTahun); 
        }
        
        if ($this->filterKelas) {
            $query->where('siswas.kelas_id', $this->filterKelas);
        }
        if ($this->filterMapel) {
            $query->where('nilais.mapel_id', $this->filterMapel);
        }

        // ==========================================
        // 1. STATISTIK UTAMA (CARDS)
        // ==========================================
        $statsUtama = (clone $query)->selectRaw("
            AVG($sqlNilai) as rata_rata,
            MAX($sqlNilai) as tertinggi,
            MIN($sqlNilai) as terendah,
            COUNT(DISTINCT siswas.id) as total_siswa
        ")->first();


        // ==========================================
        // 2. ANALISA KOMPONEN (UH vs UTS vs UAS)
        // ==========================================
        $komponenNilai = (clone $query)->selectRaw("
            AVG(rata_uh) as avg_uh,
            AVG(uts) as avg_uts,
            AVG(uas) as avg_uas
        ")->first();


        // ==========================================
        // 3. DISTRIBUSI NILAI (HISTOGRAM) -- [PERBAIKAN DISINI]
        // ==========================================
        // Kita tarik data nilainya saja, lalu hitung Grade di PHP agar tidak error SQL
        $rawNilai = (clone $query)
            ->selectRaw("$sqlNilai as nilai_akhir")
            ->get();

        // Gunakan Laravel Collection untuk Grouping & Counting
        $distribusi = $rawNilai->map(function ($row) {
            $n = $row->nilai_akhir;
            if ($n >= 90) return 'A (Sangat Baik)';
            if ($n >= 80) return 'B (Baik)';
            if ($n >= 70) return 'C (Cukup)';
            return 'D (Kurang)';
        })->countBy(); // Hasil: ['A' => 5, 'B' => 12]

        // Urutkan Key (A, B, C, D)
        $distribusi = $distribusi->sortKeys();

        $labelDistribusi = $distribusi->keys()->toArray();
        $dataDistribusi = $distribusi->values()->toArray();


        // ==========================================
        // 4. PERBANDINGAN KELAS (BAR CHART)
        // ==========================================
        $performaKelas = (clone $query)
            ->select('kelas.nama_kelas', DB::raw("AVG($sqlNilai) as rata_rata"))
            ->groupBy('kelas.nama_kelas')
            ->orderBy('nama_kelas')
            ->get();


        // ==========================================
        // 5. WATCHLIST (SISWA RAWAN)
        // ==========================================
        $siswaRawan = (clone $query)
            ->select('siswas.nama', 'kelas.nama_kelas', 'mapels.nama_mapel', 'mapels.kkm', DB::raw("$sqlNilai as nilai_akhir"))
            ->whereRaw("$sqlNilai < mapels.kkm") 
            ->orderBy('nilai_akhir', 'asc')
            ->limit(10)
            ->get();


        return view('livewire.statistik-akademik', [
            'stats' => $statsUtama,
            'komponen' => $komponenNilai,
            
            'labelDistribusi' => $labelDistribusi,
            'dataDistribusi' => $dataDistribusi,

            'labelKelas' => $performaKelas->pluck('nama_kelas')->toArray(),
            'dataKelas' => $performaKelas->map(fn($i) => round($i->rata_rata, 1))->toArray(),

            'siswaRawan' => $siswaRawan
        ])
        ->extends('layout.main')
        ->section('content');
    }
}