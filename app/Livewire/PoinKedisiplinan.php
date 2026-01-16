<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\MasterPelanggaran;
use App\Models\CatatanPelanggaran;
use App\Models\Kelas;
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

    // Filter Periode & Kelas
    public $selectedMonth; 
    public $availableMonths = []; 
    public $selectedKelas = ''; 
    public $availableKelas = []; 

    // Data Tampilan Sidebar
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
        $this->availableKelas = Kelas::orderBy('nama_kelas', 'asc')->get();
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

    public function updatedSelectedMonth() { $this->resetPage(); }
    public function updatedSelectedKelas() { $this->resetPage(); }

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
            // Hitung poin bulan ini untuk Sidebar Profil
            // (Asumsi di Model Siswa ada relasi/mutator, atau kita hitung manual disini)
            $this->poinBulanIni = CatatanPelanggaran::where('siswa_id', $this->siswa_id)
                ->whereMonth('tanggal', date('m'))
                ->join('master_pelanggarans', 'catatan_pelanggarans.master_pelanggaran_id', '=', 'master_pelanggarans.id')
                ->sum('master_pelanggarans.poin');

            $this->riwayatPelanggaran = CatatanPelanggaran::with('masterPelanggaran')
                ->where('siswa_id', $this->siswa_id)
                ->latest()
                ->take(5)
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

        // Update view
        $this->selectedMonth = date('Y-m', strtotime($this->tanggal));
        $this->loadDataSiswa();
        
        // Reset form input saja, jangan reset filter tabel
        $this->master_pelanggaran_id = null;
        $this->keterangan = null;

        if ($this->poinBulanIni >= 20) {
            $this->showSanctionAlert = true;
        } else {
            session()->flash('success', 'Pelanggaran berhasil dicatat.');
        }
    }



    // PROPERTI UNTUK MODAL SANKSI
    public $showSanksiModal = false;
    public $inputSanksi = ''; // Pilihan sanksi dari guru
    public $targetSiswaId = null;
    public $targetSiswaNama = '';

    public $targetSiswaPoin = 0;


    // Update Function
    public function openSanksiModal($siswaId)
    {
        // Gunakan Eager Loading untuk menghitung poin
        $siswa = Siswa::with(['catatanPelanggarans.masterPelanggaran'])->find($siswaId);
        
        if($siswa) {
            $this->targetSiswaId = $siswa->id;
            $this->targetSiswaNama = $siswa->nama;
            
            // Hitung Total Poin (Semua Waktu atau Bulan Ini, tergantung kebijakan)
            // Di sini kita hitung total semua poin karena sanksi biasanya akumulatif
            $this->targetSiswaPoin = $siswa->catatanPelanggarans->sum(function($c) {
                return $c->masterPelanggaran->poin;
            });

            $this->inputSanksi = $siswa->status_sanksi;
            $this->showSanksiModal = true;
        }
    }

    // FUNGSI 2: SIMPAN SANKSI MANUAL
    public function simpanSanksi()
    {
        $this->validate([
            'inputSanksi' => 'required'
        ]);

        $siswa = Siswa::find($this->targetSiswaId);
        if($siswa) {
            $siswa->update([
                'status_sanksi' => $this->inputSanksi
            ]);
            
            session()->flash('success', 'Status sanksi berhasil diperbarui.');
            $this->showSanksiModal = false;
            $this->resetPage(); // Refresh tabel
        }
    }

    // FUNGSI 3: TUTUP MODAL
    public function closeSanksiModal()
    {
        $this->showSanksiModal = false;
        $this->inputSanksi = '';
    }

    public function resetFilterTabel()
    {
        $this->selectedSiswa = null;
        $this->siswa_id = null;
        $this->search = '';
        $this->riwayatPelanggaran = []; 
        $this->resetPage();
    }

    public function closeAlert()
    {
        $this->showSanctionAlert = false;
    }

    public function render()
    {
        // 1. Search Siswa (Autocomplete)
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

        // 2. QUERY TABEL UTAMA (SISWA YANG MELANGGAR)
        // Kita query ke model Siswa, bukan Log, agar bisa di-group per siswa
        $query = Siswa::query();

        // Filter Tanggal (Wajib)
        $parts = explode('-', $this->selectedMonth);
        $bulan = $parts[1];
        $tahun = $parts[0];

        // Hanya ambil siswa yang punya catatan pelanggaran di bulan tsb
        $query->whereHas('catatanPelanggarans', function($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
              ->whereYear('tanggal', $tahun);
        });

        // Filter Siswa Tertentu (Jika dipilih dari search)
        if ($this->siswa_id) {
            $query->where('id', $this->siswa_id);
        } 
        // Filter Kelas (Jika Siswa tidak dipilih)
        elseif ($this->selectedKelas) {
            $query->where('kelas_id', $this->selectedKelas);
        }

        // Ambil Data + Eager Load Pelanggaran Bulan Ini
        // Kita filter relation-nya juga agar data yang ditarik cuma bulan ini
        $dataLaporan = $query->with(['kelas', 'catatanPelanggarans' => function($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
              ->whereYear('tanggal', $tahun)
              ->orderBy('tanggal', 'desc'); // Urutkan dari yang terbaru
        }, 'catatanPelanggarans.masterPelanggaran'])
        ->paginate(10);

        return view('livewire.poin-kedisiplinan', [
            'searchResults' => $searchResults,
            'dataLaporan' => $dataLaporan // Data Siswa (Grouped)
        ])
        ->extends('layout.main')
        ->section('content');
    }
}