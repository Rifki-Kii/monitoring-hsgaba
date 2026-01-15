<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\MasterPelanggaran;
use App\Models\CatatanPelanggaran;
use App\Models\Kelas; // <--- Import Model Kelas
use Carbon\Carbon;

class PoinKedisiplinan extends Component
{
    use WithPagination;

    // Input Form
    public $search = '';
    public $siswa_id;
    public $master_pelanggaran_id;
    public $tanggal;
    public $keterangan;

    // Filter Periode & Kelas (Untuk Tabel Bawah)
    public $selectedMonth; // Format: "Y-m"
    public $availableMonths = []; 
    
    // --- FILTER BARU ---
    public $selectedKelas = ''; // Menyimpan ID kelas yang dipilih
    public $availableKelas = []; // List semua kelas

    // Data Tampilan
    public $selectedSiswa = null;
    public $poinBulanIni = 0; 
    public $riwayatPelanggaran = []; 
    public $masterPelanggarans = [];

    // Popup Alert
    public $showSanctionAlert = false;

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->masterPelanggarans = MasterPelanggaran::all();
        
        // Load Data Kelas untuk Dropdown Filter
        $this->availableKelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        // Default: Bulan Sekarang
        $this->selectedMonth = date('Y-m');

        // Generate Dropdown 12 Bulan
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $this->availableMonths[] = [
                'value' => $date->format('Y-m'),
                'label' => $date->translatedFormat('F Y') 
            ];
        }
    }

    // Reset pagination saat filter berubah
    public function updatedSelectedMonth() { $this->resetPage(); }
    public function updatedSelectedKelas() { $this->resetPage(); } // <--- Reset saat kelas ganti

    public function updatedSearch()
    {
        $this->selectedSiswa = null;
        $this->siswa_id = null;
        $this->riwayatPelanggaran = [];
        $this->resetPage(); 
    }

    public function selectSiswa($id)
    {
        $this->siswa_id = $id;
        $this->selectedSiswa = Siswa::find($id);
        
        if($this->selectedSiswa) {
            $this->search = $this->selectedSiswa->nama; 
            $this->loadDataSiswa();
            $this->resetPage(); 
        }
    }

    public function loadDataSiswa()
    {
        if ($this->selectedSiswa) {
            $this->poinBulanIni = $this->selectedSiswa->poin_bulan_ini;

            $this->riwayatPelanggaran = CatatanPelanggaran::with('masterPelanggaran')
                ->where('siswa_id', $this->siswa_id)
                ->latest()
                ->get();
        }
    }

    public function store()
    {
        $this->validate([
            'siswa_id' => 'required',
            'master_pelanggaran_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        CatatanPelanggaran::create([
            'siswa_id' => $this->siswa_id,
            'master_pelanggaran_id' => $this->master_pelanggaran_id,
            'tanggal' => $this->tanggal,
            'keterangan' => $this->keterangan
        ]);

        $this->selectedMonth = date('Y-m', strtotime($this->tanggal));
        $this->loadDataSiswa();
        
        $this->master_pelanggaran_id = null;
        $this->keterangan = null;
        $this->resetPage(); 

        if ($this->poinBulanIni >= 20) {
            $this->showSanctionAlert = true;
        } else {
            session()->flash('success', 'Pelanggaran berhasil dicatat.');
        }
    }

    public function delete($id)
    {
        $catatan = CatatanPelanggaran::find($id);
        if($catatan) {
            $siswaIdTerdampak = $catatan->siswa_id;
            $catatan->delete();

            if($this->selectedSiswa && $this->selectedSiswa->id == $siswaIdTerdampak) {
                $this->loadDataSiswa();
            }
            session()->flash('success', 'Data dihapus.');
        }
    }

    public function resetFilterTabel()
    {
        $this->selectedSiswa = null;
        $this->siswa_id = null;
        $this->search = '';
        $this->riwayatPelanggaran = []; 
        
        // Reset Filter Kelas juga jika mau (Opsional)
        // $this->selectedKelas = ''; 
        
        $this->resetPage();
    }

    public function closeAlert()
    {
        $this->showSanctionAlert = false;
    }

    public function render()
    {
        // 1. Logic Search Siswa (Dropdown Autocomplete)
        $searchResults = [];
        if (strlen($this->search) >= 2 && !$this->selectedSiswa) {
            $searchResults = Siswa::with('kelas')
                ->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('nis', 'like', '%' . $this->search . '%')
                ->take(5)->get();
        } elseif (strlen($this->search) >= 2 && $this->selectedSiswa && $this->search != $this->selectedSiswa->nama) {
            $searchResults = Siswa::with('kelas')
                ->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('nis', 'like', '%' . $this->search . '%')
                ->take(5)->get();
        }

        // 2. QUERY TABEL BAWAH (Filter Kombinasi)
        $query = CatatanPelanggaran::with(['siswa.kelas', 'masterPelanggaran']);

        // Filter 1: Sesuai Bulan
        if ($this->selectedMonth) {
            $parts = explode('-', $this->selectedMonth);
            $query->whereMonth('tanggal', $parts[1])
                  ->whereYear('tanggal', $parts[0]);
        }

        // Filter 2: Jika Siswa Dipilih (Prioritas Tertinggi)
        if ($this->siswa_id) {
            $query->where('siswa_id', $this->siswa_id);
        } 
        // Filter 3: Jika Siswa TIDAK dipilih, baru cek Filter Kelas
        elseif ($this->selectedKelas) {
            // Gunakan whereHas untuk memfilter berdasarkan relasi (tabel siswa -> kelas_id)
            $query->whereHas('siswa', function($q) {
                $q->where('kelas_id', $this->selectedKelas);
            });
        }

        $semuaPelanggaran = $query->latest()->paginate(10);

        return view('livewire.poin-kedisiplinan', [
            'searchResults' => $searchResults,
            'semuaPelanggaran' => $semuaPelanggaran
        ])
        ->extends('layout.main')
        ->section('content');
    }
}