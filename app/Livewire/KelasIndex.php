<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa; // <--- PERLU DITAMBAHKAN

class KelasIndex extends Component
{
    use WithPagination;

    // Properti Data Kelas (CRUD)
    public $nama_kelas, $jenjang, $wali_guru_id;
    public $kelas_id;

    // State UI (Modal CRUD Kelas)
    public $isModalOpen = false;
    public $isEditMode = false;

    // --- [BARU] STATE UI (Modal List Siswa) ---
    public $isStudentListOpen = false;
    public $selectedStudents = []; // Menampung data siswa
    public $selectedClassName = ''; // Menampung nama kelas untuk judul modal

    // Search
    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    // Rules Validasi
    protected $rules = [
        'nama_kelas'   => 'required|string|max:50',
        'jenjang'      => 'required|in:SD,SMP',
        'wali_guru_id' => 'nullable|exists:gurus,id',
    ];

    // --- CRUD METHODS (KELAS) ---

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        Kelas::updateOrCreate(['id' => $this->kelas_id], [
            'nama_kelas'   => $this->nama_kelas,
            'jenjang'      => $this->jenjang,
            'wali_guru_id' => $this->wali_guru_id ?: null, 
        ]);

        session()->flash('success', $this->kelas_id ? 'Kelas berhasil diperbarui.' : 'Kelas berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $this->kelas_id     = $id;
        $this->nama_kelas   = $kelas->nama_kelas;
        $this->jenjang      = $kelas->jenjang;
        $this->wali_guru_id = $kelas->wali_guru_id;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Kelas::find($id)->delete();
        session()->flash('success', 'Kelas berhasil dihapus.');
    }

    // --- [BARU] METHODS UNTUK LIST SISWA ---

    public function showStudentList($kelasId)
    {
        $kelas = Kelas::find($kelasId);
        
        if ($kelas) {
            $this->selectedClassName = $kelas->nama_kelas;
            
            // Ambil siswa yang terdaftar di kelas ini
            $this->selectedStudents = Siswa::where('kelas_id', $kelasId)
                                           ->orderBy('nama', 'asc')
                                           ->get();
            
            // Buka Modal
            $this->isStudentListOpen = true;
        }
    }

    public function closeStudentList()
    {
        $this->isStudentListOpen = false;
        $this->selectedStudents = []; // Reset data agar hemat memori
        $this->selectedClassName = '';
    }

    // --- HELPERS ---

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

        $gurus = Guru::orderBy('nama_guru')->get();

        return view('livewire.kelas-index', [
            'kelases' => $query->latest()->paginate(10),
            'gurus'   => $gurus
        ])
        ->extends('layout.main')
        ->section('content');
    }
}