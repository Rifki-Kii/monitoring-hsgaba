@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-2">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-gray-600 mt-1">Kelola data pengguna sistem</p>
        </div>
        <button onclick="openAddModal()"
            class="mt-4 md:mt-0 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition duration-200 shadow-sm hover:shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah User
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Username</th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $u)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ substr($u->nama, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $u->nama }}</div>
                                    @if($u->nomor_hp)
                                    <div class="text-sm text-gray-500">{{ $u->nomor_hp }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                @<span>{{ $u->username }}</span>
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'user' => 'bg-green-100 text-green-800',
                                    'editor' => 'bg-blue-100 text-blue-800',
                                ];
                                $color = $roleColors[$u->role] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <button onclick="openEditModal({{ $u }})"
                                    class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-medium text-sm transition duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                
                                <span class="text-gray-300">|</span>
                                
                                <form action="/user/{{ $u->id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                        class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-800 font-medium text-sm transition duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        @if($users->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.137a4 4 0 01-3.67 2.363"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada user</h3>
            <p class="mt-1 text-gray-500">Mulai dengan menambahkan user baru</p>
            <button onclick="openAddModal()"
                class="mt-4 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah User Pertama
            </button>
        </div>
        @endif
    </div>
</div>

@include('user.modal-add')
@include('user.modal-edit')

<script>
function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openEditModal(user) {
    document.getElementById('edit_id').value = user.id
    document.getElementById('edit_nama').value = user.nama
    document.getElementById('edit_role').value = user.role
    document.getElementById('edit_nomor_hp').value = user.nomor_hp ?? ''

    document.getElementById('modalEdit').classList.remove('hidden')
}


function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modals = ['modalAdd', 'modalEdit'];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'modalAdd') closeAddModal();
                    else closeEditModal();
                }
            });
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('modalAdd').classList.contains('hidden')) closeAddModal();
            if (!document.getElementById('modalEdit').classList.contains('hidden')) closeEditModal();
        }
    });
});
</script>

<style>
/* Smooth transitions */
.modal-transition {
    transition: all 0.3s ease-out;
}

/* Better table hover effect */
tbody tr {
    transition: background-color 0.2s ease;
}

/* Modal backdrop blur effect */
.fixed.inset-0 {
    backdrop-filter: blur(2px);
}
</style>
@endsection