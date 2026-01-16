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
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 transition placeholder-slate-400 focus:outline-none" 
                                placeholder="Ketik nama siswa..." autocomplete="off">

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
                        @endif
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

                    <div class="bg-slate-50 border-t border-slate-200">
                        <div class="px-6 py-1 border-b border-slate-100">
                            <h4 class="font-bold text-slate-800 text-xs uppercase flex items-center gap-2">
                                <i class="fas fa-clock text-blue-500"></i> Terakhir Melanggar
                            </h4>
                        </div>
                        <div class="p-1">
                            @if($riwayatPelanggaran->isNotEmpty())
                                @php $latest = $riwayatPelanggaran->first(); @endphp
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm relative pl-4">
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

    {{-- MODAL ALERT MERAH (Auto Popup) --}}
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
                        <li class="flex items-start gap-2"><i class="fas fa-times-circle text-rose-400 mt-0.5"></i> <span>Dilarang mengikuti kegiatan <strong>Renang</strong>.</span></li>
                        <li class="flex items-start gap-2"><i class="fas fa-times-circle text-rose-400 mt-0.5"></i> <span>Dilarang mengikuti kegiatan <strong>Piket Kelas</strong>.</span></li>
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
    {{-- TABEL DATA AKUMULASI + STATUS SANKSI MANUAL (TERBARU)     --}}
    {{-- ========================================================= --}}
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-visible">
        
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <i class="bg-rose-100 p-1.5 rounded-md text-rose-600"></i> List Pelanggaran
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Tentukan aksi berdasarkan poin siswa.</p>
                </div>

                {{-- FILTER GROUP --}}
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative">
                        <select wire:model.live="selectedMonth" class="pl-3 pr-8 py-2 rounded-lg border border-slate-300 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm">
                            @foreach($availableMonths as $month)
                                <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(!$selectedSiswa)
                        <div class="relative">
                            <select wire:model.live="selectedKelas" class="pl-3 pr-8 py-2 rounded-lg border border-slate-300 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm">
                                <option value="">Semua Kelas</option>
                                @foreach($availableKelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($selectedSiswa)
                <button wire:click="resetFilterTabel" class="text-xs bg-white border border-slate-300 hover:bg-slate-100 text-slate-600 px-3 py-2 rounded-lg font-bold shadow-sm transition flex items-center gap-2">
                    <i class="fas fa-undo text-blue-500"></i> Reset Pencarian
                </button>
            @endif
        </div>

        <div class="overflow-visible">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-1/4">Siswa</th>
                        <th class="px-6 py-4 w-1/3">Pelanggaran</th>
                        <th class="px-6 py-4 text-center">Poin</th>
                        <th class="px-6 py-4 text-center">Status Aksi</th>
                        <th class="px-6 py-4 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($dataLaporan as $row) 
                        @php 
                            $totalPoin = $row->catatanPelanggarans->sum(fn($i) => $i->masterPelanggaran->poin);
                            $count = $row->catatanPelanggarans->count();
                        @endphp

                        <tr class="hover:bg-slate-50/80 transition align-top">
                            {{-- 1. SISWA --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm border border-slate-200">{{ substr($row->nama, 0, 1) }}</div>
                                    <div><div class="font-bold text-slate-700 text-base">{{ $row->nama }}</div><div class="text-xs text-slate-500">{{ $row->kelas->nama_kelas ?? '-' }} &bull; NIS: {{ $row->nis }}</div></div>
                                </div>
                            </td>

                            {{-- 2. PELANGGARAN COMPACT (RED THEME) --}}
                            <td class="px-6 py-4">
                                @if($row->catatanPelanggarans->isEmpty())
                                    <span class="text-slate-300 text-xs italic">Nihil</span>
                                @else
                                    @php 
                                        $latest = $row->catatanPelanggarans->first(); 
                                        $sisaCount = $count - 1;
                                        $severityColor = $latest->masterPelanggaran->poin >= 10 ? 'bg-rose-600' : 'bg-orange-400';
                                    @endphp
                                    <div class="flex flex-col gap-1 items-start w-full max-w-[200px]">
                                        <div class="bg-white border border-slate-200 rounded p-1.5 shadow-sm w-full relative overflow-hidden group hover:border-rose-300 transition cursor-default">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $severityColor }}"></div>
                                            <div class="pl-2">
                                                <div class="flex justify-between items-start gap-2">
                                                    <span class="text-[11px] font-bold text-slate-700 leading-tight line-clamp-1" title="{{ $latest->masterPelanggaran->jenis_pelanggaran }}">{{ $latest->masterPelanggaran->jenis_pelanggaran }}</span>
                                                    <span class="text-[10px] font-black text-rose-600 whitespace-nowrap">+{{ $latest->masterPelanggaran->poin }}</span>
                                                </div>
                                                <div class="text-[9px] text-slate-400 mt-0.5">{{ date('d/m/y', strtotime($latest->tanggal)) }}</div>
                                            </div>
                                        </div>
                                        @if($sisaCount > 0)
                                            <div x-data="{ open: false }" class="relative w-full">
                                                <button @click="open = !open" @click.outside="open = false" class="text-[10px] font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-100 px-2 py-1 rounded w-full flex items-center justify-between transition">
                                                    <span>+ {{ $sisaCount }} Lainnya</span>
                                                    <i class="fas fa-chevron-down text-[9px] transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                                </button>
                                                <div x-show="open" x-transition class="absolute z-50 left-0 mt-1 w-56 bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden ring-1 ring-black ring-opacity-5">
                                                    <div class="bg-rose-50 px-2 py-1.5 border-b border-rose-100 text-[9px] font-bold text-rose-800 uppercase tracking-wider">Riwayat Sebelumnya</div>
                                                    <div class="max-h-32 overflow-y-auto">
                                                        @foreach($row->catatanPelanggarans->skip(1) as $history)
                                                            <div class="px-2 py-1.5 border-b border-slate-50 hover:bg-rose-50 transition flex justify-between items-center last:border-0">
                                                                <div class="overflow-hidden">
                                                                    <div class="text-[10px] font-medium text-slate-700 truncate">{{ $history->masterPelanggaran->jenis_pelanggaran }}</div>
                                                                    <div class="text-[9px] text-slate-400">{{ date('d/m/y', strtotime($history->tanggal)) }}</div>
                                                                </div>
                                                                <div class="text-[10px] font-bold text-rose-500 pl-2">+{{ $history->masterPelanggaran->poin }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- 3. TOTAL POIN (RATA TENGAH) --}}
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center items-center h-full">
                                    <span class="text-xl font-black {{ $totalPoin >= 20 ? 'text-rose-600' : ($totalPoin > 0 ? 'text-slate-700' : 'text-emerald-500') }}">{{ $totalPoin }}</span>
                                </div>
                            </td>

                            {{-- 4. STATUS SANKSI (RATA TENGAH & COMPACT) --}}
                            <td class="px-6 py-4 align-middle text-center">
                                <div class="flex justify-center items-center h-full w-full">
                                    @if($row->status_sanksi)
                                        @php
                                            $s = $row->status_sanksi;
                                            $style = 'bg-slate-100 text-slate-600 border-slate-300';
                                            if (Str::contains($s, ['SP', 'Peringatan', 'Skorsing', 'Tindakan', 'Orang Tua'])) {
                                                $style = 'bg-rose-100 text-rose-700 border-rose-200';
                                            } elseif (Str::contains($s, ['Piket', 'Pembinaan', 'Bersih'])) {
                                                $style = 'bg-orange-100 text-orange-700 border-orange-200';
                                            } elseif (Str::contains($s, ['Teguran', 'Lisan', 'Nasihat'])) {
                                                $style = 'bg-blue-100 text-blue-700 border-blue-200';
                                            }
                                        @endphp
                                        <span class="inline-block px-3 py-1.5 rounded-md border text-[10px] font-bold uppercase tracking-wide whitespace-nowrap shadow-sm {{ $style }}">
                                            {{ $s }}
                                        </span>
                                    @else
                                        @if($totalPoin > 0)
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="inline-block px-2 py-1 rounded-md border border-slate-200 bg-slate-50 text-slate-400 text-[10px] font-bold whitespace-nowrap">
                                                    Belum Ditindak
                                                </span>
                                                @if($totalPoin >= 20)
                                                    <span class="text-[9px] text-rose-600 font-extrabold animate-pulse whitespace-nowrap">! WAJIB PROSES</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wide whitespace-nowrap opacity-80">
                                                Aman
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            {{-- 5. OPSI (RATA TENGAH) --}}
                            <td class="px-6 py-4 align-middle text-center">
                                <div class="flex justify-center items-center h-full">
                                    @if($totalPoin > 0)
                                        <button wire:click="openSanksiModal({{ $row->id }})" 
                                            class="text-xs font-bold text-white bg-red-600 hover:bg-blue-800 px-3 py-2 rounded-lg shadow-md hover:shadow-lg transition flex items-center gap-2 transform active:scale-95">
                                            <i class="fas fa-gavel"></i> Tindakan
                                        </button>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                <p>Tidak ada data pelanggaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-white">{{ $dataLaporan->links() }}</div>
    </div>

    {{-- MODAL PILIH SANKSI --}}
  {{-- MODAL PILIH SANKSI (VERSI COMPACT / RINGKAS) --}}
    @if($showSanksiModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4 animate-fade-in">
        {{-- Ukuran diperkecil: max-w-sm --}}
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-100">
            
            {{-- Header Modal (Lebih Tipis) --}}
            <div class="bg-blue-800 px-4 py-3 flex justify-between items-center">
                <h3 class="text-white font-bold text-sm">Tindak Lanjut Siswa</h3>
                <button wire:click="closeSanksiModal" class="text-slate-400 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>

            <div class="p-4">
                
                {{-- INFO SISWA & POIN (COMPACT ROW) --}}
                {{-- Dibuat sebaris agar hemat tempat --}}
                <div class="mb-4 bg-slate-50 px-3 py-2.5 rounded-lg border border-slate-200 flex justify-between items-center shadow-sm">
                    <div>
                        <p class="text-[9px] text-slate-400 uppercase font-bold">Nama Siswa</p>
                        <h2 class="text-sm font-bold text-blue-800 truncate max-w-[160px]">{{ $targetSiswaNama }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-slate-400 uppercase font-bold">Total Poin</p>
                        <span class="text-base font-black {{ $targetSiswaPoin >= 20 ? 'text-rose-600' : ($targetSiswaPoin >= 10 ? 'text-orange-500' : 'text-blue-600') }}">
                            {{ $targetSiswaPoin }}
                        </span>
                    </div>
                </div>

                {{-- Form Pilihan (Lebih Rapat) --}}
                <div class="space-y-2">
                    <p class="text-xs font-bold text-slate-700 mb-1">Pilih Keputusan:</p>
                    
                    <label class="flex items-center px-3 py-2 border border-slate-200 rounded-md cursor-pointer hover:bg-blue-50 transition gap-2 group {{ $inputSanksi == 'Teguran Lisan' ? 'bg-blue-50 border-blue-300 ring-1 ring-blue-300' : '' }}">
                        <input type="radio" wire:model="inputSanksi" value="Teguran Lisan" class="w-3.5 h-3.5 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-medium text-slate-700 group-hover:text-blue-700">Teguran Lisan</span>
                    </label>

                    <label class="flex items-center px-3 py-2 border border-slate-200 rounded-md cursor-pointer hover:bg-orange-50 transition gap-2 group {{ $inputSanksi == 'Piket Kebersihan' ? 'bg-orange-50 border-orange-300 ring-1 ring-orange-300' : '' }}">
                        <input type="radio" wire:model="inputSanksi" value="Piket Kebersihan" class="w-3.5 h-3.5 text-orange-500 focus:ring-orange-500">
                        <span class="text-xs font-medium text-slate-700 group-hover:text-orange-700">Sanksi: Piket Kebersihan</span>
                    </label>

                    <label class="flex items-center px-3 py-2 border border-slate-200 rounded-md cursor-pointer hover:bg-rose-50 transition gap-2 group {{ $inputSanksi == 'Surat Peringatan 1' ? 'bg-rose-50 border-rose-300 ring-1 ring-rose-300' : '' }}">
                        <input type="radio" wire:model="inputSanksi" value="Surat Peringatan 1" class="w-3.5 h-3.5 text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-medium text-slate-700 group-hover:text-rose-700">Surat Peringatan (SP 1)</span>
                    </label>

                    <label class="flex items-center px-3 py-2 border border-slate-200 rounded-md cursor-pointer hover:bg-rose-50 transition gap-2 group {{ $inputSanksi == 'Panggilan Orang Tua' ? 'bg-rose-50 border-rose-300 ring-1 ring-rose-300' : '' }}">
                        <input type="radio" wire:model="inputSanksi" value="Panggilan Orang Tua" class="w-3.5 h-3.5 text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-medium text-slate-700 group-hover:text-rose-700">Panggilan Orang Tua</span>
                    </label>

                    <div class="mt-1">
                        <input type="text" wire:model="inputSanksi" placeholder="Ketik sanksi lain..." class="w-full px-3 py-1.5 border border-slate-300 rounded-md text-xs focus:ring-2 focus:ring-slate-500 focus:outline-none">
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <button wire:click="closeSanksiModal" class="w-full bg-white border border-slate-300 text-slate-700 font-bold py-2 rounded-md hover:bg-slate-50 transition text-xs">Batal</button>
                    <button wire:click="simpanSanksi" class="w-full bg-blue-800 text-white font-bold py-2 rounded-md hover:bg-slate-900 shadow-md transition text-xs">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>