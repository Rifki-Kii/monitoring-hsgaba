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
    protected $periode;

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
                $lastRow = $rowCount + 6; // Header sekarang 2 baris (4 & 5), data mulai baris 7? Cek view. 
                // Di view: Baris 1-2 Judul, Baris 3 Kosong, Baris 4-5 Header Tabel. Data mulai baris 6.
                // Jadi lastRow = 5 + rowCount.
                $lastRow = $rowCount + 5;

                // 1. STYLING JUDUL
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E293B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // 2. STYLING HEADER (Baris 4 & 5)
                $sheet->getStyle('A4:F5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF9F1239']], // Rose 800
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFFFFFF']]]
                ]);

                // 3. ATUR LEBAR KOLOM
                $sheet->getColumnDimension('A')->setWidth(6);  // No
                $sheet->getColumnDimension('B')->setWidth(35); // Nama
                $sheet->getColumnDimension('C')->setWidth(15); // Kelas
                $sheet->getColumnDimension('D')->setWidth(12); // Kasus
                $sheet->getColumnDimension('E')->setWidth(12); // Poin
                $sheet->getColumnDimension('F')->setWidth(25); // Status

                // 4. LOOPING DATA UNTUK WARNA BARIS
                for ($r = 6; $r <= $lastRow; $r++) {
                    $idx = $r - 6;
                    $status = strtoupper($this->dataSiswa[$idx]['status_sanksi'] ?? '');
                    $poin = $this->dataSiswa[$idx]['total_poin'];

                    // A. Zebra Striping (Baris Genap)
                    if ($r % 2 == 0) {
                        $sheet->getStyle('A'.$r.':F'.$r)->getFill()
                            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
                    }

                    // B. Highlight Status Sanksi (Kolom F)
                    $cell = 'F'.$r;
                    
                    if (str_contains($status, 'SP') || str_contains($status, 'SKORSING') || str_contains($status, 'TINDAKAN')) {
                        // MERAH (Berat)
                        $sheet->getStyle($cell)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FFDC2626']], // Teks Merah
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFEF2F2']] // Bg Merah Muda
                        ]);
                    } 
                    elseif (str_contains($status, 'PIKET') || str_contains($status, 'PEMBINAAN')) {
                        // ORANYE (Sedang)
                        $sheet->getStyle($cell)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FFEA580C']], 
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF7ED']]
                        ]);
                    }
                    elseif (str_contains($status, 'TEGURAN')) {
                        // BIRU (Ringan)
                        $sheet->getStyle($cell)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FF2563EB']], 
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFF6FF']]
                        ]);
                    }
                    elseif ($poin > 0 && empty($status)) {
                        // Belum Ditindak (Abu-abu Miring)
                        $sheet->getStyle($cell)->applyFromArray([
                            'font' => ['italic' => true, 'color' => ['argb' => 'FF64748B']],
                        ]);
                    }
                }

                // 5. BORDER TABEL
                $sheet->getStyle('A4:F'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']],
                        'outline' => ['borderStyle' => Border::BORDER_MEDIUM]
                    ],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
                ]);

                // Rata Tengah Kolom A, C, D, E, F
                $sheet->getStyle('A6:A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C6:F'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}