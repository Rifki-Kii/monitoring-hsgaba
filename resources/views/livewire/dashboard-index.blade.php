<div class="min-h-screen bg-slate-50 p-6 font-sans text-slate-800">
    
    {{-- HEADER & GLOBAL SEARCH --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Executive Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Monitoring Terintegrasi Homeschooling Group ABA</p>
        </div>
        
        {{-- Global Search Bar --}}
        <div class="flex-1 max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="globalSearch" type="text" 
                class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm transition" 
                placeholder="Cari Siswa (NIS/Nama) untuk pantauan cepat...">
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white border border-slate-200 text-slate-500 px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
                <i class="far fa-calendar-alt mr-2 text-blue-600"></i> {{ date('d M Y') }}
            </div>
        </div>
    </div>

    {{-- STATS GRID (4 Pilar Utama) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Siswa --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $totalSiswa }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">+ Aktif</span>
                <span class="text-xs text-slate-400">Tahun Ajaran Ini</span>
            </div>
        </div>

        {{-- Rata-rata Nilai --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Indeks Prestasi</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $rataRataNilai }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $rataRataNilai }}%"></div>
            </div>
        </div>

        {{-- Pelanggaran --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggaran (Bln Ini)</p>
                    <h3 class="text-3xl font-black text-rose-600 mt-2">{{ $totalPelanggaranBulanIni }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 transition">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-500">
                <span class="font-bold text-rose-600">{{ $siswaBermasalahCount }} Siswa</span> perlu pembinaan
            </div>
        </div>

        {{-- Siswa Remedial --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Remedial</p>
                    <h3 class="text-3xl font-black text-orange-500 mt-2">{{ $siswaRemedialCount }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition">
                    <i class="fas fa-book-reader text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-500">
                Data berdasarkan nilai < KKM
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT LAYOUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- KOLOM KIRI (8/12): TABEL UTAMA & ANALISIS MAPEL --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- 1. TABEL PRIORITY MONITOR --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Pantauan Prioritas</h3>
                        <p class="text-xs text-slate-500">Siswa dengan nilai rendah atau poin disiplin tinggi</p>
                    </div>
                    @if($globalSearch)
                        <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold">Search Result: Active</span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-6 py-3">Siswa</th>
                                <th class="px-6 py-3">Kelas</th>
                                <th class="px-6 py-3 text-center">Akademik (N)</th>
                                <th class="px-6 py-3 text-center">Disiplin</th>
                                <th class="px-6 py-3 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($studentsAtRisk as $student)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $student['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $student['kelas'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-bold {{ $student['nilai'] < 75 ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $student['nilai'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($student['poin'] > 20)
                                        <div class="flex items-center justify-center gap-1 text-rose-600 font-bold">
                                            <i class="fas fa-arrow-up"></i> {{ $student['poin'] }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">{{ $student['poin'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- TOMBOL TINDAKAN (LINK KE HALAMAN POIN) --}}
                                    <a href="{{ route('poin.index') }}" class="text-xs bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-lg font-bold shadow-sm transition flex items-center justify-center gap-1 mx-auto w-max">
                                        <i class="fas fa-gavel"></i> Tindak
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400">
                                    Tidak ada data siswa yang perlu perhatian khusus saat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. ANALISIS MAPEL TERSULIT (Grid 2 Kolom) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Card Mapel --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-brain text-purple-500"></i> Mapel Perlu Evaluasi
                    </h3>
                    <div class="space-y-4">
                        @foreach($hardestSubjects as $mapel)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700">{{ $mapel['nama'] }}</span>
                                <span class="font-bold text-slate-900">{{ $mapel['avg'] }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $mapel['avg'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Card Distribusi Grade --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-500"></i> Sebaran Nilai (Grade)
                    </h3>
                    <div class="space-y-3">
                        {{-- Grade A --}}
                        <div class="flex items-center gap-3">
                            <span class="w-8 font-bold text-emerald-600">A</span>
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                @php $pctA = ($totalSiswa > 0) ? ($gradeDistribution['A']/$totalSiswa)*100 : 0; @endphp
                                <div class="h-full bg-emerald-500" style="width: {{ $pctA }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-10 text-right">{{ $gradeDistribution['A'] }} Siswa</span>
                        </div>
                        {{-- Grade B --}}
                        <div class="flex items-center gap-3">
                            <span class="w-8 font-bold text-blue-600">B</span>
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                @php $pctB = ($totalSiswa > 0) ? ($gradeDistribution['B']/$totalSiswa)*100 : 0; @endphp
                                <div class="h-full bg-blue-500" style="width: {{ $pctB }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-10 text-right">{{ $gradeDistribution['B'] }} Siswa</span>
                        </div>
                        {{-- Grade C --}}
                        <div class="flex items-center gap-3">
                            <span class="w-8 font-bold text-yellow-600">C</span>
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                @php $pctC = ($totalSiswa > 0) ? ($gradeDistribution['C']/$totalSiswa)*100 : 0; @endphp
                                <div class="h-full bg-yellow-500" style="width: {{ $pctC }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-10 text-right">{{ $gradeDistribution['C'] }} Siswa</span>
                        </div>
                        {{-- Grade D --}}
                        <div class="flex items-center gap-3">
                            <span class="w-8 font-bold text-rose-600">D</span>
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                @php $pctD = ($totalSiswa > 0) ? ($gradeDistribution['D']/$totalSiswa)*100 : 0; @endphp
                                <div class="h-full bg-rose-500" style="width: {{ $pctD }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-10 text-right">{{ $gradeDistribution['D'] }} Siswa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (4/12): SIDEBAR ACTIONS & FEED --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Quick Actions Card --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="font-bold text-lg mb-4">Quick Access</h3>
                <div class="grid grid-cols-2 gap-3">
                    {{-- TOMBOL INPUT NILAI --}}
                    <a href="{{ route('nilai.index') }}" class="bg-white/10 hover:bg-white/20 transition p-3 rounded-xl flex flex-col items-center justify-center text-center gap-2 border border-white/5">
                        <i class="fas fa-edit text-2xl text-blue-300"></i>
                        <span class="text-xs font-medium">Input Nilai</span>
                    </a>
                    
                    {{-- TOMBOL DATA SISWA --}}
                    {{-- Sesuaikan route ini jika pakai 'master/siswa' atau 'siswa.index' --}}
                    <a href="{{ route('siswa.index') }}" class="bg-white/10 hover:bg-white/20 transition p-3 rounded-xl flex flex-col items-center justify-center text-center gap-2 border border-white/5">
                        <i class="fas fa-user-plus text-2xl text-emerald-300"></i>
                        <span class="text-xs font-medium">Data Siswa</span>
                    </a>
                    
                    {{-- TOMBOL CATAT PELANGGARAN --}}
                    <a href="{{ route('poin.index') }}" class="bg-rose-500/20 hover:bg-rose-500/30 transition p-3 rounded-xl flex flex-col items-center justify-center text-center gap-2 border border-rose-500/30">
                        <i class="fas fa-exclamation-triangle text-2xl text-rose-300"></i>
                        <span class="text-xs font-medium">Catat Poin</span>
                    </a>
                    
                    {{-- TOMBOL LAPORAN --}}
                    <a href="{{ route('laporan.index') }}" class="bg-white/10 hover:bg-white/20 transition p-3 rounded-xl flex flex-col items-center justify-center text-center gap-2 border border-white/5">
                        <i class="fas fa-file-export text-2xl text-yellow-300"></i>
                        <span class="text-xs font-medium">Laporan</span>
                    </a>
                </div>
            </div>

            {{-- Violation Feed (Timeline) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-800">Pelanggaran Terbaru</h3>
                    <a href="{{ route('poin.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
                </div>
                
                <div class="relative pl-4 border-l border-slate-200 space-y-6">
                    @foreach($recentViolations as $violation)
                    <div class="relative">
                        {{-- Dot Indicator --}}
                        <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-rose-500 border-2 border-white shadow-sm"></div>
                        
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">{{ $violation['time'] }} &bull; {{ $violation['date'] }}</p>
                            <p class="text-sm font-bold text-slate-800">{{ $violation['student'] }} <span class="font-normal text-slate-500">({{ $violation['kelas'] }})</span></p>
                            <p class="text-xs font-medium text-rose-600 bg-rose-50 inline-block px-2 py-0.5 rounded mt-1 border border-rose-100">
                                {{ $violation['type'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>