<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanKedisiplinanExport implements FromView, WithEvents
{
    protected $dataSiswa;
    protected $namaKelas;
    protected $periode; // String misal: "1 Jan 2025 - 31 Jan 2025"

    public function __construct($dataSiswa, $namaKelas, $periode)
    {
        $this->dataSiswa = $dataSiswa;
        $this->namaKelas = $namaKelas;
        $this->periode = $periode;
    }

    public function view(): View
    {
        return view('exports.laporan_kedisiplinan_excel', [
            'siswas' => $this->dataSiswa,
            'namaKelas' => $this->namaKelas,
            'periode' => $this->periode
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $rowCount = count($this->dataSiswa);
                $lastRow = $rowCount + 5; // +5 untuk header
                
                // 1. STYLING JUDUL
                $sheet->mergeCells('A1:F1'); // Judul Utama
                $sheet->mergeCells('A2:F2'); // Sub Judul
                
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E293B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // 2. STYLING HEADER TABEL (Baris 4 & 5) - MERAH MAROON (Khas Disiplin)
                $sheet->mergeCells('A4:A5'); // No
                $sheet->mergeCells('B4:B5'); // Nama
                $sheet->mergeCells('C4:C5'); // Kelas
                $sheet->mergeCells('D4:E4'); // Statistik (Kasus & Poin)
                $sheet->mergeCells('F4:F5'); // Status
                
                $sheet->getStyle('A4:F5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF9F1239']], // Rose 800
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFFFFFF']]]
                ]);

                // 3. LEBAR KOLOM
                $sheet->getColumnDimension('A')->setWidth(6);  // No
                $sheet->getColumnDimension('B')->setWidth(35); // Nama
                $sheet->getColumnDimension('C')->setWidth(15); // Kelas
                $sheet->getColumnDimension('D')->setWidth(15); // Jml Kasus
                $sheet->getColumnDimension('E')->setWidth(15); // Total Poin
                $sheet->getColumnDimension('F')->setWidth(20); // Status

                // 4. ISI DATA & CONDITIONAL FORMATTING
                for ($r = 6; $r <= $lastRow; $r++) {
                    $idx = $r - 6;
                    $poin = $this->dataSiswa[$idx]['total_poin'];

                    // Zebra Striping
                    if ($r % 2 == 0) {
                        $sheet->getStyle('A'.$r.':F'.$r)->getFill()
                            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
                    }

                    // JIKA POIN TINGGI -> MERAH & BOLD
                    if ($poin >= 20) {
                        $sheet->getStyle('E'.$r.':F'.$r)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FFDC2626']], // Merah
                        ]);
                    }
                }

                // 5. BORDER KELILING
                $sheet->getStyle('A6:F'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']],
                        'outline' => ['borderStyle' => Border::BORDER_MEDIUM]
                    ],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
                ]);
                
                // Center Columns A, C, D, E, F
                $sheet->getStyle('A6:A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C6:F'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}