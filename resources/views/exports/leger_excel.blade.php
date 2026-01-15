<table>
    {{-- BARIS 1 & 2: JUDUL --}}
    <tr>
        <td>LEGER NILAI AKADEMIK - KELAS {{ strtoupper($namaKelas) }}</td>
    </tr>
    <tr>
        <td>HOMESCHOOLING GROUP ABA - TAHUN AJARAN {{ $tahun }}</td>
    </tr>
    
    <tr></tr> {{-- Spasi --}}

    {{-- HEADER TABEL --}}
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">NAMA SISWA</th>
            
            @if(count($mapels) > 0)
                <th colspan="{{ count($mapels) }}">MATA PELAJARAN</th>
            @endif

            <th rowspan="2">RATA-RATA</th>
            <th rowspan="2">TOTAL SKOR</th>
        </tr>
        <tr>
            @foreach($mapels as $m)
                {{-- Kita tampilkan KKM di header agar informatif --}}
                <th>{{ strtoupper($m->nama_mapel) }} (KKM: {{ $m->kkm }})</th>
            @endforeach
        </tr>
    </thead>

    {{-- DATA --}}
    <tbody>
        @foreach($legerData as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['siswa']->nama }}</td>

                @foreach($mapels as $m)
                    @php 
                        $nilai = $row['nilai_per_mapel'][$m->id] ?? 0;
                    @endphp
                    <td>
                        {{ $nilai > 0 ? $nilai : '' }}
                    </td>
                @endforeach

                <td>{{ number_format($row['rata_rata_total'], 1) }}</td>
                <td>{{ $row['total_skor'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>