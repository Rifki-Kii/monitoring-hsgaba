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
            <th>NO</th>
            <th>NAMA SISWA</th>
            <th>KELAS</th> {{-- Kolom Kelas Tetap Ada --}}
            <th colspan="2">STATISTIK PELANGGARAN</th>
            <th>STATUS / TINDAKAN</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>JML KASUS</th>
            <th>TOTAL POIN</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswas as $index => $s)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $s['nama'] }}</td>
                
                {{-- PERBAIKAN DISINI: Menampilkan Kelas Asli Siswa --}}
                <td>{{ $s['kelas'] }}</td> 

                <td>{{ $s['jumlah_kasus'] }}</td>
                <td>{{ $s['total_poin'] }}</td>
                <td>
                    @if($s['total_poin'] >= 50) SP 3 (BERAT)
                    @elseif($s['total_poin'] >= 20) SP 1 (WASPADA)
                    @elseif($s['total_poin'] > 0) PEMBINAAN
                    @else BERSIH @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>