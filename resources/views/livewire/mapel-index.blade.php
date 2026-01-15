<div>
    <div class="container mx-auto px-2 py-2">
        <div class="bg-white rounded-lg shadow-lg p-6">

            {{-- HEADER & SEARCH --}}
           {{-- HEADER & SEARCH --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Mata Pelajaran</h1>

                <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                    
                  

                    {{-- Search --}}
                    <div class="relative w-full md:w-64">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Cari Mapel / Kode...">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                      {{-- FILTER KATEGORI (BARU) --}}
                    <div class="w-full md:w-48">
                        <select wire:model.live="filterKategori" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-700 cursor-pointer">
                            <option value="">Semua Kategori</option>
                            <option value="Tsaqafah Islam">Tsaqafah Islam</option>
                            <option value="Pengetahuan Umum">Pengetahuan Umum</option>
                            <option value="Keterampilan">Keterampilan</option>
                        </select>
                    </div>

                    {{-- Tombol Tambah --}}
                    <button wire:click="create"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
            </div>

            {{-- FLASH MESSAGE --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
           {{-- TABLE --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="w-full text-left border-collapse bg-white">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-xs font-bold tracking-wider">
                            <th class="py-3 px-6 border-b text-center">Kode</th>
                            <th class="py-3 px-6 border-b">Mata Pelajaran</th>
                            {{-- URUTAN BARU: Pengajar dulu, baru KKM --}}
                            <th class="py-3 px-6 border-b">Pengajar</th> 
                            <th class="py-3 px-6 border-b text-center">KKM</th>
                            <th class="py-3 px-6 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse($mapels as $m)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                
                                {{-- 1. KODE --}}
                                <td class="py-3 px-6 text-center font-mono font-bold text-blue-600">
                                    {{ $m->kode_mapel }}
                                </td>

                                {{-- 2. NAMA MAPEL --}}
                                <td class="py-3 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-base">{{ $m->nama_mapel }}</span>
                                        <span class="mt-1 w-fit px-2 py-0.5 rounded text-[10px] font-bold border 
                                            @if($m->kategori == 'Tsaqafah Islam') bg-green-100 text-green-800 border-green-200
                                            @elseif($m->kategori == 'Pengetahuan Umum') bg-blue-100 text-blue-800 border-blue-200
                                            @else bg-orange-100 text-orange-800 border-orange-200 @endif">
                                            {{ $m->kategori }}
                                        </span>
                                    </div>
                                </td>

                                {{-- 3. PENGAJAR (Sudah dipindah ke sini) --}}
                                <td class="py-3 px-6 align-top">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($m->gurus as $guru)
                                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-full px-2 py-1">
                                                <div class="w-4 h-4 rounded-full bg-blue-100 flex items-center justify-center text-[8px] text-blue-600 mr-1 font-bold">
                                                    {{ substr($guru->nama_guru, 0, 1) }}
                                                </div>
                                                <span class="text-[10px] font-medium text-gray-700">
                                                    {{ Str::limit($guru->nama_guru, 15) }}
                                                </span>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 text-xs italic">- Belum ada -</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- 4. KKM (Sudah dipindah ke sini) --}}
                                <td class="py-3 px-6 text-center">
                                    <span class="font-bold text-gray-700">{{ $m->kkm }}</span>
                                </td>
                                
                                {{-- 5. AKSI --}}
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center gap-2">
                                        <button wire:click="edit({{ $m->id }})"
                                            class="group relative w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition duration-200 shadow-sm border border-yellow-100">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>
                                        <button wire:click="delete({{ $m->id }})"
                                            wire:confirm="Hapus Mapel {{ $m->nama_mapel }}?"
                                            class="group relative w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition duration-200 shadow-sm border border-red-100">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-book-open text-4xl mb-2 text-gray-300"></i>
                                        <p>Belum ada data mata pelajaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $mapels->links() }}</div>
        </div>
    </div>

    {{-- MODAL FORM (ADD / EDIT) --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg animate-fade-in-down">

                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $isEditMode ? 'Edit Mapel' : 'Tambah Mapel Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="p-6 space-y-4">

                        {{-- Kode Mapel --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode Mapel</label>
                            <input wire:model="kode_mapel" type="text"
                                class="w-full border rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 font-mono"
                                placeholder="Contoh: MTK-01">
                            @error('kode_mapel') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nama Mapel --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Mata Pelajaran</label>
                            <input wire:model="nama_mapel" type="text"
                                class="w-full border rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: Matematika">
                            @error('nama_mapel') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Kategori --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori</label>
                                <select wire:model="kategori"
                                    class="w-full border rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Tsaqafah Islam">Tsaqafah Islam</option>
                                    <option value="Pengetahuan Umum">Pengetahuan Umum</option>
                                    <option value="Keterampilan">Keterampilan</option>
                                </select>
                                @error('kategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- KKM --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">KKM</label>
                                <input wire:model="kkm" type="number" min="0" max="100"
                                    class="w-full border rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="75">
                                @error('kkm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end p-4 border-t bg-gray-50 rounded-b-lg gap-2">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-bold text-sm">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold text-sm shadow">
                            {{ $isEditMode ? 'Simpan Perubahan' : 'Simpan Data' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>