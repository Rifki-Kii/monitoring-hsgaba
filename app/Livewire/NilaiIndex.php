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
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class NilaiIndex extends Component
{
    use WithFileUploads;

    // --- 1. DATA MASTER ---
    public $kelasList = [];
    public $mapelList = []; // <--- Ini nanti isinya berubah-ubah sesuai kelas
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
    public $fileImport;

    public function mount()
    {
        // 1. Ambil Semua Kelas
        $this->kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        
        // 2. Set Default Filter
        $this->tahun_ajaran = '2025/2026';
        $this->semester = 'genap';

        // 3. Set Default Kelas (Ambil yang pertama)
        if ($this->kelasList->count() > 0) {
            $this->kelas_id = $this->kelasList->first()->id;
        }

        // 4. [LOGIKA BARU] Load Mapel sesuai Kelas yang terpilih
        $this->loadMapelByKelas();

        // 5. Load Data Siswa & Nilai
        if ($this->kelas_id && $this->mapel_id) {
            $this->loadSiswa();
        }
    }

    // --- LISTENER SAAT FILTER BERUBAH ---

    public function updatedKelasId() 
    { 
        // Saat ganti kelas, Mapel harus direfresh
        $this->loadMapelByKelas(); 
        $this->loadSiswa(); 
    }

    public function updatedMapelId() 
    { 
        $this->updateKKM(); 
        $this->loadSiswa(); 
    }
    
    public function updatedTahunAjaran() { $this->loadSiswa(); } 
    public function updatedSemester() { $this->loadSiswa(); }    
    public function gantiMode($modeBaru) { $this->mode = $modeBaru; $this->loadSiswa(); }

    // --- [BARU] LOGIKA FILTER MAPEL ---
    public function loadMapelByKelas()
    {
        // Kosongkan dulu
        $this->mapelList = [];

        if ($this->kelas_id) {
            // Ambil data kelas beserta relasi mapels-nya
            $kelas = Kelas::with('mapels')->find($this->kelas_id);

            if ($kelas && $kelas->mapels->count() > 0) {
                // Isi Dropdown dengan Mapel milik kelas tersebut
                $this->mapelList = $kelas->mapels->sortBy('nama_mapel');
                
                // Otomatis pilih mapel pertama agar user tidak bingung
                $this->mapel_id = $this->mapelList->first()->id;
            } else {
                // Jika kelas belum di-setting mapelnya
                $this->mapel_id = null;
            }
        }
        
        // Update KKM sesuai mapel yang baru terpilih
        $this->updateKKM();
    }

    public function updateKKM()
    {
        if ($this->mapel_id) {
            $mapel = Mapel::find($this->mapel_id);
            $this->kkm = $mapel->kkm ?? 75;
        } else {
            $this->kkm = 0;
        }
    }

    public function loadSiswa()
    {
        // Reset data dulu
        $this->siswaList = [];
        $this->inputNilai = [];

        // Hanya load jika Kelas & Mapel sudah terpilih
        if ($this->kelas_id && $this->mapel_id) {
            
            $siswas = Siswa::where('kelas_id', $this->kelas_id)
                           ->orderBy('nama', 'asc') // Pastikan nama kolom di DB 'nama' atau 'nama_lengkap'
                           ->get();
            
            $this->siswaList = $siswas;

            foreach ($siswas as $siswa) {
                $existing = Nilai::where('siswa_id', $siswa->id)
                    ->where('mapel_id', $this->mapel_id)
                    ->where('semester', $this->semester)
                    ->where('tahun_ajaran', $this->tahun_ajaran)
                    ->first();

                $this->inputNilai[$siswa->id] = [
                    'rata_uh' => $existing->rata_uh ?? null, 
                    'tugas' => $existing->tugas ?? null, 
                    'pts' => $existing->uts ?? null, 
                    'pas' => $existing->uas ?? null, 
                    'keterampilan' => $existing->keterampilan ?? null,
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
        
        // --- 1. AMBIL INPUTAN (UBAH BAGIAN INI) ---
        // Tambahkan (float) di depannya agar string kosong "" atau null terbaca sebagai angka 0
        $rataUH = (float) ($data['rata_uh'] ?? 0);
        $nilaiB = (float) ($data['tugas'] ?? 0);
        $nilaiC = (float) ($data['pts'] ?? 0);
        $nilaiD = (float) ($data['pas'] ?? 0);

        // --- 2. HITUNG NILAI PENGETAHUAN (N) ---
        $nilaiPengetahuan = ($rataUH + $nilaiB + $nilaiC + $nilaiD) / 4;
        $nilaiPengetahuanBulat = round($nilaiPengetahuan); 

        // --- 3. HITUNG NILAI RAPORT ---
        // Ini juga tambahkan (float)
        $nilaiKeterampilan = (float) ($data['keterampilan'] ?? 0);
        
        $nilaiRaport = ($nilaiPengetahuanBulat + $nilaiKeterampilan) / 2;
        $nilaiRaportBulat = round($nilaiRaport);

        // --- 4. TENTUKAN PREDIKAT ---
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
                    
                    'rata_uh' => $nilai['rata_uh'],
                    'tugas' => $nilai['tugas'], 
                    'uts' => $nilai['pts'],     
                    'uas' => $nilai['pas'],     
                    'keterampilan' => $nilai['keterampilan'],
                    
                    'nilai_akhir' => $nilai['nilai_pengetahuan'],
                    'nilai_raport' => $nilai['nilai_raport'],
                    'predikat' => $nilai['predikat'],
                    'status' => $nilai['status'],
                ]
            );
        }
        session()->flash('message', 'Data Nilai Berhasil Disimpan!');
        $this->mode = 'view';
    }

    public function exportExcel()
    {
        if (!$this->kelas_id || !$this->mapel_id) {
            return session()->flash('error', 'Pilih Kelas dan Mapel terlebih dahulu.');
        }

        // Ambil nama kelas untuk nama file (Handle jika kelas tidak ditemukan)
        $kelas = $this->kelasList->where('id', $this->kelas_id)->first();
        $namaKelas = $kelas ? $kelas->nama_kelas : 'Unknown';

        $namaFile = 'Nilai_Kelas_' . $namaKelas . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new NilaiExport(
            $this->kelas_id, 
            $this->mapel_id, 
            $this->semester, 
            $this->tahun_ajaran,
            $this->kkm
        ), $namaFile);
    }

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

        $this->fileImport = null;
        $this->loadSiswa();
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