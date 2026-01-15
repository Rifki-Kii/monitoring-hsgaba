<div class="min-h-screen bg-gray-50 p-6 font-sans">

    {{-- HEADER & TITLE --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Statistik Akademik Advanced</h1>
        <p class="text-slate-500 text-sm mt-1">Analisa mendalam performa akademik siswa.</p>
    </div>

    {{-- FILTER BAR (CONTROL PANEL) --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-8 sticky top-0 z-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            {{-- Filter Tahun --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Tahun</label>
                <select wire:model.live="filterTahun" class="w-full border-slate-300 rounded-lg text-sm focus:ring-blue-500">
                    @for($y = date('Y'); $y >= date('Y')-2; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- Filter Semester --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Semester</label>
                <select wire:model.live="filterSemester" class="w-full border-slate-300 rounded-lg text-sm focus:ring-blue-500">
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Filter Kelas</label>
                <select wire:model.live="filterKelas" class="w-full border-slate-300 rounded-lg text-sm focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($listKelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Mapel --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Filter Mapel</label>
                <select wire:model.live="filterMapel" class="w-full border-slate-300 rounded-lg text-sm focus:ring-blue-500">
                    <option value="">Semua Mapel</option>
                    @foreach($listMapel as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- 1. SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Total Siswa Terdata --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs text-slate-400 font-bold uppercase">Data Siswa</p>
            <h3 class="text-2xl font-black text-slate-700 mt-1">{{ $stats->total_siswa ?? 0 }}</h3>
        </div>
        
        {{-- Rata-rata --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs text-slate-400 font-bold uppercase">Rata-rata Nilai</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ number_format($stats->rata_rata ?? 0, 2) }}</h3>
        </div>

        {{-- Tertinggi --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs text-slate-400 font-bold uppercase">Nilai Tertinggi</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats->tertinggi ?? 0, 1) }}</h3>
        </div>

        {{-- Terendah --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs text-slate-400 font-bold uppercase">Nilai Terendah</p>
            <h3 class="text-2xl font-black text-rose-600 mt-1">{{ number_format($stats->terendah ?? 0, 1) }}</h3>
        </div>
    </div>

    {{-- 2. GRAFIK KOMPLEKS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- GRAFIK A: KOMPONEN NILAI (Radar/Bar) --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-1">
            <h4 class="font-bold text-gray-800 mb-2 text-sm">Analisa Komponen Nilai</h4>
            <p class="text-xs text-slate-400 mb-4">Perbandingan rata-rata UH, UTS, dan UAS.</p>
            <div id="chartKomponen" class="w-full h-64"></div>
        </div>

        {{-- GRAFIK B: DISTRIBUSI PREDIKAT (Histogram) --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-2">
            <h4 class="font-bold text-gray-800 mb-2 text-sm">Distribusi Sebaran Nilai (Histogram)</h4>
            <p class="text-xs text-slate-400 mb-4">Banyaknya siswa berdasarkan kelompok nilai.</p>
            <div id="chartDistribusi" class="w-full h-64"></div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- GRAFIK C: PERFORMA KELAS --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-2">
            <h4 class="font-bold text-gray-800 mb-4 text-sm">Perbandingan Rata-rata Kelas</h4>
            <div id="chartKelas" class="w-full h-80"></div>
        </div>

        {{-- TABEL: WATCHLIST (SISWA DI BAWAH KKM) --}}
        <div class="bg-white p-0 rounded-xl shadow-sm border border-slate-200 lg:col-span-1 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 bg-rose-50">
                <h4 class="font-bold text-rose-700 text-sm flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Perlu Perhatian
                </h4>
                <p class="text-[10px] text-rose-500 mt-1">Daftar nilai siswa di bawah KKM Mapel.</p>
            </div>
            <div class="flex-1 overflow-y-auto max-h-80">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-500 font-bold sticky top-0">
                        <tr>
                            <th class="px-4 py-2">Siswa</th>
                            <th class="px-4 py-2">Mapel</th>
                            <th class="px-4 py-2 text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($siswaRawan as $rawan)
                            <tr>
                                <td class="px-4 py-2 font-medium text-slate-700">
                                    {{ Str::limit($rawan->nama, 15) }}
                                    <div class="text-[10px] text-slate-400">{{ $rawan->nama_kelas }}</div>
                                </td>
                                <td class="px-4 py-2 text-slate-500">{{ $rawan->nama_mapel }}</td>
                                <td class="px-4 py-2 text-right font-bold text-rose-600">
                                    {{ number_format($rawan->nilai_akhir, 1) }}
                                    <span class="block text-[9px] text-slate-400">KKM: {{ $rawan->kkm }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-4 text-center text-slate-400">Aman, tidak ada nilai di bawah KKM.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SCRIPT GRAPH (Advanced) --}}
    <script wire:ignore>
        document.addEventListener('livewire:initialized', () => {
            
            // 1. CHART KOMPONEN (RADAR / BAR)
            var optKomponen = {
                series: [{
                    name: 'Rata-rata',
                    data: [
                        {{ number_format($komponen->avg_uh ?? 0, 1) }}, 
                        {{ number_format($komponen->avg_uts ?? 0, 1) }}, 
                        {{ number_format($komponen->avg_uas ?? 0, 1) }}
                    ]
                }],
                chart: { height: 250, type: 'bar', toolbar: {show: false} },
                plotOptions: { bar: { distributed: true, borderRadius: 4, columnWidth: '40%' } },
                colors: ['#3b82f6', '#8b5cf6', '#ec4899'],
                xaxis: { categories: ['Rata UH', 'UTS', 'UAS'] },
                legend: { show: false },
                dataLabels: { enabled: true, formatter: (val) => val }
            };
            new ApexCharts(document.querySelector("#chartKomponen"), optKomponen).render();


            // 2. CHART DISTRIBUSI (Histogram Style)
            var optDistribusi = {
                series: [{ name: 'Jumlah Siswa', data: @json($dataDistribusi) }],
                chart: { height: 250, type: 'area', toolbar: {show: false} },
                colors: ['#10b981'],
                fill: { type: 'gradient', gradient: { opacityFrom: 0.6, opacityTo: 0.1 } },
                dataLabels: { enabled: true },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: { categories: @json($labelDistribusi) },
                yaxis: { show: false },
                grid: { show: false }
            };
            new ApexCharts(document.querySelector("#chartDistribusi"), optDistribusi).render();


            // 3. CHART KELAS
            var optKelas = {
                series: [{ name: 'Rata-rata Nilai', data: @json($dataKelas) }],
                chart: { height: 320, type: 'bar', toolbar: {show: false} },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                xaxis: { categories: @json($labelKelas) },
                colors: ['#6366f1']
            };
            new ApexCharts(document.querySelector("#chartKelas"), optKelas).render();

        });
    </script>
</div>