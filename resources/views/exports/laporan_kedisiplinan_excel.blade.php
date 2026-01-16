<table>
    <tr>
        <td colspan="6" style="font-weight: bold; font-size: 14px; text-align: center;">
            LAPORAN KEDISIPLINAN SISWA - {{ strtoupper($namaKelas) }}
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center;">PERIODE: {{ strtoupper($periode) }}</td>
    </tr>
    <tr></tr>

    <thead>
        <tr>
            <th style="text-align:center; font-weight:bold; border:1px solid #000;">NO</th>
            <th style="text-align:center; font-weight:bold; border:1px solid #000;">NAMA SISWA</th>
            <th style="text-align:center; font-weight:bold; border:1px solid #000;">KELAS</th>
            <th colspan="2" style="text-align:center; font-weight:bold; border:1px solid #000;">STATISTIK PELANGGARAN</th>
            <th style="text-align:center; font-weight:bold; border:1px solid #000;">STATUS / TINDAKAN</th>
        </tr>
        <tr>
            <th style="border:1px solid #000;"></th>
            <th style="border:1px solid #000;"></th>
            <th style="border:1px solid #000;"></th>
            <th style="text-align:center; font-weight:bold; border:1px solid #000;">JML KASUS</th>
            <th style="text-align:center; font-weight:bold; border:1px solid #000;">TOTAL POIN</th>
            <th style="border:1px solid #000;"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswas as $index => $s)
            <tr>
                <td style="text-align:center; border:1px solid #000;">{{ $index + 1 }}</td>
                <td style="border:1px solid #000;">{{ $s['nama'] }}</td>
                
                {{-- PERBAIKAN: Gunakan 'nama_kelas' sesuai query Controller --}}
                <td style="text-align:center; border:1px solid #000;">{{ $s['nama_kelas'] }}</td> 

                <td style="text-align:center; border:1px solid #000;">{{ $s['jumlah_kasus'] }}</td>
                <td style="text-align:center; border:1px solid #000; font-weight:bold;">{{ $s['total_poin'] }}</td>
                
                {{-- PERBAIKAN: Logika Status mengambil dari Database (Manual), lalu Fallback ke Otomatis --}}
                <td style="text-align:center; border:1px solid #000;">
                    @if(!empty($s['status_sanksi']))
                        {{-- Jika guru sudah set sanksi (misal: SP 1, SKORSING) --}}
                        {{ strtoupper($s['status_sanksi']) }}
                    @else
                        {{-- Jika belum diset manual, tampilkan status saran sistem --}}
                        @if($s['total_poin'] >= 20)
                            BELUM DITINDAK (BERAT)
                        @elseif($s['total_poin'] > 0)
                            BELUM DITINDAK
                        @else
                            TIDAK ADA 
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>