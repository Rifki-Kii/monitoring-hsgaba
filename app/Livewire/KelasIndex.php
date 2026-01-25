<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Mapel; // Pastikan Model Mapel sudah di-import

class KelasIndex extends Component
{
    use WithPagination;

    // --- Properti Data Kelas (CRUD) ---
    public $nama_kelas, $jenjang, $wali_guru_id;
    public $kelas_id;

    // --- State UI (Modal CRUD Kelas) ---
    public $isModalOpen = false;
    public $isEditMode = false;

    // --- State UI (Modal List Siswa) ---
    public $isStudentListOpen = false;
    public $selectedStudents = [];
    public $selectedClassName = '';

    // --- [BARU] State UI (Checkbox Mapel) ---
    public $selectedMapels = []; // Array untuk menampung ID Mapel yang dipilih

    // --- Search ---
    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    // --- Rules Validasi ---
    protected $rules = [
        'nama_kelas'     => 'required|string|max:50',
        'jenjang'        => 'required|in:SD,SMP',
        'wali_guru_id'   => 'nullable|exists:gurus,id',
        'selectedMapels' => 'array' // Validasi agar formatnya array
    ];

    public $isMapelListOpen = false;
    public $selectedMapelList = [];
    public $selectedClassNameForMapel = '';
    // =========================================================================
    // CRUD METHODS (KELAS & MAPEL)
    // =========================================================================

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        // 1. Simpan Data Kelas Utama
        $kelas = Kelas::updateOrCreate(['id' => $this->kelas_id], [
            'nama_kelas'   => $this->nama_kelas,
            'jenjang'      => $this->jenjang,
            'wali_guru_id' => $this->wali_guru_id ?: null, 
        ]);

        // 2. [PENTING] Simpan Relasi Mapel (Many-to-Many)
        // Fungsi sync() otomatis menghapus yang tidak dicentang & menambah yang dicentang
        if ($kelas) {
            $kelas->mapels()->sync($this->selectedMapels);
        }

        session()->flash('success', $this->kelas_id ? 'Kelas berhasil diperbarui.' : 'Kelas berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        // Eager load 'mapels' agar query lebih efisien
        $kelas = Kelas::with('mapels')->findOrFail($id);
        
        $this->kelas_id     = $id;
        $this->nama_kelas   = $kelas->nama_kelas;
        $this->jenjang      = $kelas->jenjang;
        $this->wali_guru_id = $kelas->wali_guru_id;

        // [BARU] Ambil ID mapel yang sudah terhubung dengan kelas ini
        // pluck('id') akan menghasilkan array contoh: [1, 3, 5]
        $this->selectedMapels = $kelas->mapels->pluck('id')->toArray();

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        // Karena di migration pakai cascadeOnDelete, data di tabel pivot otomatis hilang
        Kelas::find($id)->delete();
        session()->flash('success', 'Kelas berhasil dihapus.');
    }

    // =========================================================================
    // METHODS UNTUK LIST SISWA (POPUP)
    // =========================================================================

    public function showStudentList($kelasId)
    {
        $kelas = Kelas::find($kelasId);
        
        if ($kelas) {
            $this->selectedClassName = $kelas->nama_kelas;
            
            $this->selectedStudents = Siswa::where('kelas_id', $kelasId)
                                           ->orderBy('nama_lengkap', 'asc') // Pastikan nama kolom di DB 'nama_lengkap' atau 'nama'
                                           ->get();
            
            $this->isStudentListOpen = true;
        }
    }

    public function closeStudentList()
    {
        $this->isStudentListOpen = false;
        $this->selectedStudents = [];
        $this->selectedClassName = '';
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->nama_kelas = '';
        $this->jenjang = '';
        $this->wali_guru_id = '';
        $this->kelas_id = null;
        $this->selectedMapels = []; // [BARU] Reset checkbox mapel
    }

   public function showMapelList($kelasId)
    {
        $kelas = Kelas::find($kelasId);
        
        if ($kelas) {
            $this->selectedClassNameForMapel = $kelas->nama_kelas;
            
            // CUSTOM SORTING: Tsaqafah Islam -> Pengetahuan Umum -> Keterampilan
            $this->selectedMapelList = $kelas->mapels()
                ->orderByRaw("
                    CASE 
                        WHEN kategori = 'Tsaqafah Islam' THEN 1
                        WHEN kategori = 'Pengetahuan Umum' THEN 2
                        WHEN kategori = 'Keterampilan' THEN 3
                        ELSE 4 
                    END
                ")
                ->orderBy('nama_mapel', 'asc') // Jika kategori sama, urutkan nama mapel A-Z
                ->get();
            
            $this->isMapelListOpen = true;
        }
    }

    public function closeMapelList()
    {
        $this->isMapelListOpen = false;
        $this->selectedMapelList = [];
        $this->selectedClassNameForMapel = '';
    }

    public function render()
    {
        $query = Kelas::with('waliKelas')->withCount('siswas'); 

        if ($this->search) {
            $query->where('nama_kelas', 'like', '%' . $this->search . '%')
                  ->orWhereHas('waliKelas', function($q) {
                      $q->where('nama_guru', 'like', '%' . $this->search . '%');
                  });
        }

        // [BARU] Ambil data Mapel untuk ditampilkan di Checkbox Modal
        $allMapels = Mapel::orderBy('kategori')->orderBy('nama_mapel')->get();

        return view('livewire.kelas-index', [
            'kelases'   => $query->latest()->paginate(10),
            'gurus'     => Guru::orderBy('nama_guru')->get(),
            'allMapels' => $allMapels // Kirim variabel ini ke View
        ])
        ->extends('layout.main')
        ->section('content');
    }
}