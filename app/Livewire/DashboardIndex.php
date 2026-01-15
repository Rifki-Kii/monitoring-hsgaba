<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Mapel;

class DashboardIndex extends Component
{
    // Properties
    public $totalSiswa, $totalGuru, $totalKelas;
    public $rataRataNilai, $siswaRemedialCount;
    public $totalPelanggaranBulanIni, $siswaBermasalahCount;
    
    // Search
    public $globalSearch = '';

    public function mount()
    {
        // ... (Logic Statistik Dasar Sama seperti sebelumnya) ...
        $this->totalSiswa = Siswa::count();
        $this->totalGuru = Guru::count();
        $this->totalKelas = Kelas::count();
        $this->rataRataNilai = number_format(Nilai::avg('nilai_akhir') ?? 0, 1);
        $this->siswaRemedialCount = Nilai::where('nilai_akhir', '<', 75)->distinct('siswa_id')->count();
        
        // Dummy Data Disiplin
        $this->totalPelanggaranBulanIni = 12;
        $this->siswaBermasalahCount = 5;
    }

    public function render()
    {
        // 1. SISWA PERLU PERHATIAN (Diupdate dengan fitur Search)
        $studentsAtRisk = Siswa::with('nilais', 'kelas')
            ->when($this->globalSearch, function($q) {
                $q->where('nama', 'like', '%'.$this->globalSearch.'%')
                  ->orWhere('nis', 'like', '%'.$this->globalSearch.'%');
            })
            ->take(5) // Limit 5 biar ga berat
            ->get()
            ->map(function($s) {
                $nilaiAkhir = $s->nilais->avg('nilai_akhir') ?? 0;
                return [
                    'nama' => $s->nama,
                    'kelas' => $s->kelas->nama_kelas ?? '-',
                    'nilai' => number_format($nilaiAkhir, 1),
                    'poin' => rand(0, 40), // Dummy Poin
                    'status' => $nilaiAkhir < 75 ? 'Remedial' : 'Aman'
                ];
            });

        // 2. [BARU] DISTRIBUSI GRADE (Analisis Kualitas)
        // Menghitung berapa siswa dapat A, B, C, D
        $grades = Nilai::all()->map(function($n) {
            return $n->predikat; 
        })->countBy(); // Hasil: ['A' => 10, 'B' => 5, ...]

        $gradeDistribution = [
            'A' => $grades['A'] ?? 0,
            'B' => $grades['B'] ?? 0,
            'C' => $grades['C'] ?? 0,
            'D' => $grades['D'] ?? 0, // D & E digabung atau dipisah sesuai kebutuhan
        ];

        // 3. [BARU] MAPEL TERSULIT (Analisis Kurikulum)
        $hardestSubjects = Mapel::with('nilais')->get()->map(function($m) {
            return [
                'nama' => $m->nama_mapel,
                'avg' => number_format($m->nilais->avg('nilai_akhir') ?? 0, 1)
            ];
        })->sortBy('avg')->take(4); // Ambil 4 mapel dengan nilai terendah

        // 4. [BARU] TIMELINE PELANGGARAN TERBARU (Dummy Realtime)
        $recentViolations = [
            ['student' => 'Ahmad Dani', 'kelas' => '10A', 'type' => 'Terlambat', 'time' => '07:15 WIB', 'date' => 'Hari Ini'],
            ['student' => 'Siti Aminah', 'kelas' => '11B', 'type' => 'Tidak Berseragam', 'time' => 'Kemarin', 'date' => 'Senin'],
            ['student' => 'Budi Santoso', 'kelas' => '12C', 'type' => 'Bolos', 'time' => '12 Okt', 'date' => 'Minggu Lalu'],
        ];

        return view('livewire.dashboard-index', [
            'studentsAtRisk' => $studentsAtRisk,
            'gradeDistribution' => $gradeDistribution,
            'hardestSubjects' => $hardestSubjects,
            'recentViolations' => $recentViolations,
            'topClasses' => [] // (Bisa pakai logic sebelumnya)
        ])
        ->extends('layout.main')
        ->section('content');
    }
}