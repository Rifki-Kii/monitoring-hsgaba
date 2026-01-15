<table>
    {{-- BARIS 1-4: KOP LAPORAN --}}
    <tr>
        <td colspan="10" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN HASIL MONITORING AKADEMIK</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">KELAS</td>
        <td colspan="8">: {{ $nama_kelas }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">MATA PELAJARAN</td>
        <td colspan="8">: {{ $nama_mapel }} (KKM: {{ $kkm }})</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">PERIODE</td>
        <td colspan="8">: {{ $tahun_ajaran }} - Semester {{ ucfirst($semester) }}</td>
    </tr>

    {{-- BARIS 5: HEADER TABEL --}}
    <tr>
        {{-- IDENTITAS (Abu Tua) --}}
        <th style="background-color: #2d3748; color: #ffffff; text-align: center; vertical-align: middle;">ID_SISWA<br>(SYSTEM)</th>
        <th style="background-color: #2d3748; color: #ffffff; text-align: center; vertical-align: middle;">NAMA SISWA</th>

        {{-- KOMPONEN NILAI INPUT (Biru) --}}
        <th style="background-color: #3182ce; color: #ffffff; text-align: center; vertical-align: middle;">RATA UH<br>(A)</th>
        <th style="background-color: #3182ce; color: #ffffff; text-align: center; vertical-align: middle;">TUGAS<br>(B)</th>
        <th style="background-color: #3182ce; color: #ffffff; text-align: center; vertical-align: middle;">PTS<br>(C)</th>
        <th style="background-color: #3182ce; color: #ffffff; text-align: center; vertical-align: middle;">PAS<br>(D)</th>

        {{-- HASIL KOGNITIF (Abu Muda) --}}
        <th style="background-color: #a0aec0; color: #000000; text-align: center; vertical-align: middle;">N. KOGNITIF<br>(N)</th>

        {{-- PRAKTEK (Hijau) --}}
        <th style="background-color: #38a169; color: #ffffff; text-align: center; vertical-align: middle;">N. PRAKTEK</th>

        {{-- FINAL RAPORT (Kuning Emas) --}}
        <th style="background-color: #d69e2e; color: #ffffff; text-align: center; vertical-align: middle;">NILAI RAPORT</th>

        {{-- MUTU (Hitam) --}}
        <th style="background-color: #000000; color: #ffffff; text-align: center; vertical-align: middle;">MUTU</th>
        
        {{-- STATUS (Putih) --}}
        <th style="background-color: #ffffff; color: #000000; text-align: center; vertical-align: middle;">STATUS</th>
    </tr>

    {{-- DATA SISWA --}}
    @foreach($data as $row)
        @php
            $kognitif = $row['nilai']->nilai_akhir ?? 0; // DB: nilai_akhir = Kognitif
            $raport = $row['nilai']->nilai_raport ?? 0;   // DB: nilai_raport = Raport
            $predikat = $row['nilai']->predikat ?? '-';
            $status = $row['nilai']->status ?? '-';
        @endphp
        <tr>
            <td style="text-align: center;">{{ $row['siswa']->id }}</td>
            <td>{{ $row['siswa']->nama }}</td>
            
            {{-- Inputan --}}
            <td style="text-align: center;">{{ $row['nilai']->rata_uh ?? 0 }}</td>
            <td style="text-align: center;">{{ $row['nilai']->tugas ?? 0 }}</td>
            <td style="text-align: center;">{{ $row['nilai']->uts ?? 0 }}</td>
            <td style="text-align: center;">{{ $row['nilai']->uas ?? 0 }}</td>

            {{-- Hasil Kognitif --}}
            <td style="text-align: center; font-weight: bold; background-color: #edf2f7;">{{ $kognitif }}</td>

            {{-- Input Praktek --}}
            <td style="text-align: center;">{{ $row['nilai']->keterampilan ?? 0 }}</td>

            {{-- Hasil Raport (FINAL) --}}
            <td style="text-align: center; font-weight: bold; background-color: #fefcbf;">{{ $raport }}</td>

            {{-- Predikat --}}
            <td style="text-align: center; font-weight: bold;">{{ $predikat }}</td>
            
            {{-- Status (Lulus/Remedial) --}}
            <td style="text-align: center; color: {{ $status == 'Remedial' ? '#e53e3e' : '#2f855a' }}; font-weight: bold;">
                {{ $status }}
            </td>
        </tr>
    @endforeach
</table>