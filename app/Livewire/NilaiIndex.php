<?php

namespace App\Livewire;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Livewire\Component;
use App\Exports\NilaiExport;
use App\Imports\NilaiImport;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; // <--- WAJIB
use Maatwebsite\Excel\Facades\Excel; // <--- WAJIB

class NilaiIndex extends Component
{
    // --- 1. DATA MASTER ---
    public $kelasList = [];
    public $mapelList = [];
    public $tahunList = ['2023/2024', '2024/2025', '2025/2026', '2026/2027'];

    // --- 2. FILTER & STATE ---
    public $kelas_id;
    public $mapel_id;
    public $tahun_ajaran;
    public $semester;

    // --- 3. DATA & LOGIC ---
    public $siswaList = [];
    public $inputNilai = [];
    
    public $kkm = 75; 
    public $mode = 'view'; 

    use WithFileUploads; // <--- Pasang Trait ini

    // ... properti lama biarkan
    public $fileImport; // <---
    public function mount()
    {
        $this->kelasList = Kelas::all();
        $this->mapelList = Mapel::all();
        $this->tahun_ajaran = '2025/2026';
        $this->semester = 'genap';

        if ($this->kelasList->count() > 0) $this->kelas_id = $this->kelasList->first()->id;
        if ($this->mapelList->count() > 0) $this->mapel_id = $this->mapelList->first()->id;

        if ($this->kelas_id && $this->mapel_id) {
            $this->updateKKM();
            $this->loadSiswa();
        }
    }

    public function updatedKelasId() { $this->loadSiswa(); }
    public function updatedMapelId() { $this->updateKKM(); $this->loadSiswa(); }
    public function updatedTahunAjaran() { $this->loadSiswa(); } 
    public function updatedSemester() { $this->loadSiswa(); }    
    public function gantiMode($modeBaru) { $this->mode = $modeBaru; $this->loadSiswa(); }

    public function updateKKM()
    {
        if ($this->mapel_id) {
            $mapel = Mapel::find($this->mapel_id);
            $this->kkm = $mapel->kkm ?? 75;
        }
    }

    public function loadSiswa()
    {
        if ($this->kelas_id && $this->mapel_id) {
            $siswas = Siswa::where('kelas_id', $this->kelas_id)->orderBy('nama', 'asc')->get();
            $this->siswaList = $siswas;

            foreach ($siswas as $siswa) {
                $existing = Nilai::where('siswa_id', $siswa->id)
                    ->where('mapel_id', $this->mapel_id)
                    ->where('semester', $this->semester)
                    ->where('tahun_ajaran', $this->tahun_ajaran)
                    ->first();

                $this->inputNilai[$siswa->id] = [
                    // Kolom Inputan Utama (SUDAH BERSIH DARI UH1-3)
                    'rata_uh' => $existing->rata_uh ?? null, 
                    'tugas' => $existing->tugas ?? null, 
                    'pts' => $existing->uts ?? null, 
                    'pas' => $existing->uas ?? null, 
                    'keterampilan' => $existing->keterampilan ?? null,
                    
                    // Kolom Hasil Hitung
                    'nilai_pengetahuan' => $existing->nilai_akhir ?? 0, 
                    'nilai_raport' => $existing->nilai_raport ?? 0,
                    'predikat' => $existing->predikat ?? '-',
                    'status' => $existing->status ?? '-',
                ];
            }
        }
    }

    public function hitungNilai($siswaId)
    {
        $data = $this->inputNilai[$siswaId];
        
        // --- 1. AMBIL INPUTAN ---
        $rataUH = $data['rata_uh'] ?? 0; // Nilai A
        $nilaiB = $data['tugas'] ?? 0;   // Nilai B
        $nilaiC = $data['pts'] ?? 0;     // Nilai C
        $nilaiD = $data['pas'] ?? 0;     // Nilai D

        // --- 2. HITUNG NILAI PENGETAHUAN (N) ---
        // Rumus: (A + B + C + D) / 4
        $nilaiPengetahuan = ($rataUH + $nilaiB + $nilaiC + $nilaiD) / 4;
        $nilaiPengetahuanBulat = round($nilaiPengetahuan); 

        // --- 3. HITUNG NILAI RAPORT ---
        $nilaiKeterampilan = $data['keterampilan'] ?? 0;
        
        // Rumus Akhir = (Pengetahuan + Keterampilan) / 2
        $nilaiRaport = ($nilaiPengetahuanBulat + $nilaiKeterampilan) / 2;
        $nilaiRaportBulat = round($nilaiRaport);

        // --- 4. TENTUKAN PREDIKAT (DARI NILAI RAPORT) ---
        $predikat = 'D';
        if ($nilaiRaportBulat >= 95) $predikat = 'A+';
        elseif ($nilaiRaportBulat >= 90) $predikat = 'A';
        elseif ($nilaiRaportBulat >= 86) $predikat = 'B+';
        elseif ($nilaiRaportBulat >= 83) $predikat = 'B';
        elseif ($nilaiRaportBulat >= 79) $predikat = 'C+';
        elseif ($nilaiRaportBulat >= 75) $predikat = 'C';
        else $predikat = 'D'; 

        // --- 5. UPDATE ARRAY ---
        $this->inputNilai[$siswaId]['nilai_pengetahuan'] = $nilaiPengetahuanBulat;
        $this->inputNilai[$siswaId]['nilai_raport'] = number_format($nilaiRaport, 1);
        $this->inputNilai[$siswaId]['predikat'] = $predikat;
        $this->inputNilai[$siswaId]['status'] = ($nilaiRaportBulat >= $this->kkm) ? 'Tuntas' : 'Remedial';
    }

    public function simpanSemua()
    {
        $this->validate(['kelas_id' => 'required', 'mapel_id' => 'required']);

        foreach ($this->inputNilai as $siswaId => $nilai) {
            $this->hitungNilai($siswaId); 

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'mapel_id' => $this->mapel_id,
                    'semester' => $this->semester,
                    'tahun_ajaran' => $this->tahun_ajaran,
                ],
                [
                    'kelas_id' => $this->kelas_id,
                    'guru_id' => Auth::id(),
                    
                    'rata_uh' => $nilai['rata_uh'], // Simpan Rata UH
                    'tugas' => $nilai['tugas'], 
                    'uts' => $nilai['pts'],     
                    'uas' => $nilai['pas'],     
                    'keterampilan' => $nilai['keterampilan'],
                    
                    'nilai_akhir' => $nilai['nilai_pengetahuan'],
                    'nilai_raport' => $nilai['nilai_raport'],
                    'predikat' => $nilai['predikat'],
                    'status' => $nilai['status'],
                    
                    // PERBAIKAN: SAYA SUDAH MENGHAPUS 'uh1', 'uh2', 'uh3' DARI SINI
                ]
            );
        }
        session()->flash('message', 'Data Nilai Berhasil Disimpan!');
        $this->mode = 'view';
    }

    public function exportExcel()
    {
        // Validasi filter harus dipilih dulu
        if (!$this->kelas_id || !$this->mapel_id) {
            return session()->flash('error', 'Pilih Kelas dan Mapel terlebih dahulu.');
        }

        $namaFile = 'Nilai_Kelas_' . $this->kelasList->find($this->kelas_id)->nama_kelas . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new NilaiExport(
            $this->kelas_id, 
            $this->mapel_id, 
            $this->semester, 
            $this->tahun_ajaran,
            $this->kkm
        ), $namaFile);
    }

    // --- FITUR IMPORT ---
    public function importExcel()
    {
        $this->validate([
            'fileImport' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new NilaiImport(
            $this->kelas_id, 
            $this->mapel_id, 
            $this->semester, 
            $this->tahun_ajaran,
            $this->kkm
        ), $this->fileImport);

        $this->fileImport = null; // Reset file
        $this->loadSiswa(); // Refresh tabel
        session()->flash('message', 'Data Nilai Berhasil Di-import!');
    }

    public function render()
    {
        return view('livewire.nilai-index', [
            'kelasList' => $this->kelasList,
            'mapelList' => $this->mapelList,
        ])
        ->extends('layout.main')
        ->section('content');
    }
}