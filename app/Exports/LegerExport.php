<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LegerExport implements FromView, WithEvents
{
    protected $legerData;
    protected $mapels;
    protected $namaKelas;
    protected $tahun;

    public function __construct($legerData, $mapels, $namaKelas)
    {
        $this->legerData = $legerData;
        $this->mapels = $mapels;
        $this->namaKelas = $namaKelas;
        $this->tahun = date('Y');
    }

    public function view(): View
    {
        return view('exports.leger_excel', [
            'legerData' => $this->legerData,
            'mapels' => $this->mapels,
            'namaKelas' => $this->namaKelas,
            'tahun' => $this->tahun
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $mapelCount = count($this->mapels);
                $rowCount = count($this->legerData);
                
                // KOORDINAT AKHIR
                // Kolom dimulai dari: No(A), Nama(B), Mapel(C...?), Rata2, Total
                $colStartMapel = 3; // Kolom C
                $colEndMapel = $colStartMapel + $mapelCount - 1;
                
                $colRata = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colEndMapel + 1);
                $colTotal = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colEndMapel + 2);
                $lastColumnLetter = $colTotal;
                
                $rowStartData = 6;
                $rowEndData = $rowStartData + $rowCount - 1;

                // ==========================================================
                // 1. STYLING JUDUL (Header Laporan)
                // ==========================================================
                $sheet->mergeCells('A1:' . $lastColumnLetter . '1');
                $sheet->mergeCells('A2:' . $lastColumnLetter . '2');
                
                $styleJudul = [
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'name' => 'Calibri', // Font standar Excel modern
                        'color' => ['argb' => 'FF1E293B'] // Slate 800
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];
                $sheet->getStyle('A1:A2')->applyFromArray($styleJudul);


                // ==========================================================
                // 2. STYLING HEADER TABEL (Baris 4 & 5) - NAVY BLUE THEME
                // ==========================================================
                $headerRange = 'A4:' . $lastColumnLetter . '5';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'], // Teks Putih
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1E3A8A'], // Blue Navy (Resmi)
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true, // Agar nama mapel panjang turun ke bawah
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFFFFFFF'], // Garis pemisah putih
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM, // Garis luar tebal
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // ==========================================================
                // 3. STYLING ISI DATA (LOOPING BARIS)
                // ==========================================================
                
                // Atur Lebar Kolom Dulu
                $sheet->getColumnDimension('A')->setWidth(6);  // No
                $sheet->getColumnDimension('B')->setWidth(35); // Nama Siswa
                for ($i = $colStartMapel; $i <= $colEndMapel + 2; $i++) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                    $sheet->getColumnDimension($colLetter)->setWidth(10); // Lebar kolom nilai seragam
                }

                // Loop setiap baris data
                for ($r = $rowStartData; $r <= $rowEndData; $r++) {
                    
                    // A. Zebra Striping (Baris Genap dikasih warna abu tipis)
                    if ($r % 2 == 0) {
                        $sheet->getStyle('A' . $r . ':' . $lastColumnLetter . $r)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFF1F5F9'] // Slate 100 (Very light gray)
                            ]
                        ]);
                    }

                    // B. Conditional Formatting (Nilai Merah jika < KKM)
                    // Kita harus loop kolom mapel satu per satu
                    $dataIndex = $r - $rowStartData; // Index array legerData
                    $rowData = $this->legerData[$dataIndex];
                    
                    $colIndex = 3; // Mulai dari Kolom C
                    foreach ($this->mapels as $m) {
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        $cellCoordinate = $colLetter . $r;
                        
                        $nilai = $rowData['nilai_per_mapel'][$m->id] ?? 0;
                        
                        // Jika Nilai < KKM, warnai Merah & Bold
                        if ($nilai < $m->kkm && $nilai > 0) {
                            $sheet->getStyle($cellCoordinate)->applyFromArray([
                                'font' => [
                                    'color' => ['argb' => 'FFDC2626'], // Merah
                                    'bold' => true
                                ]
                            ]);
                        }
                        
                        $colIndex++;
                    }
                    
                    // C. Kolom Rata-rata & Total (Bold & Background khusus)
                    $rangeSummary = $colRata . $r . ':' . $colTotal . $r;
                    $sheet->getStyle($rangeSummary)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFEOE7FF'] // Indigo sangat muda
                        ]
                    ]);
                }

                // ==========================================================
                // 4. FINISHING TOUCHES (Borders Seluruh Tabel)
                // ==========================================================
                $tableRange = 'A6:' . $lastColumnLetter . $rowEndData;
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'vertical' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCBD5E1'], // Border vertikal abu-abu
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM, // Border luar tabel tebal
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);

                // Center Alignment untuk semua kolom NILAI (C sampai akhir)
                $firstMapelCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3);
                $sheet->getStyle($firstMapelCol . '6:' . $lastColumnLetter . $rowEndData)
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Left Alignment untuk Nama Siswa (Agar rapi)
                $sheet->getStyle('B6:B' . $rowEndData)
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setIndent(1);

                // Center Alignment untuk Nomor
                $sheet->getStyle('A6:A' . $rowEndData)
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}