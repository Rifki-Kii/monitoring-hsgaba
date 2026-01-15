<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NilaiExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $kelas_id, $mapel_id, $semester, $tahun_ajaran, $kkm;

    public function __construct($kelas_id, $mapel_id, $semester, $tahun_ajaran, $kkm)
    {
        $this->kelas_id = $kelas_id;
        $this->mapel_id = $mapel_id;
        $this->semester = $semester;
        $this->tahun_ajaran = $tahun_ajaran;
        $this->kkm = $kkm;
    }

    public function view(): View
    {
        $kelas = Kelas::find($this->kelas_id);
        $mapel = Mapel::find($this->mapel_id);

        $siswas = Siswa::where('kelas_id', $this->kelas_id)->orderBy('nama', 'asc')->get();
        $data = [];

        foreach ($siswas as $siswa) {
            $nilai = Nilai::where('siswa_id', $siswa->id)
                ->where('mapel_id', $this->mapel_id)
                ->where('semester', $this->semester)
                ->where('tahun_ajaran', $this->tahun_ajaran)
                ->first();

            $data[] = [
                'siswa' => $siswa,
                'nilai' => $nilai
            ];
        }

        return view('exports.nilai', [
            'data' => $data,
            'kkm' => $this->kkm,
            'nama_kelas' => $kelas->nama_kelas,
            'nama_mapel' => $mapel->nama_mapel,
            'semester' => $this->semester,
            'tahun_ajaran' => $this->tahun_ajaran
        ]);
    }

    // --- STYLING EXCEL AGAR RAPI ---
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow(); 
                
                // 1. JUDUL HEADER (Baris 5) - Text Putih, Bold, Center
                $sheet->getStyle('A5:K5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);

                // 2. BORDER SELURUH TABEL (Hitam Tipis)
                $sheet->getStyle('A5:K' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    ],
                ]);

                // 3. RATA TENGAH UNTUK SEMUA KOLOM NILAI (C sampai K)
                $sheet->getStyle('C6:K' . $highestRow)->getAlignment()->setHorizontal('center');
                
                // 4. ID SISWA (A) CENTER
                $sheet->getStyle('A6:A' . $highestRow)->getAlignment()->setHorizontal('center');

                // 5. ATUR LEBAR KOLOM
                $sheet->getColumnDimension('A')->setWidth(10); // ID
                $sheet->getColumnDimension('B')->setWidth(30); // Nama
                $sheet->getColumnDimension('C')->setWidth(12); // UH
                $sheet->getColumnDimension('D')->setWidth(12); // Tugas
                $sheet->getColumnDimension('E')->setWidth(12); // PTS
                $sheet->getColumnDimension('F')->setWidth(12); // PAS
                $sheet->getColumnDimension('G')->setWidth(15); // Kognitif (N)
                $sheet->getColumnDimension('H')->setWidth(15); // Praktek
                $sheet->getColumnDimension('I')->setWidth(18); // Nilai Raport
                $sheet->getColumnDimension('J')->setWidth(10); // Mutu
                $sheet->getColumnDimension('K')->setWidth(15); // Status
            },
        ];
    }
}