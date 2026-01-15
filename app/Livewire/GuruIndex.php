<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas; // <--- Jangan lupa import Model Kelas

class GuruIndex extends Component
{
    use WithPagination;

    // Properti Form (Tetap sama)
    public $nama_guru, $nip, $email, $no_hp, $jenis_kelamin, $alamat;
    public $selected_mapels = [];
    public $guru_id;
    public $isEdit = false;

    // --- PROPERTI BARU (SEARCH & FILTER) ---
    public $search = '';
    public $filter_mapel = '';
    public $filter_kelas = '';

    // Reset pagination jika user melakukan pencarian/filter
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterMapel() { $this->resetPage(); }
    public function updatingFilterKelas() { $this->resetPage(); }

    protected function rules() { 
        return [
            'nama_guru' => 'required|string|max:255',
            'nip' => 'nullable|numeric|unique:gurus,nip,' . $this->guru_id,
            'email' => 'nullable|email',
            'no_hp' => 'nullable|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'selected_mapels' => 'array',
        ];
    }

    // ... (Fungsi resetInputFields, store, edit, update, delete, cancel BIARKAN SAMA) ...
    private function resetInputFields(){
       $this->nama_guru = ''; $this->nip = ''; $this->email = ''; $this->no_hp = '';
       $this->jenis_kelamin = ''; $this->alamat = ''; $this->selected_mapels = [];
       $this->guru_id = ''; $this->isEdit = false;
    }
    public function store() { $this->validate(); $guru = Guru::create(['nama_guru'=>$this->nama_guru,'nip'=>$this->nip,'email'=>$this->email,'no_hp'=>$this->no_hp,'jenis_kelamin'=>$this->jenis_kelamin,'alamat'=>$this->alamat]); if(!empty($this->selected_mapels)){$guru->mapels()->attach($this->selected_mapels);} session()->flash('message','Guru ditambahkan'); $this->resetInputFields(); }
    public function edit($id) { $guru=Guru::with('mapels')->findOrFail($id); $this->guru_id=$id; $this->nama_guru=$guru->nama_guru; $this->nip=$guru->nip; $this->email=$guru->email; $this->no_hp=$guru->no_hp; $this->jenis_kelamin=$guru->jenis_kelamin; $this->alamat=$guru->alamat; $this->selected_mapels=$guru->mapels->pluck('id')->toArray(); $this->isEdit=true;}
    public function update() { $this->validate(); $guru=Guru::findOrFail($this->guru_id); $guru->update(['nama_guru'=>$this->nama_guru,'nip'=>$this->nip,'email'=>$this->email,'no_hp'=>$this->no_hp,'jenis_kelamin'=>$this->jenis_kelamin,'alamat'=>$this->alamat]); $guru->mapels()->sync($this->selected_mapels); session()->flash('message','Data diupdate'); $this->resetInputFields();}
    public function delete($id) { Guru::find($id)->delete(); session()->flash('message','Data dihapus');}
    public function cancel() { $this->resetInputFields(); }

    // --- LOGIC UTAMA PENCARIAN & FILTER ---
    public function render()
    {
        // 1. Query Awal
        $query = Guru::with(['mapels', 'waliKelas']);

        // 2. Search (Nama atau NIP)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_guru', 'like', '%' . $this->search . '%')
                  ->orWhere('nip', 'like', '%' . $this->search . '%');
            });
        }

        // 3. Filter Mapel
        if ($this->filter_mapel) {
            $query->whereHas('mapels', function($q) {
                $q->where('mapels.id', $this->filter_mapel);
            });
        }

        // 4. Filter Wali Kelas (Cari guru yg jadi wali di kelas X)
        if ($this->filter_kelas) {
            $query->whereHas('waliKelas', function($q) {
                $q->where('kelas.id', $this->filter_kelas);
            });
        }

        // Ambil Data Pendukung
        $gurus = $query->latest()->paginate(10);
        $all_mapels = Mapel::orderBy('nama_mapel', 'asc')->get();
        $all_kelas = Kelas::orderBy('nama_kelas', 'asc')->get(); // Untuk dropdown filter

        return view('livewire.guru-index', [
            'gurus' => $gurus,
            'all_mapels' => $all_mapels,
            'all_kelas' => $all_kelas
        ])
        ->extends('layout.main')
        ->section('content');
    }
}