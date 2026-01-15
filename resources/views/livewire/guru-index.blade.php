<div>
    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- FORM INPUT (KIRI) --}}
        <div class="bg-white shadow rounded-lg p-4 h-fit">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">
                {{ $isEdit ? 'Edit Data Guru' : 'Tambah Guru Baru' }}
            </h2>
            {{-- Formnya sama persis seperti sebelumnya, saya persingkat di sini --}}
           
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <input wire:model="nama_guru" type="text" class="w-full border rounded p-2"
                            placeholder="Nama Lengkap Guru">
                        @error('nama_guru') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- NIP --}}
                    <div class="mb-3">
                        <input wire:model="nip" type="number" class="w-full border rounded p-2"
                            placeholder="NIP (Nomor Induk Pegawai)">
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <input wire:model="email" type="email" class="w-full border rounded p-2"
                            placeholder="Alamat Email">
                    </div>

                    {{-- No HP --}}
                    <div class="mb-3">
                        <input wire:model="no_hp" type="number" class="w-full border rounded p-2"
                            placeholder="Nomor Handphone">
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <textarea wire:model="alamat" class="w-full border rounded p-2"
                            placeholder="Alamat Lengkap Domisili"></textarea>
                    </div>

                    {{-- Jenis Kelamin (Placeholder ada di opsi pertama) --}}
                    <div class="mb-3">
                        <select wire:model="jenis_kelamin"
                            class="w-full border rounded p-2 text-gray-600 focus:text-black">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    {{-- Mapel (Checkbox) --}}
                    <div class="mb-4 border p-2 rounded relative">
                        <div class="text-xs text-gray-400 mb-2 font-semibold">Pilih Mata Pelajaran yang diampu:</div>
                        <div class="max-h-40 overflow-y-auto">
                            @foreach($all_mapels as $mapel)
                                <div class="flex items-center mb-1">
                                    <input wire:model="selected_mapels" value="{{ $mapel->id }}" type="checkbox"
                                        class="mr-2 cursor-pointer">
                                    <span class="text-sm text-gray-700">{{ $mapel->nama_mapel }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded w-full shadow transition duration-200">
                        {{ $isEdit ? 'Update Data' : 'Simpan Data' }}
                    </button>

                    @if($isEdit)
                        <button wire:click="cancel" type="button"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold px-4 py-2 rounded w-full mt-2 shadow transition duration-200">
                            Batal
                        </button>
                    @endif

                </form>

        </div>

        {{-- TABEL DATA (KANAN) --}}
        <div class="lg:col-span-2 bg-white shadow rounded-lg p-4">

            <div class="flex flex-col gap-4 mb-4">
                <h2 class="text-xl font-bold text-gray-800">Daftar Guru</h2>

                {{-- TOOLBAR PENCARIAN & FILTER --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50 p-3 rounded border">

                    {{-- 1. Input Search (Nama / NIP) --}}
                    <div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="w-full border rounded px-3 py-2 text-sm focus:ring-blue-500"
                            placeholder="Cari Nama / NIP...">
                    </div>

                    {{-- 2. Filter Mapel --}}
                    <div>
                        <select wire:model.live="filter_mapel" class="w-full border rounded px-3 py-2 text-sm">
                            <option value="">-- Semua Mapel --</option>
                            @foreach($all_mapels as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Filter Wali Kelas --}}
                    <div>
                        <select wire:model.live="filter_kelas" class="w-full border rounded px-3 py-2 text-sm">
                            <option value="">-- Semua Wali Kelas --</option>
                            @foreach($all_kelas as $k)
                                <option value="{{ $k->id }}">Wali Kelas {{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-3">Identitas</th>
                            <th class="px-4 py-3">Mengajar</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $guru)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 align-top max-w-50">
                                    <div class="font-bold text-gray-900">{{ $guru->nama_guru }}</div>
                                    <div class="text-xs text-gray-500">NIP: {{ $guru->nip ?? '-' }}</div>
                                    <span
                                        class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $guru->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $guru->jenis_kelamin == 'L' ? 'L' : 'P' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 align-top">
                                    {{-- Status Wali Kelas --}}
                                    <div class="mb-2">
                                        @if($guru->waliKelas)
                                            <span
                                                class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded font-bold border border-green-200">
                                                Wali Kelas {{ $guru->waliKelas->nama_kelas }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Daftar Mapel --}}
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($guru->mapels as $m)
                                            <span
                                                class="bg-indigo-50 text-indigo-700 text-[10px] px-2 py-1 rounded border border-indigo-100">
                                                {{ $m->nama_mapel }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 italic text-xs">-</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-center align-top">
                                    <div class="flex flex-col gap-1">
                                        <button wire:click="edit({{ $guru->id }})"
                                            class="text-yellow-600 hover:text-yellow-800 text-xs font-bold">Edit</button>
                                        <button wire:click="delete({{ $guru->id }})" wire:confirm="Hapus?"
                                            class="text-red-600 hover:text-red-800 text-xs font-bold">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400">
                                    Data tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $gurus->links() }}</div>
        </div>
    </div>
</div>