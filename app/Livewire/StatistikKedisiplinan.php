<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\MasterPelanggaran;
use Illuminate\Support\Facades\DB;

class StatistikKedisiplinan extends Component
{
    // Filter State
    public $filterTahun;
    public $filterBulan;
    public $filterKelas = '';

    // Data Dropdown
    public $listKelas;

    public function mount()
    {
        $this->filterTahun = date('Y');
        $this->filterBulan = ''; // Kosong = Semua Bulan
        $this->listKelas = Kelas::orderBy('nama_kelas')->get();
    }

    public function render()
    {
        // BASE QUERY
        // Join tabel: Catatan -> Siswa -> Kelas -> Master Pelanggaran
        $query = DB::table('catatan_pelanggarans')
            ->join('siswas', 'catatan_pelanggarans.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->join('master_pelanggarans', 'catatan_pelanggarans.master_pelanggaran_id', '=', 'master_pelanggarans.id');

        // TERAPKAN FILTER
        if ($this->filterTahun) {
            $query->whereYear('catatan_pelanggarans.tanggal', $this->filterTahun);
        }
        if ($this->filterBulan) {
            $query->whereMonth('catatan_pelanggarans.tanggal', $this->filterBulan);
        }
        if ($this->filterKelas) {
            $query->where('siswas.kelas_id', $this->filterKelas);
        }

        // ==========================================
        // 1. STATISTIK UTAMA (CARDS)
        // ==========================================
        $stats = (clone $query)->selectRaw("
            COUNT(*) as total_kasus,
            SUM(master_pelanggarans.poin) as total_poin,
            COUNT(DISTINCT siswas.id) as siswa_terlibat
        ")->first();

        // Cari Pelanggaran Paling Populer
        $topJenis = (clone $query)
            ->select('master_pelanggarans.jenis_pelanggaran', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('master_pelanggarans.jenis_pelanggaran')
            ->orderByDesc('jumlah')
            ->first();


        // ==========================================
        // 2. TREN BULANAN (LINE CHART)
        // ==========================================
        // Note: Chart ini sebaiknya tetap setahun penuh meski filter bulan aktif,
        // agar user tetap bisa melihat konteks perbandingannya.
        // Kita buat query terpisah sedikit agar CHART TREN tidak terpengaruh filter bulan (opsional).
        // Tapi untuk konsistensi UI saat ini, kita ikuti filter saja.
        
        $trenData = (clone $query)
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Isi data bulan 1-12 (agar grafik tidak bolong)
        $dataTren = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataTren[] = $trenData[$i] ?? 0;
        }


        // ==========================================
        // 3. KOMPOSISI JENIS PELANGGARAN (DONUT)
        // ==========================================
        $jenisData = (clone $query)
            ->select('master_pelanggarans.jenis_pelanggaran', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('master_pelanggarans.jenis_pelanggaran')
            ->orderByDesc('jumlah')
            ->limit(5) // Ambil Top 5 saja
            ->get();

        $labelJenis = $jenisData->pluck('jenis_pelanggaran')->toArray();
        $dataJenis = $jenisData->pluck('jumlah')->toArray();


        // ==========================================
        // 4. KELAS PALING SERING MELANGGAR (BAR)
        // ==========================================
        // Diurutkan berdasarkan TOTAL POIN (Severity), bukan jumlah kasus.
        $kelasNakal = (clone $query)
            ->select('kelas.nama_kelas', DB::raw('SUM(master_pelanggarans.poin) as total_poin'))
            ->groupBy('kelas.nama_kelas')
            ->orderByDesc('total_poin')
            ->limit(10)
            ->get();


        // ==========================================
        // 5. BLACKLIST SISWA (TABLE)
        // ==========================================
        // Siswa dengan akumulasi poin tertinggi
        $blacklist = (clone $query)
            ->select(
                'siswas.nama', 
                'kelas.nama_kelas', 
                'siswas.nis',
                DB::raw('SUM(master_pelanggarans.poin) as akumulasi_poin'),
                DB::raw('COUNT(*) as jumlah_kasus')
            )
            ->groupBy('siswas.id', 'siswas.nama', 'kelas.nama_kelas', 'siswas.nis')
            ->orderByDesc('akumulasi_poin')
            ->limit(10)
            ->get();


        return view('livewire.statistik-kedisiplinan', [
            'stats' => $stats,
            'topJenis' => $topJenis,
            
            'dataTren' => $dataTren,
            
            'labelJenis' => $labelJenis,
            'dataJenis' => $dataJenis,

            'labelKelas' => $kelasNakal->pluck('nama_kelas')->toArray(),
            'dataKelas' => $kelasNakal->map(fn($k) => (int)$k->total_poin)->toArray(),

            'blacklist' => $blacklist
        ])
        ->extends('layout.main')
        ->section('content');
    }
}