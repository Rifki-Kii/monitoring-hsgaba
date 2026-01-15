<div class="container mx-auto px-4 py-6">
    
    {{-- HEADER & FILTER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Kelas</h1>
            <p class="text-gray-500 text-sm">Kelola data kelas dan wali kelas</p>
        </div>
        
        <div class="flex gap-2">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Kelas..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </button>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif

    {{-- TABEL DATA KELAS --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold leading-normal">
                <tr>
                    <th class="py-3 px-6 border-b">Nama Kelas</th>
                    <th class="py-3 px-6 border-b">Jenjang</th>
                    <th class="py-3 px-6 border-b">Wali Kelas</th>
                    <th class="py-3 px-6 border-b text-center">Jumlah Siswa</th>
                    <th class="py-3 px-6 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                {{-- PERBAIKAN DISINI: Menggunakan $kelases --}}
                @forelse($kelases as $kelas)
                    <tr class="border-b border-gray-100 hover:bg-blue-50/50 transition duration-150">
                        <td class="py-3 px-6 font-bold text-gray-800">{{ $kelas->nama_kelas }}</td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $kelas->jenjang == 'SMP' ? 'bg-indigo-100 text-indigo-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $kelas->jenjang }}
                            </span>
                        </td>
                        <td class="py-3 px-6">{{ $kelas->waliKelas->nama_guru ?? '-' }}</td>
                        
                        {{-- TOMBOL LIHAT SISWA (POPUP) --}}
                        <td class="py-3 px-6 text-center">
                            @php $count = $kelas->siswas_count ?? 0; @endphp
                            
                            @if($count > 0)
                                <button wire:click="showStudentList({{ $kelas->id }})" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 hover:bg-blue-200 transition cursor-pointer group shadow-sm border border-blue-200">
                                    <i class="fas fa-users mr-1.5 text-blue-500 group-hover:text-blue-700"></i>
                                    {{ $count }} Siswa
                                </button>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-200">
                                    0 Siswa
                                </span>
                            @endif
                        </td>

                        {{-- AKSI EDIT/HAPUS --}}
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center gap-2">
                                <button wire:click="edit({{ $kelas->id }})" class="w-8 h-8 rounded bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition flex items-center justify-center" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $kelas->id }})" wire:confirm="Hapus kelas {{ $kelas->nama_kelas }}?" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition flex items-center justify-center" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Data kelas belum tersedia.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kelases->links() }}
    </div>


    {{-- ========================================================== --}}
    {{-- MODAL CRUD KELAS (Tambah / Edit) --}}
    {{-- ========================================================== --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md relative animate-fade-in-up">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $isEditMode ? 'Edit Kelas' : 'Tambah Kelas Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            
            <form wire:submit.prevent="store">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kelas</label>
                        <input wire:model="nama_kelas" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: 1A, 7B">
                        @error('nama_kelas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenjang</label>
                        <select wire:model="jenjang" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Pilih Jenjang --</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                        </select>
                        @error('jenjang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Wali Kelas (Guru)</label>
                        <select wire:model="wali_guru_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                        @error('wali_guru_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-end gap-2 rounded-b-lg">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm font-bold hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    {{-- ========================================================== --}}
    {{-- MODAL POPUP: LIST SISWA PER KELAS --}}
    {{-- ========================================================== --}}
    @if($isStudentListOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg relative overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up">
            
            {{-- Header Modal --}}
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white shrink-0">
                <div>
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-2"></i> Kelas {{ $selectedClassName }}
                    </h3>
                    <p class="text-blue-100 text-xs mt-0.5">Daftar siswa yang terdaftar di kelas ini</p>
                </div>
                <button wire:click="closeStudentList" class="text-white hover:text-red-200 transition text-xl focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body List Siswa --}}
            <div class="overflow-y-auto p-0 flex-1">
                @if(count($selectedStudents) > 0)
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b sticky top-0">
                            <tr>
                                <th class="px-5 py-3 w-16">No</th>
                                <th class="px-5 py-3">NIS</th>
                                <th class="px-5 py-3">Nama Lengkap</th>
                                <th class="px-5 py-3 text-center">L/P</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($selectedStudents as $index => $siswa)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3 font-mono text-gray-600">{{ $siswa->nis }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $siswa->nama }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                            {{ $siswa->jenis_kelamin }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-10 text-center text-gray-500">
                        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-user-slash text-2xl text-gray-400"></i>
                        </div>
                        <p class="font-medium">Belum ada siswa di kelas ini.</p>
                    </div>
                @endif
            </div>

            {{-- Footer Modal --}}
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-between items-center shrink-0">
                <span class="text-xs text-gray-500 font-bold">Total: {{ count($selectedStudents) }} Siswa</span>
                <button wire:click="closeStudentList" class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 text-xs font-bold hover:bg-gray-100 transition shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

</div>