<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;

class SiswaIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    // --- PROPERTI DATA SISWA ---
    public $nis, $nama, $jenis_kelamin, $kelas_id;
    public $tempat_lahir, $tanggal_lahir, $alamat, $nama_ayah, $nama_ibu;
    public $foto, $foto_lama; // foto = file baru, foto_lama = string path db
    public $siswa_id;

    // --- PROPERTI FILTER & SEARCH ---
    public $search = '';
    public $filter_jk = '';
    public $filter_kelas = '';

    // --- PROPERTI UI STATE (PENGGANTI JS) ---
    public $isModalOpen = false;    // Untuk Add/Edit
    public $isDetailOpen = false;   // Untuk Detail
    public $isEditMode = false;     // Membedakan Add vs Edit
    public $isDetailEditMode = false; // Mode edit di dalam detail modal (jika diperlukan)

    // Reset pagination saat searching
    public function updatingSearch() { $this->resetPage(); }

    // --- LOGIC CRUD ---
    
    // 1. Membuka Modal Tambah
    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    // 2. Membuka Modal Edit
    public function edit($id)
    {
        $this->loadData($id);
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    // 3. Membuka Modal Detail
    public function detail($id)
    {
        $this->loadData($id);
        $this->isDetailEditMode = false; // Default view mode
        $this->isDetailOpen = true;
    }

    // Helper: Load Data Siswa ke Properti Livewire
    private function loadData($id)
    {
        $siswa = Siswa::findOrFail($id);
        $this->siswa_id = $id;
        $this->nis = $siswa->nis;
        $this->nama = $siswa->nama;
        $this->jenis_kelamin = $siswa->jenis_kelamin;
        $this->kelas_id = $siswa->kelas_id;
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir;
        $this->alamat = $siswa->alamat;
        $this->nama_ayah = $siswa->nama_ayah;
        $this->nama_ibu = $siswa->nama_ibu;
        $this->foto_lama = $siswa->foto;
    }

    // 4. Simpan Data (Store / Update)
    public function store()
    {
        // Validasi
        $rules = [
            'nis' => 'required|unique:siswas,nis,' . $this->siswa_id,
            'nama' => 'required',
            'kelas_id' => 'required',
            'jenis_kelamin' => 'required',
           'foto' => 'nullable|image|max:5120',
        ];
        $this->validate($rules);

        $data = [
            'nis' => $this->nis,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kelas_id' => $this->kelas_id,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
        ];

        // Handle Foto
        if ($this->foto) {
            $data['foto'] = $this->foto->store('fotos', 'public');
            // Hapus foto lama jika sedang edit
            if ($this->siswa_id && $this->foto_lama) {
                if(Storage::disk('public')->exists($this->foto_lama)){
                    Storage::disk('public')->delete($this->foto_lama);
                }
            }
        }

        if ($this->siswa_id) {
            Siswa::find($this->siswa_id)->update($data);
            session()->flash('success', 'Data Siswa berhasil diperbarui.');
        } else {
            Siswa::create($data);
            session()->flash('success', 'Data Siswa berhasil ditambahkan.');
        }

        $this->closeModal();
        $this->resetInputFields();
    }

    // 5. Hapus Data
    public function delete($id)
    {
        $siswa = Siswa::find($id);
        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }
        $siswa->delete();
        session()->flash('success', 'Data Siswa berhasil dihapus.');
    }

    // --- HELPER FUNCTIONS ---
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDetailOpen = false;
        $this->resetInputFields();
    }

    public function toggleDetailEditMode()
    {
        $this->isDetailEditMode = !$this->isDetailEditMode;
    }

    private function resetInputFields()
    {
        $this->nis = ''; $this->nama = ''; $this->jenis_kelamin = ''; $this->kelas_id = '';
        $this->tempat_lahir = ''; $this->tanggal_lahir = ''; $this->alamat = '';
        $this->nama_ayah = ''; $this->nama_ibu = ''; $this->foto = null; $this->foto_lama = null;
        $this->siswa_id = null;
    }

    public function render()
    {
        $query = Siswa::with('kelas');

        // Logic Search & Filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama', 'like', '%'.$this->search.'%')
                  ->orWhere('nis', 'like', '%'.$this->search.'%');
            });
        }
        if ($this->filter_jk) {
            $query->where('jenis_kelamin', $this->filter_jk);
        }
        if ($this->filter_kelas) {
            $query->where('kelas_id', $this->filter_kelas);
        }

        return view('livewire.siswa-index', [
            'siswas' => $query->latest()->paginate(10),
            'kelas' => Kelas::all()
        ])
        ->extends('layout.main')
        ->section('content');
    }
}