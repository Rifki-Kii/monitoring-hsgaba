<div class="min-h-screen bg-gray-50 p-6 font-sans">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Statistik Kedisiplinan Advanced</h1>
        <p class="text-slate-500 text-sm mt-1">Monitoring pelanggaran dan ketertiban siswa.</p>
    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-8 sticky top-0 z-20">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            
            {{-- Filter Tahun --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Tahun</label>
                <select wire:model.live="filterTahun" class="w-full border-slate-300 rounded-lg text-sm focus:ring-rose-500">
                    @for($y = date('Y'); $y >= date('Y')-2; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Bulan</label>
                <select wire:model.live="filterBulan" class="w-full border-slate-300 rounded-lg text-sm focus:ring-rose-500">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Filter Kelas</label>
                <select wire:model.live="filterKelas" class="w-full border-slate-300 rounded-lg text-sm focus:ring-rose-500">
                    <option value="">Semua Kelas</option>
                    @foreach($listKelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- 1. SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Total Kasus --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 border-l-4 border-l-rose-500">
            <p class="text-xs text-slate-400 font-bold uppercase">Total Kasus</p>
            <h3 class="text-3xl font-black text-rose-600 mt-1">{{ number_format($stats->total_kasus ?? 0) }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Pelanggaran tercatat</p>
        </div>
        
        {{-- Total Poin --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 border-l-4 border-l-orange-500">
            <p class="text-xs text-slate-400 font-bold uppercase">Total Poin</p>
            <h3 class="text-3xl font-black text-orange-600 mt-1">{{ number_format($stats->total_poin ?? 0) }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Akumulasi poin sanksi</p>
        </div>

        {{-- Siswa Terlibat --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 border-l-4 border-l-yellow-500">
            <p class="text-xs text-slate-400 font-bold uppercase">Siswa Terlibat</p>
            <h3 class="text-3xl font-black text-yellow-600 mt-1">{{ number_format($stats->siswa_terlibat ?? 0) }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Siswa unik melanggar</p>
        </div>

        {{-- Top Pelanggaran --}}
        <div class="bg-slate-800 p-5 rounded-xl shadow-sm border border-slate-700 text-white">
            <p class="text-xs text-slate-400 font-bold uppercase">Sering Terjadi</p>
            <h3 class="text-lg font-bold mt-1 leading-tight">{{ $topJenis->jenis_pelanggaran ?? '-' }}</h3>
            <p class="text-2xl font-black text-rose-400 mt-1">{{ $topJenis->jumlah ?? 0 }}x</p>
        </div>
    </div>

    {{-- 2. GRAFIK UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- TREN LINE CHART --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-2">
            <h4 class="font-bold text-gray-800 mb-4 text-sm">Tren Pelanggaran Bulanan</h4>
            <div id="chartTren" class="w-full h-80"></div>
        </div>

        {{-- DONUT CHART --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-1">
            <h4 class="font-bold text-gray-800 mb-4 text-sm">Proporsi Jenis Pelanggaran</h4>
            <div id="chartJenis" class="w-full flex justify-center py-4"></div>
        </div>
    </div>

    {{-- 3. BOTTOM SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- GRAFIK KELAS NAKAL (BAR) --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 lg:col-span-2">
            <h4 class="font-bold text-gray-800 mb-4 text-sm">Peringkat Kelas Berdasarkan Total Poin (Ketidaktertiban)</h4>
            <div id="chartKelas" class="w-full h-80"></div>
        </div>

        {{-- TABEL BLACKLIST --}}
        <div class="bg-white p-0 rounded-xl shadow-sm border border-slate-200 lg:col-span-1 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 bg-rose-600">
                <h4 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fas fa-skull"></i> Watchlist Siswa
                </h4>
                <p class="text-[10px] text-rose-100 mt-1">Top 10 siswa dengan poin tertinggi.</p>
            </div>
            <div class="flex-1 overflow-y-auto max-h-80">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-500 font-bold sticky top-0">
                        <tr>
                            <th class="px-4 py-2">Siswa</th>
                            <th class="px-4 py-2 text-center">Kasus</th>
                            <th class="px-4 py-2 text-right">Total Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($blacklist as $b)
                            <tr class="hover:bg-rose-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-700">{{ $b->nama }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $b->nama_kelas }} &bull; {{ $b->nis }}</div>
                                </td>
                                <td class="px-4 py-3 text-center text-slate-500">
                                    {{ $b->jumlah_kasus }}x
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-1 rounded-md font-bold text-white text-[10px]
                                        {{ $b->akumulasi_poin >= 20 ? 'bg-rose-600' : 'bg-orange-500' }}">
                                        {{ $b->akumulasi_poin }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-4 text-center text-slate-400">Tidak ada data pelanggaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SCRIPT GRAPH --}}
    <script wire:ignore>
        document.addEventListener('livewire:initialized', () => {
            
            // 1. CHART TREN (AREA)
            var optTren = {
                series: [{ name: "Kasus", data: @json($dataTren) }],
                chart: { height: 320, type: 'area', toolbar: {show: false} },
                colors: ['#f43f5e'], // Rose Color
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
                xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#chartTren"), optTren).render();


            // 2. CHART JENIS (DONUT)
            var optJenis = {
                series: @json($dataJenis),
                labels: @json($labelJenis),
                chart: { type: 'donut', height: 280 },
                colors: ['#ef4444', '#f97316', '#eab308', '#84cc16', '#3b82f6'],
                legend: { position: 'bottom', fontSize: '11px' },
                plotOptions: { pie: { donut: { size: '65%' } } }
            };
            new ApexCharts(document.querySelector("#chartJenis"), optJenis).render();


            // 3. CHART KELAS (BAR)
            var optKelas = {
                series: [{ name: 'Total Poin', data: @json($dataKelas) }],
                chart: { type: 'bar', height: 320, toolbar: {show: false} },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '45%', distributed: true } },
                xaxis: { categories: @json($labelKelas) },
                colors: ['#1e293b', '#334155', '#475569', '#64748b', '#94a3b8'], // Slate colors
                legend: { show: false },
                dataLabels: { enabled: true },
                tooltip: { y: { formatter: (val) => val + " Poin" } }
            };
            new ApexCharts(document.querySelector("#chartKelas"), optKelas).render();

        });
    </script>
</div>