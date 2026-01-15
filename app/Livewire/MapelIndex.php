<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mapel;

class MapelIndex extends Component
{
    use WithPagination;

    // Properti Data (Form Input)
    public $kode_mapel, $nama_mapel, $kategori, $kkm;
    public $mapel_id;

    // UI State & Filter
    public $search = '';
    public $filterKategori = ''; // <--- PROPERTI BARU UNTUK FILTER
    public $isModalOpen = false;
    public $isEditMode = false;

    // Reset halaman saat search atau filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKategori() { $this->resetPage(); } // <--- RESET PAGE SAAT FILTER BERUBAH

    protected function rules()
    {
        return [
            'kode_mapel' => 'required|string|max:20|unique:mapels,kode_mapel,' . $this->mapel_id,
            'nama_mapel' => 'required|string|max:100',
            'kategori'   => 'required|in:Tsaqafah Islam,Pengetahuan Umum,Keterampilan',
            'kkm'        => 'required|integer|min:0|max:100',
        ];
    }

    // --- CRUD (Create, Store, Edit, Delete) ---
    // (Bagian ini TIDAK BERUBAH, sama seperti kode Anda sebelumnya)
    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        Mapel::updateOrCreate(['id' => $this->mapel_id], [
            'kode_mapel' => $this->kode_mapel,
            'nama_mapel' => $this->nama_mapel,
            'kategori'   => $this->kategori,
            'kkm'        => $this->kkm,
        ]);

        session()->flash('success', $this->mapel_id ? 'Mapel berhasil diperbarui.' : 'Mapel berhasil ditambahkan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        $this->mapel_id   = $id;
        $this->kode_mapel = $mapel->kode_mapel;
        $this->nama_mapel = $mapel->nama_mapel;
        $this->kategori   = $mapel->kategori;
        $this->kkm        = $mapel->kkm;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Mapel::find($id)->delete();
        session()->flash('success', 'Mapel berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->kode_mapel = '';
        $this->nama_mapel = '';
        $this->kategori = '';
        $this->kkm = '';
        $this->mapel_id = null;
    }
public function render()
    {
        $query = Mapel::with('gurus');

        // 1. Logic Pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_mapel', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_mapel', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Logic Filter Kategori
        if ($this->filterKategori) {
            $query->where('kategori', $this->filterKategori);
            
            // PERBAIKAN DISINI (Natural Sort):
            // 1. Urutkan berdasarkan Panjang teks dulu (agar 10 tidak nyelip di 1)
            // 2. Baru urutkan teksnya
            $query->orderByRaw('LENGTH(kode_mapel) ASC')
                  ->orderBy('kode_mapel', 'asc');
                  
        } else {
            // Default: Urutkan Nama A-Z
            $query->orderBy('nama_mapel', 'asc');
        }

        return view('livewire.mapel-index', [
            'mapels' => $query->paginate(10)
        ])
        ->extends('layout.main')
        ->section('content');
    }
}