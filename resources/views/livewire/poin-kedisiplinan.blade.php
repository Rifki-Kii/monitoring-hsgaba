<div class="min-h-screen bg-gray-50/50 p-6 font-sans">
    
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Poin Kedisiplinan</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Monitoring Poin & Sanksi Siswa</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 shadow-sm flex items-center gap-2">
            <i class="far fa-calendar-alt text-blue-500"></i> Hari ini: {{ date('d F Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (2/3): FORM INPUT --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        Form Input Pelanggaran
                    </h3>
                </div>

                <form wire:submit.prevent="store">
                    
                   {{-- 1. CARI SISWA --}}
                    <div class="mb-5 relative">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Cari Siswa (Nama/NIS)</label>
                        <div class="relative">
                            {{-- Ikon Search (Kiri) --}}
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                                <i class="fas fa-search"></i>
                            </span>

                            {{-- Input Field --}}
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 transition placeholder-slate-400 focus:outline-none" 
                                placeholder="Ketik nama siswa..." autocomplete="off">

                            {{-- TOMBOL CLEAR / CANCEL (Kanan) --}}
                            @if($search)
                                <button type="button" wire:click="$set('search', '')" 
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-rose-500 transition cursor-pointer"
                                    title="Hapus Pencarian">
                                    <i class="fas fa-times-circle text-lg"></i>
                                </button>
                            @endif
                        </div>

                        {{-- Hasil Pencarian Dropdown --}}
                        @if(!empty($searchResults) && count($searchResults) > 0)
                            <ul class="absolute z-20 w-full bg-white border border-slate-200 rounded-lg mt-1 shadow-xl max-h-60 overflow-y-auto animate-fade-in-up">
                                @foreach($searchResults as $result)
                                    <li wire:click="selectSiswa({{ $result->id }})" 
                                        class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-slate-50 last:border-0 transition flex justify-between items-center group">
                                        <div>
                                            <div class="font-bold text-slate-700 group-hover:text-blue-700">{{ $result->nama }}</div>
                                            <div class="text-xs text-slate-500">Kelas: {{ $result->kelas->nama_kelas ?? '-' }} &bull; NIS: {{ $result->nis }}</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-slate-300 group-hover:text-blue-400 text-xs"></i>
                                    </li>
                                @endforeach
                            </ul>
                        @endif  {{-- <--- JANGAN LUPA BAGIAN INI (Penutup IF Dropdown) --}}
                        
                        @error('siswa_id') <span class="text-rose-500 text-xs mt-1 block font-medium"><i class="fas fa-exclamation-circle mr-1"></i> Siswa harus dipilih dari daftar.</span> @enderror
                    </div>
                    {{-- JENIS PELANGGARAN --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Pelanggaran</label>
                        <div class="relative">
                            <select wire:model="master_pelanggaran_id" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 bg-white appearance-none cursor-pointer">
                                <option value="">-- Pilih Jenis Pelanggaran --</option>
                                @foreach($masterPelanggarans as $mp)
                                    <option value="{{ $mp->id }}">
                                        {{ $mp->jenis_pelanggaran }} (+{{ $mp->poin }} Poin)
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('master_pelanggaran_id') <span class="text-rose-500 text-xs mt-1 block font-medium">Pilih jenis pelanggaran.</span> @enderror
                    </div>

                    {{-- TANGGAL & KETERANGAN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal</label>
                            <input type="date" wire:model="tanggal" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan (Opsional)</label>
                            <input type="text" wire:model="keterangan" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500" placeholder="Kronologi...">
                        </div>
                    </div>

                    {{-- TOMBOL SIMPAN --}}
                    <div class="pt-5 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition flex items-center gap-2 transform active:scale-95">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

       {{-- KOLOM KANAN (1/3): INFO SISWA --}}
        <div class="lg:col-span-1">
            @if($selectedSiswa)
                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden mb-6 animate-fade-in-up h-fit sticky top-6">
                    
                    {{-- Header Profil --}}
                    <div class="bg-slate-800 p-6 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                        <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center text-slate-300 text-4xl mb-3 shadow-lg border-4 border-slate-700">
                            @if($selectedSiswa->foto)
                                <img src="{{ asset('storage/'.$selectedSiswa->foto) }}" class="w-full h-full rounded-full object-cover">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <h3 class="text-white font-bold text-lg tracking-wide">{{ $selectedSiswa->nama }}</h3>
                        <div class="inline-block bg-slate-700 rounded-full px-3 py-1 mt-2 text-xs text-slate-300 border border-slate-600">
                            {{ $selectedSiswa->kelas->nama_kelas ?? '-' }} &bull; {{ $selectedSiswa->nis }}
                        </div>
                    </div>
                    
                    {{-- Info Poin Aktif --}}
                    <div class="p-6 text-center border-b border-slate-100 bg-white">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Poin Aktif ({{ date('F') }})</p>
                        <div class="text-5xl font-black {{ $poinBulanIni >= 20 ? 'text-rose-600' : ($poinBulanIni >= 10 ? 'text-orange-500' : 'text-emerald-500') }}">
                            {{ $poinBulanIni }}
                        </div>
                        <div class="mt-3 text-xs {{ $poinBulanIni >= 20 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }} inline-block px-3 py-1 rounded-full font-medium">
                            @if($poinBulanIni >= 20)
                                <i class="fas fa-exclamation-circle"></i> Terkena Sanksi
                            @else
                                Batas Sanksi: <strong>20 Poin</strong>
                            @endif
                        </div>
                    </div>

                    {{-- Riwayat Terakhir (HANYA 1 DATA) --}}
                    <div class="bg-slate-50 border-t border-slate-200">
                        <div class="px-6 py-1 border-b border-slate-100">
                            <h4 class="font-bold text-slate-800 text-xs uppercase flex items-center gap-2">
                                <i class="fas fa-clock text-blue-500"></i> Terakhir Melanggar
                            </h4>
                        </div>
                        
                        <div class="p-1">
                            @if($riwayatPelanggaran->isNotEmpty())
                                {{-- AMBIL DATA PERTAMA SAJA --}}
                                @php $latest = $riwayatPelanggaran->first(); @endphp

                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm relative pl-4">
                                    {{-- Indikator Warna --}}
                                    <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $latest->masterPelanggaran->poin >= 10 ? 'bg-rose-500' : 'bg-blue-400' }}"></div>
                                    
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="text-sm font-bold text-slate-800 leading-tight">
                                            {{ $latest->masterPelanggaran->jenis_pelanggaran }}
                                        </p>
                                        <span class="text-xs font-bold {{ $latest->masterPelanggaran->poin >= 10 ? 'text-rose-600' : 'text-blue-600' }}">
                                            +{{ $latest->masterPelanggaran->poin }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                                        <i class="far fa-calendar"></i> {{ date('d F Y', strtotime($latest->tanggal)) }}
                                    </div>
                                    
                                    @if($latest->keterangan)
                                        <p class="text-xs text-slate-500 italic bg-slate-50 p-2 rounded border border-slate-100">
                                            "{{ $latest->keterangan }}"
                                        </p>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-4 opacity-75">
                                    <i class="fas fa-check-circle text-4xl text-emerald-100 mb-2"></i>
                                    <p class="text-xs text-slate-400">Belum ada pelanggaran.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-sm border border-dashed border-slate-300 p-8 text-center h-full flex flex-col items-center justify-center text-slate-400 min-h-[400px]">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-id-card-alt text-4xl text-slate-300"></i>
                    </div>
                    <h4 class="font-bold text-slate-600 text-lg">Data Siswa</h4>
                    <p class="text-sm mt-1 max-w-[200px]">Pilih siswa untuk melihat profil.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL ALERT MERAH --}}
    @if($showSanctionAlert)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-bounce-in">
            <div class="bg-rose-600 p-6 text-center relative overflow-hidden">
                <i class="fas fa-exclamation-triangle text-5xl text-white mb-3 relative z-10 animate-pulse"></i>
                <h3 class="text-2xl font-black text-white uppercase tracking-wide relative z-10">PERINGATAN SANKSI</h3>
            </div>
            <div class="p-8 text-center">
                <p class="text-lg text-slate-700 mb-4 font-medium">
                    Siswa <span class="font-bold text-rose-600 bg-rose-50 px-2 rounded">{{ $selectedSiswa->nama }}</span> telah mencapai batas toleransi.
                </p>
                <div class="bg-gradient-to-br from-rose-50 to-white border border-rose-100 rounded-xl p-4 mb-6 shadow-inner">
                    <p class="text-xs text-rose-800 uppercase font-bold tracking-widest">Total Poin Bulan Ini</p>
                    <p class="text-5xl font-black text-rose-600 mt-1">{{ $poinBulanIni }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-lg text-left text-sm text-slate-700 border border-slate-200 shadow-sm">
                    <strong class="text-rose-600 flex items-center gap-2 mb-2"><i class="fas fa-gavel"></i> Tindakan Wajib:</strong>
                    <ul class="space-y-2 pl-1">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-times-circle text-rose-400 mt-0.5"></i> <span>Dilarang mengikuti kegiatan <strong>Renang</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-times-circle text-rose-400 mt-0.5"></i> <span>Dilarang mengikuti kegiatan <strong>Piket Kelas</strong>.</span>
                        </li>
                    </ul>
                </div>
                <button wire:click="closeAlert" class="mt-6 w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transition transform active:scale-95">
                    Saya Mengerti, Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success'))
        <div class="fixed bottom-6 right-6 bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-slide-in-right z-50">
            <div class="bg-white/20 p-2 rounded-full"><i class="fas fa-check text-white"></i></div>
            <div>
                <h4 class="font-bold text-sm">Berhasil!</h4>
                <p class="text-xs text-emerald-100">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- BAGIAN BARU: TABEL DATA PELANGGARAN + DROPDOWN BULAN    --}}
    {{-- ========================================================= --}}
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <div class="flex items-center gap-4">
                {{-- JUDUL TABEL --}}
                <div>
                    @if($selectedSiswa)
                        <h3 class="font-bold text-lg text-blue-700 flex items-center gap-2">
                            <i class="fas fa-history bg-blue-100 p-1.5 rounded-md"></i> Log Siswa: {{ $selectedSiswa->nama }}
                        </h3>
                    @else
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <i class="fas fa-table bg-slate-200 p-1.5 rounded-md text-slate-600"></i> Laporan Pelanggaran
                        </h3>
                    @endif
                    <p class="text-xs text-slate-500 mt-1">Data statistik pelanggaran bulanan.</p>
                </div>

                {{-- DROPDOWN FILTER PERIODE (DISINI POSISINYA SEKARANG) --}}
                {{-- AREA FILTER (KANAN JUDUL) --}}
                {{-- AREA FILTER (KANAN JUDUL) --}}
                <div class="flex flex-col sm:flex-row gap-2">
                    
                    {{-- 1. FILTER PERIODE BULAN (SEKARANG DI KIRI) --}}
                    <div class="relative">
                        <select wire:model.live="selectedMonth" class="pl-3 pr-8 py-2 rounded-lg border border-slate-300 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm w-full sm:w-auto">
                            @foreach($availableMonths as $month)
                                <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. FILTER KELAS (SEKARANG DI KANAN) --}}
                    {{-- Hanya muncul jika tidak sedang memilih satu siswa spesifik --}}
                    @if(!$selectedSiswa)
                        <div class="relative">
                            <select wire:model.live="selectedKelas" class="pl-3 pr-8 py-2 rounded-lg border border-slate-300 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm w-full sm:w-auto">
                                <option value="">Semua Kelas</option>
                                @foreach($availableKelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </div>
            </div>
            
            <div class="flex items-center gap-4">
                @if($selectedSiswa)
                    <button wire:click="resetFilterTabel" class="text-xs bg-white border border-slate-300 hover:bg-slate-100 text-slate-600 px-3 py-2 rounded-lg font-bold shadow-sm transition flex items-center gap-2">
                        <i class="fas fa-times text-rose-500"></i> Kembali Riwayat Semua 
                    </button>
                @endif

                {{-- Indikator Warna --}}
                <div class="flex gap-3 text-xs border-l border-slate-300 pl-4">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Ringan</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Sedang</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span> Berat</div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Pelanggaran</th>
                        <th class="px-6 py-4 text-center">Poin</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($semuaPelanggaran as $data)
                        <tr class="hover:bg-blue-50/30 transition group">
                            {{-- Tanggal --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-700">{{ date('d M Y', strtotime($data->tanggal)) }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $data->created_at->format('H:i') }} WIB</div>
                            </td>

                            {{-- Nama Siswa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs border border-slate-200">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-700 block group-hover:text-blue-600 transition">{{ $data->siswa->nama }}</span>
                                        <span class="text-xs text-slate-400">NIS: {{ $data->siswa->nis }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Kelas --}}
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-xs font-bold border border-slate-200">
                                    {{ $data->siswa->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>

                            {{-- Jenis Pelanggaran --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $data->masterPelanggaran->jenis_pelanggaran }}</div>
                                @if($data->keterangan)
                                    <div class="text-xs text-slate-500 italic mt-1">"{{ $data->keterangan }}"</div>
                                @endif
                            </td>

                            {{-- Poin --}}
                            <td class="px-6 py-4 text-center">
                                @php $p = $data->masterPelanggaran->poin; @endphp
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                    {{ $p >= 20 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 
                                      ($p >= 5  ? 'bg-orange-100 text-orange-700 border border-orange-200' : 
                                                  'bg-emerald-100 text-emerald-700 border border-emerald-200') }}">
                                    +{{ $p }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <button wire:click="delete({{ $data->id }})" 
                                    wire:confirm="Hapus data ini?"
                                    class="text-slate-300 hover:text-rose-600 transition p-2 rounded-full hover:bg-rose-50">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-folder-open text-3xl mb-2 opacity-50"></i>
                                <p>Tidak ada data pelanggaran pada bulan <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}</strong>.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-white">
            {{ $semuaPelanggaran->links() }} 
        </div>
    </div> 

</div>