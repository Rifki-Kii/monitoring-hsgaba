<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\CatatanPelanggaran;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LegerExport;
use App\Exports\LaporanKedisiplinanExport;

class LaporanIndex extends Component
{
    public $activeTab = 'akademik'; 
    public $filterKelas; 
    public $listKelas;

    // STATE AKADEMIK
    public $allMapels;
    public $selectedMapelIds = [];
    public $showMapelFilter = false;
    
    // STATE KEDISIPLINAN
    public $startDate;
    public $endDate;

    // STATE MODAL
    public $showModal = false; 
    public $modalType = ''; 
    public $selectedSiswa = null;
    public $dataModal = []; 
    public $totalPoinSiswa = 0; 

    public function mount()
    {
        $this->listKelas = Kelas::orderBy('nama_kelas')->get();
        $this->allMapels = Mapel::orderBy('nama_mapel')->get();
        
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
        
        // DEFAULT START: Masuk ke Akademik, pilih kelas pertama
        $this->filterKelas = $this->listKelas->first()->id ?? null;
        $this->loadMapelOtomatis();
    }

    // --- LOGIC PINDAH TAB (PENTING) ---
    public function setTab($tab)
    {
        $this->activeTab = $tab;

        if ($tab == 'akademik') {
            // Jika masuk Akademik, WAJIB pilih satu kelas (jangan kosong)
            if (empty($this->filterKelas)) {
                $this->filterKelas = $this->listKelas->first()->id ?? null;
            }
            $this->loadMapelOtomatis();
        } 
        elseif ($tab == 'kedisiplinan') {
            // Jika masuk Kedisiplinan, DEFAULT-nya adalah SEMUA SISWA (Kosong)
            $this->filterKelas = ''; 
        }
    }

    public function updatedFilterKelas()
    {
        if ($this->activeTab == 'akademik') {
            $this->loadMapelOtomatis();
        }
    }

    public function loadMapelOtomatis()
    {
        if ($this->filterKelas) {
            $mapelAdaNilai = DB::table('nilais')
                ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
                ->where('siswas.kelas_id', $this->filterKelas)
                ->distinct()->pluck('nilais.mapel_id')->toArray();

            $this->selectedMapelIds = !empty($mapelAdaNilai) ? $mapelAdaNilai : $this->allMapels->pluck('id')->toArray();
        }
    }

    // --- DOWNLOAD EXCEL ---\\
    public function downloadExcel()
    {
        // 1. Tentukan Nama File & Judul Laporan berdasarkan Filter
        if ($this->filterKelas) {
            $kelas = $this->listKelas->find($this->filterKelas);
            $namaKelas = $kelas ? $kelas->nama_kelas : 'Kelas';
        } else {
            // Jika filter kosong (hanya bisa di kedisiplinan), judulnya SEMUA SISWA
            $namaKelas = 'SEMUA SISWA';
        }

        // 2. Cek Tab Aktif
        if ($this->activeTab == 'akademik') {
            // --- EXPORT LEGER (AKADEMIK) ---
            $data = $this->getDataAkademik();
            
            // Nama File: Leger_Nilai_Kelas_7A_20250127.xlsx
            $fileName = 'Leger_Nilai_' . str_replace(' ', '_', $namaKelas) . '_' . date('Ymd') . '.xlsx';
            
            return Excel::download(new LegerExport($data['legerData'], $data['mapels'], $namaKelas), $fileName);
        } 
        else {
            // --- EXPORT PELANGGARAN (KEDISIPLINAN) ---
            $data = $this->getDataKedisiplinan();
            
            // Map data agar sesuai struktur yang dibutuhkan View Excel
            // Kita ambil 'nama_kelas' dari hasil query dan masukkan ke key 'kelas'
            $exportData = $data->map(function($item){
                return [
                    'nama' => $item['nama'],
                    'kelas' => $item['nama_kelas'], // <--- PERBAIKAN UTAMA DI SINI (Agar muncul nama kelas asli)
                    'jumlah_kasus' => $item['jumlah_kasus'],
                    'total_poin' => $item['total_poin']
                ];
            });

            // Format Periode Laporan
            $periode = date('d M Y', strtotime($this->startDate)) . ' - ' . date('d M Y', strtotime($this->endDate));
            
            // Nama File: Laporan_Disiplin_SEMUA_SISWA_20250127.xlsx
            $fileName = 'Laporan_Disiplin_' . str_replace(' ', '_', $namaKelas) . '_' . date('Ymd') . '.xlsx';
            
            return Excel::download(new LaporanKedisiplinanExport($exportData, $namaKelas, $periode), $fileName);
        }
    }

    // --- DATA KEDISIPLINAN (SEMUA / PER KELAS) ---
    private function getDataKedisiplinan()
    {
        $query = Siswa::query();
        
        if ($this->filterKelas) {
            $query->where('kelas_id', $this->filterKelas);
        }

        // TAMBAHKAN 'kelas' DI SINI AGAR RINGAN
        $siswas = $query->with(['kelas', 'catatanPelanggarans' => function($q) {
                $q->whereBetween('tanggal', [$this->startDate, $this->endDate])
                  ->orderBy('tanggal', 'desc');
            }, 'catatanPelanggarans.masterPelanggaran'])
            ->get();

        $result = $siswas->map(function($siswa) {
            $poin = $siswa->catatanPelanggarans->sum(fn($c) => $c->masterPelanggaran->poin);
            
            // ... (logika listPelanggaran tetap sama) ...
            $listPelanggaran = $siswa->catatanPelanggarans->map(function($item) {
                return [
                    'jenis' => $item->masterPelanggaran->jenis_pelanggaran,
                    'tanggal' => date('d/m', strtotime($item->tanggal)),
                    'poin' => $item->masterPelanggaran->poin
                ];
            });

            return [
                'id' => $siswa->id, 
                'nama' => $siswa->nama, 
                'nis' => $siswa->nis,
                'nama_kelas' => $siswa->kelas->nama_kelas, // <--- TAMBAHAN PENTING
                'total_poin' => $poin, 
                'jumlah_kasus' => $siswa->catatanPelanggarans->count(),
                'list_pelanggaran' => $listPelanggaran
            ];
        });

        return $result->sortByDesc('total_poin')->values();
    }

    // --- DATA AKADEMIK ---
    private function getDataAkademik()
    {
        // Pastikan filter kelas ada (safety)
        if (!$this->filterKelas) return ['legerData' => [], 'mapels' => []];

        $displayMapels = $this->allMapels->whereIn('id', $this->selectedMapelIds);
        $legerData = [];

        $siswas = Siswa::where('kelas_id', $this->filterKelas)->orderBy('nama')->get();
        
        $sqlNilai = '(COALESCE(rata_uh, 0) + COALESCE(uts, 0) + COALESCE(uas, 0)) / 3';
        $rawNilai = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->where('siswas.kelas_id', $this->filterKelas)
            ->whereIn('nilais.mapel_id', $this->selectedMapelIds)
            ->select('nilais.siswa_id', 'nilais.mapel_id', DB::raw("$sqlNilai as nilai_akhir"))
            ->get();

        $formattedNilai = [];
        foreach ($rawNilai as $n) { $formattedNilai[$n->siswa_id][$n->mapel_id] = round($n->nilai_akhir); }

        foreach ($siswas as $s) {
            $total = 0; $count = 0;
            foreach ($displayMapels as $m) {
                if (isset($formattedNilai[$s->id][$m->id])) { $total += $formattedNilai[$s->id][$m->id]; $count++; }
            }
            $legerData[] = [
                'siswa' => $s,
                'nilai_per_mapel' => $formattedNilai[$s->id] ?? [],
                'rata_rata_total' => $count > 0 ? $total / $count : 0,
                'total_skor' => $total
            ];
        }
        usort($legerData, fn($a, $b) => $b['rata_rata_total'] <=> $a['rata_rata_total']);

        return ['legerData' => $legerData, 'mapels' => $displayMapels];
    }

    // Modal Logic (Sama)
    public function openModal($type, $siswaId) {
        $this->modalType = $type; 
        $this->selectedSiswa = Siswa::with('kelas')->find($siswaId);
        if ($this->selectedSiswa) {
            if ($type == 'rapor') {
                $sqlNilai = '(COALESCE(rata_uh, 0) + COALESCE(uts, 0) + COALESCE(uas, 0)) / 3';
                $this->dataModal = DB::table('nilais')->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')->where('nilais.siswa_id', $siswaId)->select('mapels.nama_mapel', 'mapels.kkm', 'nilais.rata_uh', 'nilais.uts', 'nilais.uas', DB::raw("$sqlNilai as nilai_akhir"))->get();
            } elseif ($type == 'sp') {
                $this->dataModal = CatatanPelanggaran::with('masterPelanggaran')->where('siswa_id', $siswaId)->orderBy('tanggal', 'desc')->get();
                $this->totalPoinSiswa = $this->dataModal->sum(fn($i) => $i->masterPelanggaran->poin);
            }
            $this->showModal = true;
        }
    }
    public function closeModal() { $this->showModal = false; $this->selectedSiswa = null; $this->dataModal = []; }

    public function render()
    {
        $dataView = [];
        if ($this->activeTab == 'akademik') {
            $dataView = $this->getDataAkademik();
        } else {
            $dataView['dataLaporan'] = $this->getDataKedisiplinan();
        }
        return view('livewire.laporan-index', $dataView)->extends('layout.main')->section('content');
    }
}