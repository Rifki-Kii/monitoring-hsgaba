<div>
    <div class="container mx-auto px-1 py-1">
        <div class="bg-white rounded-lg shadow-lg p-6">

          

            {{-- FILTER SECTION --}}
            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end mb-4">
                    
                    {{-- Search --}}
                    <div class="md:col-span-4">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Cari Data</label>
                        {{-- Ganti name="search" dengan wire:model.live --}}
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Cari NIS atau Nama..."
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>

                    {{-- Filter JK --}}
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Jenis Kelamin</label>
                        <select wire:model.live="filter_jk"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    {{-- Filter Kelas --}}
                    <div class="md:col-span-3">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Kelas</label>
                        <select wire:model.live="filter_kelas"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Reset --}}
                    <div class="md:col-span-3">
                         {{-- Tombol Filter dihapus karena sudah Realtime, sisa Reset --}}
                        <button wire:click="$set('search', ''); $set('filter_jk', ''); $set('filter_kelas', '');"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 w-full text-center shadow-sm">
                            Reset Filter
                        </button>
                    </div>
                </div>

                {{-- Export Buttons (Perlu mengirim parameter search ke controller) --}}
                <div class="border-t border-gray-200 pt-3 flex flex-col md:flex-row justify-end items-center gap-2">
                   
                    <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm  shadow transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Siswa
                </button>
                    <a href="{{ route('siswa.export.excel', ['search' => $search, 'jk' => $filter_jk, 'kelas_id' => $filter_kelas]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm flex items-center shadow transition">
                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </a>
                    <a href="{{ route('siswa.export.pdf', ['search' => $search, 'jk' => $filter_jk, 'kelas_id' => $filter_kelas]) }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm flex items-center shadow transition">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </a>
                </div>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="w-full text-left border-collapse bg-white">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 border-b">NIS</th>
                            <th class="py-3 px-6 border-b">Nama Lengkap</th>
                            <th class="py-3 px-6 border-b">L/P</th>
                            <th class="py-3 px-6 border-b">Kelas</th>
                            <th class="py-3 px-6 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse($siswas as $siswa)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                <td class="py-3 px-6 font-medium">{{ $siswa->nis }}</td>
                                <td class="py-3 px-6 font-medium">{{ $siswa->nama }}</td>
                                <td class="py-3 px-6">
                                    <span class="{{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }} py-1 px-3 rounded-full text-xs font-semibold">
                                        {{ $siswa->jenis_kelamin }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 font-medium">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center gap-2">
                                        {{-- Detail Button --}}
                                        <button wire:click="detail({{ $siswa->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition shadow-sm" title="Detail">
                                            <i class="fas fa-edit"></i> Detail dan edit
                                        </button>
                                        {{-- Edit Button --}}
                                        {{-- <button wire:click="edit({{ $siswa->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 rounded transition shadow-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button> --}}
                                        {{-- Delete Button --}}
                                        <button wire:click="delete({{ $siswa->id }})" wire:confirm="Yakin ingin menghapus data {{ $siswa->nama }}?" class="bg-red-600 hover:bg-red-600 text-white px-6 py-2 rounded transition shadow-sm" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-gray-500 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-medium">Data tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $siswas->links() }}
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MODAL SECTION (ADD / EDIT) --}}
    {{-- ========================================================== --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 my-8 relative">
            
            {{-- Tombol Close --}}
            <button wire:click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div class="p-4 border-b">
                <h3 class="text-xl font-bold text-gray-800">
                    {{ $isEditMode ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}
                </h3>
            </div>
            
            <form wire:submit.prevent="store">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Form Fields --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIS</label>
                        <input wire:model="nis" type="number" class="w-full border rounded px-3 py-2">
                        @error('nis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                        <input wire:model="nama" type="text" class="w-full border rounded px-3 py-2">
                        @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                        <select wire:model="kelas_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                        </select>
                        @error('kelas_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                         @error('jenis_kelamin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Detail Fields --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tempat Lahir</label>
                        <input wire:model="tempat_lahir" type="text" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir</label>
                        <input wire:model="tanggal_lahir" type="date" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="md:col-span-2">
                         <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                         <textarea wire:model="alamat" class="w-full border rounded px-3 py-2" rows="2"></textarea>
                    </div>
                     <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ayah</label>
                        <input wire:model="nama_ayah" type="text" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ibu</label>
                        <input wire:model="nama_ibu" type="text" class="w-full border rounded px-3 py-2">
                    </div>
                    
                    {{-- Foto Upload --}}
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto</label>
                        <input wire:model="foto" type="file" class="w-full text-sm text-gray-500">
                        <div wire:loading wire:target="foto" class="text-xs text-blue-500">Mengupload...</div>
                        
                        @if ($foto)
                            {{-- Preview Foto Baru --}}
                            <img src="{{ $foto->temporaryUrl() }}" class="mt-2 h-20 w-20 object-cover rounded border">
                        @elseif($foto_lama)
                            {{-- Preview Foto Lama --}}
                            <img src="{{ asset('storage/'.$foto_lama) }}" class="mt-2 h-20 w-20 object-cover rounded border">
                        @endif
                        @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end p-4 border-t bg-gray-50 rounded-b-lg">
                    <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        {{ $isEditMode ? 'Update Data' : 'Simpan Data' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- MODAL DETAIL --}}
    {{-- ========================================================== --}}
   {{-- ========================================================== --}}
    {{-- MODAL DETAIL (VIEW & EDIT INLINE)                          --}}
    {{-- ========================================================== --}}
 {{-- ========================================================== --}}
    {{-- MODAL DETAIL (VERSI OPTIMASI PERFORMA)                     --}}
    {{-- ========================================================== --}}
    @if($isDetailOpen)
    {{-- HAPUS 'backdrop-blur-sm' AGAR TIDAK BERAT --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 overflow-y-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative flex flex-col max-h-[95vh]">
            
            {{-- Tombol Close --}}
            <button wire:click="closeModal" class="absolute top-4 right-4 z-10 bg-gray-100 hover:bg-red-100 text-gray-500 hover:text-red-500 rounded-full p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            {{-- CONTENT --}}
            <div class="overflow-y-auto p-6 md:p-8">
                
                {{-- MODE VIEW (LIHAT DATA) --}}
                @if(!$isDetailEditMode)
                    <div class="flex flex-col md:flex-row gap-8">
                        {{-- Kiri: Foto --}}
                        <div class="w-full md:w-1/3 flex flex-col items-center border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0 md:pr-6">
                            @if($foto_lama)
                                <img src="{{ asset('storage/'.$foto_lama) }}" class="w-40 h-40 rounded-lg object-cover shadow border-4 border-white">
                            @else
                                <div class="w-40 h-40 rounded-lg bg-gray-100 flex items-center justify-center shadow text-gray-300">
                                    <i class="fas fa-user text-6xl"></i>
                                </div>
                            @endif
                            
                            <h2 class="text-2xl font-bold text-gray-800 text-center mt-3">{{ $nama }}</h2>
                            <p class="text-gray-500 text-sm">NIS: {{ $nis }}</p>

                            <button wire:click="toggleDetailEditMode" class="mt-6 w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg shadow font-bold flex items-center justify-center gap-2 transition">
                                <i class="fas fa-pen"></i> Edit Biodata
                            </button>
                        </div>

                        {{-- Kanan: Info --}}
                        <div class="w-full md:w-2/3 space-y-4">
                            <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Informasi Lengkap</h3>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="block text-xs font-bold text-gray-400">KELAS</span> <span class="font-semibold">{{ $kelas->find($kelas_id)->nama_kelas ?? '-' }}</span></div>
                                <div><span class="block text-xs font-bold text-gray-400">JENIS KELAMIN</span> <span>{{ $jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                                <div><span class="block text-xs font-bold text-gray-400">TEMPAT LAHIR</span> <span>{{ $tempat_lahir ?: '-' }}</span></div>
                                <div><span class="block text-xs font-bold text-gray-400">TANGGAL LAHIR</span> <span>{{ $tanggal_lahir ? date('d-m-Y', strtotime($tanggal_lahir)) : '-' }}</span></div>
                                <div class="col-span-2"><span class="block text-xs font-bold text-gray-400">ALAMAT</span> <p class="bg-gray-50 p-2 rounded border border-gray-100">{{ $alamat ?: '-' }}</p></div>
                                <div><span class="block text-xs font-bold text-gray-400">AYAH</span> <span>{{ $nama_ayah ?: '-' }}</span></div>
                                <div><span class="block text-xs font-bold text-gray-400">IBU</span> <span>{{ $nama_ibu ?: '-' }}</span></div>
                            </div>
                        </div>
                    </div>

                {{-- MODE EDIT (FORMULIR RINGAN) --}}
                @else
                    <form wire:submit.prevent="store">
                        <div class="flex flex-col md:flex-row gap-8">
                            
                            {{-- Upload Foto --}}
                            <div class="w-full md:w-1/3 flex flex-col items-center">
                                <label class="cursor-pointer group relative w-40 h-40 mb-2">
                                    {{-- Preview Logic --}}
                                    @if ($foto)
                                        <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover rounded-lg border-4 border-blue-500">
                                    @elseif($foto_lama)
                                        <img src="{{ asset('storage/'.$foto_lama) }}" class="w-full h-full object-cover rounded-lg border-4 border-gray-200">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-300 rounded-lg">
                                            <i class="fas fa-camera text-3xl"></i>
                                            <span class="text-xs mt-1">Upload</span>
                                        </div>
                                    @endif

                                    {{-- Loading Overlay (PENTING AGAR TIDAK DIKIRA MACET) --}}
                                    <div wire:loading.flex wire:target="foto" class="absolute inset-0 bg-white bg-opacity-80 flex-col items-center justify-center z-20">
                                        <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                                        <span class="text-xs font-bold text-blue-600 mt-1">Loading...</span>
                                    </div>

                                    <input wire:model="foto" type="file" class="hidden" accept="image/*">
                                </label>
                                <p class="text-xs text-center text-gray-500">Klik foto untuk mengganti</p>
                            </div>

                            {{-- Form Input (Gunakan .blur agar tidak berat) --}}
                            <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="md:col-span-2 border-b pb-1 mb-2 font-bold text-gray-700">Identitas</div>
                                
                                <div>
                                    <label class="text-xs font-bold text-gray-500">NIS</label>
                                    <input wire:model.blur="nis" type="number" class="w-full border rounded p-2 text-sm">
                                    @error('nis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Nama</label>
                                    <input wire:model.blur="nama" type="text" class="w-full border rounded p-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Kelas</label>
                                    <select wire:model.blur="kelas_id" class="w-full border rounded p-2 text-sm">
                                        @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">L/P</label>
                                    <select wire:model.blur="jenis_kelamin" class="w-full border rounded p-2 text-sm">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2 border-b pb-1 mb-2 mt-2 font-bold text-gray-700">Pribadi</div>

                                <div>
                                    <label class="text-xs font-bold text-gray-500">Tempat Lahir</label>
                                    <input wire:model.blur="tempat_lahir" type="text" class="w-full border rounded p-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Tanggal Lahir</label>
                                    <input wire:model.blur="tanggal_lahir" type="date" class="w-full border rounded p-2 text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-gray-500">Alamat</label>
                                    <textarea wire:model.blur="alamat" rows="2" class="w-full border rounded p-2 text-sm"></textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Ayah</label>
                                    <input wire:model.blur="nama_ayah" type="text" class="w-full border rounded p-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Ibu</label>
                                    <input wire:model.blur="nama_ibu" type="text" class="w-full border rounded p-2 text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Footer Tombol --}}
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="toggleDetailEditMode" class="px-4 py-2 rounded text-gray-600 bg-gray-100 hover:bg-gray-200 font-bold text-sm">
                                Batal
                            </button>
                            
                            {{-- Tombol Simpan dengan Loading State --}}
                            <button type="submit" 
                                wire:loading.attr="disabled" 
                                class="px-6 py-2 rounded text-white bg-blue-600 hover:bg-blue-700 font-bold text-sm shadow flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="foto, store">Simpan</span>
                                <span wire:loading wire:target="foto, store"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>