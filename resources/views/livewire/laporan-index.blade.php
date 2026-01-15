<div class="min-h-screen bg-slate-50 p-6 font-sans">

    {{-- HEADER (No Print) --}}
    <div class="mb-8 no-print flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pusat Laporan</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">
                Manajemen Cetak Leger, Rapor, dan Surat Peringatan Siswa
            </p>
        </div>
        
        {{-- TAB NAVIGATION --}}
        <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-200 inline-flex">
            <button wire:click="setTab('akademik')" 
                class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2 {{ $activeTab == 'akademik' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-graduation-cap {{ $activeTab == 'akademik' ? 'text-indigo-200' : 'text-slate-400' }}"></i> 
                Nilai Akademik
            </button>
            <div class="w-px bg-slate-200 my-2"></div>
            <button wire:click="setTab('kedisiplinan')" 
                class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2 {{ $activeTab == 'kedisiplinan' ? 'bg-rose-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-rose-600' }}">
                <i class="fas fa-gavel {{ $activeTab == 'kedisiplinan' ? 'text-rose-200' : 'text-slate-400' }}"></i> 
                Kedisiplinan
            </button>
        </div>
    </div>

    {{-- CONTROL PANEL (FILTER) --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-8 no-print relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-full -mr-8 -mt-8 z-0"></div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            {{-- PILIH KELAS --}}
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Kelas</label>
                <div class="relative">
                    <select wire:model.live="filterKelas" wire:key="filter-kelas-{{ $activeTab }}"
                        class="w-full pl-10 bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-bold py-2.5 transition-shadow">
                        @if($activeTab == 'kedisiplinan')
                            <option value="">Semua Siswa (Ranking Poin)</option>
                        @endif
                        @foreach($listKelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chalkboard absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            {{-- FILTER DINAMIS --}}
            <div class="md:col-span-2 flex flex-col md:flex-row gap-4">
                @if($activeTab == 'akademik')
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Opsi Tampilan</label>
                        <button wire:click="$toggle('showMapelFilter')" 
                            class="w-full px-4 py-2.5 bg-slate-50 text-slate-600 rounded-lg text-sm font-bold border border-slate-300 hover:bg-white hover:border-indigo-300 hover:text-indigo-600 transition flex items-center justify-between group">
                            <span><i class="fas fa-filter mr-2 text-slate-400 group-hover:text-indigo-500"></i> Filter Mata Pelajaran</span>
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px]">{{ count($selectedMapelIds) }} Aktif</span>
                        </button>
                    </div>
                @endif

                @if($activeTab == 'kedisiplinan')
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Periode Tanggal</label>
                        <div class="flex items-center gap-2">
                            <input type="date" wire:model.live="startDate" class="w-full bg-slate-50 border border-slate-300 text-sm rounded-lg p-2.5 font-medium text-slate-600">
                            <span class="text-slate-400">-</span>
                            <input type="date" wire:model.live="endDate" class="w-full bg-slate-50 border border-slate-300 text-sm rounded-lg p-2.5 font-medium text-slate-600">
                        </div>
                    </div>
                @endif
            </div>

            {{-- TOMBOL DOWNLOAD --}}
            <div class="md:col-span-1 text-right">
                <button wire:click="downloadExcel" wire:loading.attr="disabled" 
                    class="w-full md:w-auto px-6 py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-slate-200 text-white flex items-center justify-center gap-2 transition-transform active:scale-95
                    {{ $activeTab == 'akademik' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700' }}">
                    <span wire:loading.remove wire:target="downloadExcel"><i class="fas fa-file-excel text-lg"></i> Export Excel</span>
                    <span wire:loading wire:target="downloadExcel"><i class="fas fa-circle-notch fa-spin"></i> Memproses...</span>
                </button>
            </div>
        </div>

        {{-- DROPDOWN CHECKBOX MAPEL --}}
        @if($activeTab == 'akademik' && $showMapelFilter)
            <div class="mt-6 pt-6 border-t border-slate-100 relative z-10 animate-fade-in-down">
                <h4 class="text-xs font-bold text-indigo-900 mb-3">Pilih Mata Pelajaran:</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($allMapels as $m)
                        <label class="flex items-center gap-2 cursor-pointer p-2 bg-slate-50 hover:bg-indigo-50 border border-transparent hover:border-indigo-200 rounded transition select-none">
                            <input type="checkbox" value="{{ $m->id }}" wire:model.live="selectedMapelIds" class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 w-4 h-4">
                            <span class="text-xs font-bold text-slate-600 truncate" title="{{ $m->nama_mapel }}">{{ $m->nama_mapel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- ================================================= --}}
    {{-- TABEL AKADEMIK --}}
    {{-- ================================================= --}}
    @if($activeTab == 'akademik')
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden no-print animate-fade-in">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-800 text-slate-300 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-4 w-12 text-center border-r border-slate-700">Rank</th>
                            <th class="px-4 py-4 min-w-[220px] border-r border-slate-700">Nama Siswa</th>
                            @foreach($mapels as $m)
                                <th class="px-2 py-4 w-16 text-center border-r border-slate-700 bg-slate-800/95">
                                    <div class="truncate w-14 mx-auto" title="{{ $m->nama_mapel }}">{{ $m->nama_mapel }}</div>
                                    <div class="text-[9px] text-slate-500 mt-0.5 normal-case font-normal">KKM: {{ $m->kkm }}</div>
                                </th>
                            @endforeach
                            <th class="px-4 py-4 w-20 text-center bg-indigo-900 text-white border-r border-indigo-800">Rata2</th>
                            <th class="px-4 py-4 w-28 text-center bg-slate-800">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($legerData as $index => $row)
                            <tr class="hover:bg-indigo-50/40 transition-colors duration-150">
                                <td class="px-4 py-3 text-center border-r border-slate-100">
                                    @if($index < 3)
                                        <div class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold text-xs mx-auto">{{ $index + 1 }}</div>
                                    @else
                                        <span class="text-slate-500 font-semibold">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-r border-slate-100"><span class="font-bold text-slate-700">{{ $row['siswa']->nama }}</span></td>
                                @foreach($mapels as $m)
                                    @php $val = $row['nilai_per_mapel'][$m->id] ?? 0; @endphp
                                    <td class="px-2 py-3 text-center border-r border-slate-100">
                                        @if($val > 0)
                                            <span class="font-medium {{ $val < $m->kkm ? 'text-rose-600 font-bold bg-rose-50 px-1.5 py-0.5 rounded' : 'text-slate-600' }}">{{ $val }}</span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center bg-indigo-50/30 border-r border-slate-200"><span class="font-black text-indigo-700">{{ number_format($row['rata_rata_total'], 1) }}</span></td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="openModal('rapor', {{ $row['siswa']->id }})" class="text-xs font-bold text-slate-600 hover:text-indigo-600 bg-white border border-slate-300 hover:border-indigo-400 px-3 py-1.5 rounded-md shadow-sm hover:shadow transition flex items-center justify-center gap-1 mx-auto"><i class="fas fa-print"></i> Rapor</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ count($mapels) + 4 }}" class="py-12 text-center"><div class="flex flex-col items-center"><div class="bg-slate-100 p-3 rounded-full mb-2"><i class="fas fa-inbox text-slate-400 text-2xl"></i></div><span class="text-slate-500 font-medium">Belum ada data nilai di kelas ini.</span></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- TABEL KEDISIPLINAN (UPDATE LOGIKA SANKSI 20 POIN) --}}
    {{-- ================================================= --}}
    @if($activeTab == 'kedisiplinan')
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-visible no-print animate-fade-in">
            <div class="overflow-x-visible">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-800 text-slate-300 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-3 w-1/4">Siswa</th>
                            <th class="px-6 py-3 w-1/3">PELANGGARAN</th>
                            <th class="px-6 py-3 text-center whitespace-nowrap">Akumulasi Poin</th>
                            <th class="px-6 py-3 text-center whitespace-nowrap">Status Sanksi</th>
                            <th class="px-6 py-3 text-center whitespace-nowrap">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dataLaporan as $row) 
                            <tr class="hover:bg-rose-50/40 transition-colors duration-150">
                                {{-- Nama Siswa --}}
                                <td class="px-6 py-3 whitespace-nowrap" title="NIS: {{ $row['nis'] }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 border border-slate-200">{{ substr($row['nama'], 0, 1) }}</div>
                                        <div class="font-bold text-slate-700 text-sm">{{ $row['nama'] }}</div>
                                    </div>
                                </td>
                                
                                {{-- Pelanggaran (Dropdown) --}}
                                <td class="px-6 py-3">
                                    @if($row['jumlah_kasus'] == 0)
                                        <span class="text-slate-300 text-xs italic">Tidak Ada</span>
                                    @elseif($row['jumlah_kasus'] == 1)
                                        @php $p = $row['list_pelanggaran'][0]; @endphp
                                        <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700">
                                            <span class="text-slate-400 text-[10px]">{{ $p['tanggal'] }}</span>
                                            <span class="font-bold text-rose-600">{{ $p['jenis'] }}</span>
                                        </div>
                                    @else
                                        <div x-data="{ open: false }" class="relative inline-block">
                                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 px-3 py-1 bg-white border border-slate-300 hover:border-rose-400 hover:text-rose-600 rounded-lg text-xs font-bold text-slate-600 transition shadow-sm">
                                                <span><i class="fas fa-exclamation-circle text-rose-500 mr-1"></i> {{ $row['jumlah_kasus'] }} Pelanggaran</span>
                                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                            </button>
                                            <div x-show="open" x-transition class="absolute z-50 left-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden ring-1 ring-black ring-opacity-5" style="display: none;">
                                                <div class="bg-slate-50 px-3 py-2 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Riwayat Pelanggaran</div>
                                                <div class="max-h-48 overflow-y-auto">
                                                    @foreach($row['list_pelanggaran'] as $detail)
                                                        <div class="px-3 py-2 border-b border-slate-50 hover:bg-rose-50 transition flex justify-between items-center last:border-0 group">
                                                            <div><div class="text-xs font-bold text-slate-700 group-hover:text-rose-700">{{ $detail['jenis'] }}</div><div class="text-[10px] text-slate-400">{{ $detail['tanggal'] }}</div></div>
                                                            <div class="text-xs font-black text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded">+{{ $detail['poin'] }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                {{-- Poin --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap">
                                    @if($row['total_poin'] > 0)
                                        <span class="text-lg font-black {{ $row['total_poin'] > 20 ? 'text-rose-600' : 'text-slate-700' }}">{{ $row['total_poin'] }}</span>
                                    @else
                                        <span class="text-slate-300 font-bold">0</span>
                                    @endif
                                </td>

                                {{-- Status Sanksi (Logika Baru) --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap">
                                    @if($row['total_poin'] > 20)
                                        {{-- LEBIH DARI 20 = SANKSI TINDAKAN (Merah) --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-sm">
                                            <i class="fas fa-gavel text-[9px]"></i> SANKSI TINDAKAN
                                        </span>
                                    @elseif($row['total_poin'] > 0)
                                        {{-- 1 SAMPAI 20 = TEGURAN LISAN (Kuning/Orange) --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            <i class="fas fa-comment-dots text-[9px]"></i> TEGURAN LISAN
                                        </span>
                                    @else
                                        {{-- 0 = AMAN (Hijau) --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <i class="fas fa-check text-[9px]"></i> TIDAK ADA 
                                        </span>
                                    @endif
                                </td>

                                {{-- Opsi --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap">
                                    @if($row['total_poin'] > 0)
                                        <button wire:click="openModal('sp', {{ $row['id'] }})" class="text-[10px] font-bold text-rose-600 hover:text-rose-800 bg-white border border-rose-200 hover:bg-rose-50 px-3 py-1 rounded shadow-sm transition flex items-center justify-center gap-1 mx-auto"><i class="fas fa-file-contract"></i> Cetak SP</button>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center"><div class="flex flex-col items-center"><div class="bg-slate-100 p-3 rounded-full mb-2"><i class="fas fa-check-circle text-emerald-400 text-2xl"></i></div><span class="text-slate-500 font-medium">Tidak ada pelanggaran di periode ini.</span></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- MODAL PREVIEW (UPDATE LOGIKA DI SURAT JUGA) --}}
    @if($showModal && $selectedSiswa)
        <div wire:click.self="closeModal" class="fixed inset-0 z-[999] bg-slate-900/80 backdrop-blur-sm flex items-start justify-center overflow-y-auto p-4 md:p-8 print:p-0 print:bg-white print:fixed print:inset-0">
            <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl relative print:shadow-none print:w-full print:max-w-none transform transition-all scale-100">
                
                {{-- Modal Header --}}
                <div class="bg-slate-100 p-4 flex justify-between items-center border-b border-slate-200 no-print sticky top-0 z-10 rounded-t-xl">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-lg shadow-sm border border-slate-200"><i class="fas fa-print text-slate-600 text-lg"></i></div>
                        <div><h3 class="font-bold text-slate-800 text-sm md:text-base">Pratinjau Dokumen</h3><p class="text-xs text-slate-500">{{ $modalType == 'rapor' ? 'Rapor Akademik Siswa' : 'Surat Peringatan & Laporan' }}</p></div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="closeModal" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-sm font-bold transition">Tutup</button>
                        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md shadow-indigo-200 transition flex items-center gap-2"><i class="fas fa-print"></i> Cetak</button>
                    </div>
                </div>

                {{-- Kertas Dokumen (A4 Style) --}}
                <div class="p-8 md:p-12 font-serif text-slate-900 print:p-0 min-h-[800px]">
                    <div class="flex items-center justify-between border-b-4 border-double border-black pb-4 mb-8">
                        <div class="w-full text-center">
                            <h1 class="text-2xl font-bold uppercase tracking-widest font-sans">Homeschooling Group ABA</h1>
                            <p class="text-sm mt-1">Jl. Alamat Sekolah No. 123, Bogor | Telp: (0251) 1234567</p>
                            <p class="text-xs italic text-slate-600">Mencetak Generasi Mujahid, Mujadid, Mujtahid</p>
                        </div>
                    </div>

                    @if($modalType == 'rapor')
                        <div class="text-center mb-8"><h2 class="text-xl font-bold uppercase underline tracking-wide">LAPORAN HASIL BELAJAR</h2><p class="text-sm mt-1">Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</p></div>
                        <div class="flex justify-between mb-6 text-sm">
                            <table class="w-full">
                                <tr><td class="font-bold w-32 py-1">Nama Siswa</td><td>: {{ $selectedSiswa->nama }}</td><td class="font-bold w-32 py-1">Kelas</td><td>: {{ $selectedSiswa->kelas->nama_kelas }}</td></tr>
                                <tr><td class="font-bold py-1">NIS</td><td>: {{ $selectedSiswa->nis }}</td><td class="font-bold py-1">Semester</td><td>: Ganjil</td></tr>
                            </table>
                        </div>
                        <table class="w-full border-collapse border border-black text-sm">
                            <thead class="bg-gray-100">
                                <tr><th class="border border-black p-2 w-12 text-center">No</th><th class="border border-black p-2 text-left">Mata Pelajaran</th><th class="border border-black p-2 w-16 text-center">KKM</th><th class="border border-black p-2 w-16 text-center">Nilai</th><th class="border border-black p-2 w-16 text-center">Predikat</th><th class="border border-black p-2 text-left">Keterangan</th></tr>
                            </thead>
                            <tbody>
                                @foreach($dataModal as $index => $n)
                                    <tr><td class="border border-black p-2 text-center">{{ $index+1 }}</td><td class="border border-black p-2 font-medium">{{ $n->nama_mapel }}</td><td class="border border-black p-2 text-center">{{ $n->kkm }}</td><td class="border border-black p-2 text-center font-bold">{{ round($n->nilai_akhir) }}</td><td class="border border-black p-2 text-center">@if($n->nilai_akhir >= 90) A @elseif($n->nilai_akhir >= 80) B @elseif($n->nilai_akhir >= 70) C @else D @endif</td><td class="border border-black p-2 text-xs italic">{{ $n->nilai_akhir >= $n->kkm ? 'Tuntas.' : 'Perlu Remedial.' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if($modalType == 'sp')
                        <div class="text-center mb-8">
                            <h2 class="text-xl font-bold uppercase underline tracking-wide">
                                {{ $totalPoinSiswa > 20 ? 'SURAT SANKSI TINDAKAN' : 'LAPORAN KEDISIPLINAN' }}
                            </h2>
                            <p class="text-sm mt-1">Nomor: {{ rand(100,999) }}/BK/{{ date('m') }}/{{ date('Y') }}</p>
                        </div>
                        <p class="mb-4">Kepada Yth,<br><strong>Orang Tua / Wali Murid</strong><br>di Tempat</p>
                        <p class="mb-6 text-justify">Assalamu'alaikum Warahmatullahi Wabarakatuh,<br>Berdasarkan data kedisiplinan sekolah, kami sampaikan rekapitulasi pelanggaran siswa sebagai berikut:</p>
                        <div class="border border-black p-4 mb-6">
                            <table class="w-full text-sm">
                                <tr><td class="font-bold w-40 py-1">Nama Siswa</td><td>: {{ $selectedSiswa->nama }}</td></tr>
                                <tr><td class="font-bold py-1">Kelas</td><td>: {{ $selectedSiswa->kelas->nama_kelas }}</td></tr>
                                <tr><td class="font-bold py-1">Total Poin</td><td class="font-bold">: {{ $totalPoinSiswa }} Poin</td></tr>
                                <tr><td class="font-bold py-1">Status Sanksi</td><td class="font-bold uppercase text-red-600">: 
                                    @if($totalPoinSiswa > 20) SANKSI TINDAKAN (BERAT)
                                    @elseif($totalPoinSiswa > 0) TEGURAN LISAN
                                    @else AMAN
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <p class="mb-2 font-bold text-sm">Rincian Pelanggaran:</p>
                        <table class="w-full border-collapse border border-black text-sm mb-8">
                            <thead class="bg-gray-100">
                                <tr><th class="border border-black p-2 w-10 text-center">No</th><th class="border border-black p-2 w-32 text-center">Tanggal</th><th class="border border-black p-2 text-left">Jenis Pelanggaran</th><th class="border border-black p-2 w-20 text-center">Poin</th></tr>
                            </thead>
                            <tbody>
                                @foreach($dataModal as $idx => $p)
                                    <tr><td class="border border-black p-2 text-center">{{ $idx+1 }}</td><td class="border border-black p-2 text-center">{{ date('d/m/Y', strtotime($p->tanggal)) }}</td><td class="border border-black p-2">{{ $p->masterPelanggaran->jenis_pelanggaran }}</td><td class="border border-black p-2 text-center font-bold">+{{ $p->masterPelanggaran->poin }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="text-justify mb-8">
                            @if($totalPoinSiswa > 20)
                                Mengingat poin pelanggaran telah melampaui batas toleransi (20 Poin), kami memohon kehadiran Bapak/Ibu di sekolah untuk pembahasan tindak lanjut bersama Kepala Sekolah dan Tim Kedisiplinan.
                            @elseif($totalPoinSiswa > 0)
                                Laporan ini disampaikan sebagai bahan evaluasi bersama untuk meningkatkan kedisiplinan siswa di masa mendatang.
                            @else
                                Alhamdulillah, siswa tidak memiliki catatan pelanggaran.
                            @endif
                        </p>
                    @endif

                    <div class="flex justify-end mt-16 pr-8">
                        <div class="text-center"><p class="mb-20">Bogor, {{ date('d F Y') }}<br>Mengetahui, Kepala Sekolah / BK</p><p class="font-bold border-b border-black inline-block min-w-[150px]">( ........................... )</p></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        @media print {
            .no-print, #sidebar, header { display: none !important; }
            body { background: white; -webkit-print-color-adjust: exact; }
            .fixed { position: static; width: 100%; height: auto; overflow: visible; }
            @if($activeTab == 'akademik') @page { size: landscape; margin: 10mm; } @endif
        }
    </style>
</div>