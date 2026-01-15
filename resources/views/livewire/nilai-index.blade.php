<div class="min-h-screen bg-slate-50 p-6 font-sans text-slate-900">
    
    {{-- 1. HEADER PAGE --}}
    <div class="max-w-7xl mx-auto mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Monitoring Akademik</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">TA: {{ $tahun_ajaran }}</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 capitalize">{{ $semester }}</span>
                </div>
            </div>
            
            {{-- TOMBOL AKSI --}}
            @if($kelas_id && $mapel_id && count($siswaList) > 0)
                <div class="flex items-center gap-3">
                    
                    {{-- 1. TOMBOL EXPORT --}}
                    <button wire:click="exportExcel" wire:loading.attr="disabled" class="group relative inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-green-700 transition-all duration-200 bg-green-100 border border-green-200 rounded-lg hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-file-excel mr-2"></i> 
                        <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                        <span wire:loading wire:target="exportExcel">Downloading...</span>
                    </button>

                    {{-- 2. TOMBOL IMPORT (Upload) --}}
                    <div x-data="{ uploading: false }" class="relative">
                        <label class="cursor-pointer inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-orange-700 transition-all duration-200 bg-orange-100 border border-orange-200 rounded-lg hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <i class="fas fa-upload mr-2"></i> Import Excel
                            <input type="file" wire:model="fileImport" wire:change="importExcel" class="hidden" accept=".xlsx, .xls"
                                x-on:change="uploading = true; $wire.importExcel().then(() => { uploading = false; })">
                        </label>
                        {{-- Loading Indicator Import --}}
                        <div wire:loading wire:target="fileImport" class="absolute top-full left-0 mt-1 w-full text-center">
                            <span class="text-[10px] text-orange-600 font-bold">Uploading...</span>
                        </div>
                    </div>

                    {{-- DIVIDER --}}
                    <div class="h-6 w-px bg-slate-300 mx-1"></div>

                    {{-- 3. TOMBOL MODE INPUT (Yg Lama) --}}
                    @if($mode == 'view')
                        <button wire:click="gantiMode('input')" class="inline-flex items-center justify-center px-5 py-2 text-sm font-bold text-white transition-all duration-200 bg-blue-700 rounded-lg hover:bg-blue-800 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                            <i class="fas fa-edit mr-2"></i> Input Manual
                        </button>
                    @else
                        <button wire:click="gantiMode('view')" class="inline-flex items-center justify-center px-5 py-2 text-sm font-bold text-slate-700 transition-all duration-200 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 shadow-sm">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- 2. FILTER CARD --}}
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kelas</label>
                <select wire:model.live="kelas_id" class="block w-full rounded-lg border-slate-300 bg-slate-50 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 p-2.5 cursor-pointer hover:bg-slate-100 transition">
                    @foreach($kelasList as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mata Pelajaran</label>
                <select wire:model.live="mapel_id" class="block w-full rounded-lg border-slate-300 bg-slate-50 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 p-2.5 cursor-pointer hover:bg-slate-100 transition">
                    @foreach($mapelList as $m) <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tahun Ajaran</label>
                <select wire:model.live="tahun_ajaran" class="block w-full rounded-lg border-slate-300 bg-slate-50 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 p-2.5 cursor-pointer hover:bg-slate-100 transition">
                    @foreach($tahunList as $t) <option value="{{ $t }}">{{ $t }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Semester</label>
                <select wire:model.live="semester" class="block w-full rounded-lg border-slate-300 bg-slate-50 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 p-2.5 cursor-pointer hover:bg-slate-100 transition">
                    <option value="ganjil">Ganjil</option><option value="genap">Genap</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ALERT MESSAGE --}}
    @if (session()->has('message'))
        <div class="max-w-7xl mx-auto mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded shadow-sm flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 transition"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- 3. MAIN TABLE AREA --}}
    @if($kelas_id && $mapel_id && count($siswaList) > 0)
        
        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
            
            {{-- Banner Mode Input --}}
            @if($mode == 'input')
                <div class="bg-blue-600 px-6 py-3 flex justify-between items-center text-white shadow-inner">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-keyboard text-blue-200 text-lg"></i>
                        <span class="font-bold uppercase tracking-wide text-sm">Mode Edit Nilai</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs bg-blue-500/50 px-3 py-1 rounded-full border border-blue-400 font-medium backdrop-blur-sm">
                            <i class="fas fa-save mr-1"></i> Autosave Aktif
                        </span>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="simpanSemua">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        
                        {{-- TABLE HEADER (BLUE THEME) --}}
                        <thead>
                            <tr class="bg-blue-900 text-white uppercase text-xs leading-normal">
                                <th class="py-4 px-6 text-left border-r border-blue-800 w-64 font-bold tracking-wider">Nama Siswa</th>
                                
                                {{-- KELOMPOK NILAI INPUT --}}
                                <th class="py-3 px-2 text-center border-r border-blue-800 w-24 bg-blue-800">Rata UH<br><span class="text-[9px] text-blue-200 normal-case">(Komponen A)</span></th>
                                <th class="py-3 px-2 text-center border-r border-blue-800 w-24 bg-blue-800">Tugas<br><span class="text-[9px] text-blue-200 normal-case">(Komponen B)</span></th>
                                <th class="py-3 px-2 text-center border-r border-blue-800 w-24 bg-blue-800">PTS<br><span class="text-[9px] text-blue-200 normal-case">(Komponen C)</span></th>
                                <th class="py-3 px-2 text-center border-r border-blue-800 w-24 bg-blue-800">PAS<br><span class="text-[9px] text-blue-200 normal-case">(Komponen D)</span></th>

                                {{-- HASIL HITUNG --}}
                                <th class="py-3 px-4 text-center border-r border-blue-800 bg-indigo-900 w-28">
                                    N. Kognitif<br><span class="text-[9px] text-indigo-200 normal-case">(Pengetahuan)</span>
                                </th>
                                <th class="py-3 px-4 text-center border-r border-blue-800 w-28 bg-teal-900">
                                    N. Praktek<br><span class="text-[9px] text-teal-200 normal-case">(Keterampilan)</span>
                                </th>

                                {{-- NILAI FINAL --}}
                                <th class="py-3 px-4 text-center border-r border-blue-800 bg-slate-900 w-32 border-l-4 border-l-yellow-500">
                                    <div class="text-[9px] text-yellow-400 font-bold mb-0.5 uppercase tracking-wider">KKM: {{ $kkm }}</div>
                                    <div class="text-sm font-black text-white">NILAI RAPORT</div>
                                </th>
                                <th class="py-3 px-4 text-center w-20 bg-blue-900">Mutu<br><span class="text-[9px] text-blue-200 normal-case">(Grade)</span></th>
                            </tr>
                        </thead>
                        
                        {{-- TABLE BODY --}}
                        <tbody class="text-slate-700 text-sm">
                            @foreach($siswaList as $index => $siswa)
                                @php
                                    $predikat = $inputNilai[$siswa->id]['predikat'];
                                    
                                    // LOGIKA WARNA BADGE MUTU/GRADE
                                    $badgeClass = 'bg-gray-100 text-gray-500'; // Default
                                    
                                    if (in_array($predikat, ['A', 'A+'])) {
                                        $badgeClass = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'; // Hijau (Excellent)
                                    } elseif (in_array($predikat, ['B', 'B+'])) {
                                        $badgeClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-200'; // Biru (Good)
                                    } elseif (in_array($predikat, ['C', 'C+'])) {
                                        $badgeClass = 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200'; // Kuning (Warning)
                                    } elseif (in_array($predikat, ['D', 'E'])) {
                                        $badgeClass = 'bg-red-100 text-red-700 ring-1 ring-red-200'; // Merah (Danger)
                                    }

                                    // Zebra Striping
                                    $rowClass = $index % 2 === 0 ? 'bg-white' : 'bg-slate-50'; 
                                @endphp

                                <tr class="{{ $rowClass }} border-b border-slate-200 hover:bg-blue-50 transition-colors duration-150">
                                    
                                    {{-- NAMA --}}
                                    <td class="py-3 px-6 text-left whitespace-nowrap border-r border-slate-200">
                                        <div class="font-bold text-slate-800">{{ $siswa->nama }}</div>
                                        @if($mode=='view') <div class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $siswa->nis ?? '' }}</div> @endif
                                    </td>

                                    {{-- INPUT FIELDS --}}
                                    <td class="p-0 border-r border-slate-200 relative h-12">
                                        @if($mode=='input') 
                                            <input type="number" wire:model.blur="inputNilai.{{ $siswa->id }}.rata_uh" wire:change="hitungNilai({{ $siswa->id }})" class="absolute inset-0 w-full h-full border-0 text-center bg-transparent focus:ring-2 focus:ring-inset focus:ring-blue-600 text-slate-800 font-semibold" placeholder="0">
                                        @else 
                                            <div class="w-full h-full flex items-center justify-center font-medium">{{ $inputNilai[$siswa->id]['rata_uh'] ?? '-' }}</div>
                                        @endif
                                    </td>
                                    <td class="p-0 border-r border-slate-200 relative bg-blue-50/20">
                                        @if($mode=='input') 
                                            <input type="number" wire:model.blur="inputNilai.{{ $siswa->id }}.tugas" wire:change="hitungNilai({{ $siswa->id }})" class="absolute inset-0 w-full h-full border-0 text-center bg-transparent focus:ring-2 focus:ring-inset focus:ring-blue-600 text-blue-800 font-semibold" placeholder="0">
                                        @else 
                                            <div class="w-full h-full flex items-center justify-center font-medium text-blue-700">{{ $inputNilai[$siswa->id]['tugas'] ?? '-' }}</div>
                                        @endif
                                    </td>
                                    <td class="p-0 border-r border-slate-200 relative">
                                        @if($mode=='input') 
                                            <input type="number" wire:model.blur="inputNilai.{{ $siswa->id }}.pts" wire:change="hitungNilai({{ $siswa->id }})" class="absolute inset-0 w-full h-full border-0 text-center bg-transparent focus:ring-2 focus:ring-inset focus:ring-blue-600 text-indigo-800 font-semibold" placeholder="0">
                                        @else 
                                            <div class="w-full h-full flex items-center justify-center font-medium text-indigo-700">{{ $inputNilai[$siswa->id]['pts'] ?? '-' }}</div>
                                        @endif
                                    </td>
                                    <td class="p-0 border-r border-slate-200 relative bg-blue-50/20">
                                        @if($mode=='input') 
                                            <input type="number" wire:model.blur="inputNilai.{{ $siswa->id }}.pas" wire:change="hitungNilai({{ $siswa->id }})" class="absolute inset-0 w-full h-full border-0 text-center bg-transparent focus:ring-2 focus:ring-inset focus:ring-blue-600 text-purple-800 font-semibold" placeholder="0">
                                        @else 
                                            <div class="w-full h-full flex items-center justify-center font-medium text-purple-700">{{ $inputNilai[$siswa->id]['pas'] ?? '-' }}</div>
                                        @endif
                                    </td>

                                    {{-- HASIL KOGNITIF --}}
                                    <td class="py-3 px-4 text-center border-r border-slate-200 bg-gray-100 font-bold text-slate-600">
                                        {{ $inputNilai[$siswa->id]['nilai_pengetahuan'] ?: '-' }}
                                    </td>

                                    {{-- INPUT KETERAMPILAN --}}
                                    <td class="p-0 border-r border-slate-200 relative bg-teal-50/50">
                                        @if($mode=='input') 
                                            <input type="number" wire:model.blur="inputNilai.{{ $siswa->id }}.keterampilan" wire:change="hitungNilai({{ $siswa->id }})" class="absolute inset-0 w-full h-full border-0 text-center bg-transparent focus:ring-2 focus:ring-inset focus:ring-teal-600 text-teal-800 font-bold" placeholder="0">
                                        @else 
                                            <div class="w-full h-full flex items-center justify-center font-bold text-teal-700">{{ $inputNilai[$siswa->id]['keterampilan'] ?? '-' }}</div>
                                        @endif
                                    </td>

                                    {{-- NILAI RAPORT (FINAL) --}}
                                    <td class="py-3 px-4 text-center border-r border-slate-200 bg-yellow-50/80 border-l-2 border-l-yellow-400">
                                        @if($inputNilai[$siswa->id]['nilai_raport'] > 0)
                                            <span class="text-md font-black text-slate-900">
                                                {{ $inputNilai[$siswa->id]['nilai_raport'] }}
                                            </span>
                                        @else
                                            <span class="text-slate-300 font-medium">-</span>
                                        @endif
                                    </td>

                                    {{-- PREDIKAT (WARNA-WARNI) --}}
                                    <td class="py-3 px-4 text-center">
                                        @if($predikat != '-')
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-black shadow-sm {{ $badgeClass }}">
                                                {{ $predikat }}
                                            </span>
                                        @else
                                            <span class="text-slate-300 font-bold">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ACTION BAR (INPUT MODE ONLY) --}}
                @if($mode == 'input')
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                        <div class="text-xs text-gray-500 mr-auto hidden md:block">
                            <i class="fas fa-info-circle mr-1"></i> Pastikan semua data sudah benar sebelum menyimpan.
                        </div>
                        <button type="button" wire:click="gantiMode('view')" 
                            class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 shadow-sm transition-colors focus:ring-2 focus:ring-slate-400">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-md transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> SIMPAN SEMUA
                        </button>
                    </div>
                @endif
            </form>
        </div>
    
    @elseif($kelas_id && $mapel_id)
        {{-- EMPTY STATE --}}
        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="fas fa-users-slash text-slate-300 text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Data Siswa Tidak Ditemukan</h3>
            <p class="text-slate-500 mt-2 max-w-md mx-auto">Siswa belum terdaftar di kelas ini.</p>
        </div>

    @else
        {{-- INITIAL STATE --}}
        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-dashed border-slate-300 p-20 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-filter text-blue-300 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Mulai Monitoring</h3>
            <p class="text-slate-500 mt-2">Silakan pilih <strong>Kelas</strong> dan <strong>Mata Pelajaran</strong> pada filter di atas.</p>
        </div>
    @endif
</div>