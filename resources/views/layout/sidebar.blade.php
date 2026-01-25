<div id="sidebar"
    class="sidebar fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-gray-900 to-gray-800 flex flex-col border-r border-gray-700 shadow-2xl z-40">
    <div class="p-5 border-b border-gray-700 flex-shrink-0">
        <div class="flex items-center space-x-3">
            {{-- Pastikan asset logo benar --}}
            <img src="{{ asset('assets/logo-aba.png') }}" alt="Logo ABA"
                class="w-10 h-10 rounded-lg object-contain shadow-md bg-gray-800">

            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">Monitoring System</h1>
                <p class="text-xs text-gray-400 mt-0.5">Homeschooling Group ABA</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar">
        <nav class="p-4 space-y-0.5">

            {{-- ================================================= --}}
            {{-- 1. DASHBOARD (SEMUA USER) --}}
            {{-- ================================================= --}}
            <a href="/dashboard"
                class="sidebar-menu-item group relative flex items-center p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50"
                data-menu="dashboard">
                <div
                    class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-blue-500/20 to-blue-600/20 flex items-center justify-center group-hover:from-blue-500/30 group-hover:to-blue-600/30">
                    <i class="fas fa-home text-blue-400 text-xs"></i>
                </div>
                <span class="text-sm font-medium text-gray-300 group-hover:text-white">Dashboard</span>
            </a>

            {{-- ================================================= --}}
            {{-- 2. DATA MASTER (HANYA ADMIN & WALI KELAS) --}}
            {{-- ================================================= --}}
            @if(in_array(auth()->user()->role, ['admin', 'wali_kelas']))
                <div class="sidebar-dropdown-group mt-1">
                    <button
                        class="sidebar-dropdown-btn w-full flex items-center justify-between p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50 group">
                        <div class="flex items-center">
                            <div
                                class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 flex items-center justify-center group-hover:from-emerald-500/30 group-hover:to-emerald-600/30">
                                <i class="fas fa-database text-emerald-400 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-300 group-hover:text-white">Data Master</span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-500 text-xs transition-transform duration-300"></i>
                    </button>

                    <div class="sidebar-submenu ml-9 mt-1 space-y-1 hidden">
                        <a href="/master/siswa"
                            class="sidebar-submenu-item flex items-center p-2 rounded transition-all duration-200 hover:bg-gray-800/50"
                            data-menu="siswa">
                            <i class="fas fa-user-graduate text-gray-400 text-xs mr-2.5"></i>
                            <span class="text-xs text-gray-300 hover:text-white">Data Siswa</span>
                        </a>
                        <a href="/master/guru"
                            class="sidebar-submenu-item flex items-center p-2 rounded transition-all duration-200 hover:bg-gray-800/50"
                            data-menu="guru">
                            <i class="fas fa-chalkboard-teacher text-gray-400 text-xs mr-2.5"></i>
                            <span class="text-xs text-gray-300 hover:text-white">Data Guru</span>
                        </a>
                        <a href="/master/kelas"
                            class="sidebar-submenu-item flex items-center p-2 rounded transition-all duration-200 hover:bg-gray-800/50"
                            data-menu="kelas">
                            <i class="fas fa-school text-gray-400 text-xs mr-2.5"></i>
                            <span class="text-xs text-gray-300 hover:text-white">Data Kelas</span>
                        </a>
                        <a href="/master/mapel"
                            class="sidebar-submenu-item flex items-center p-2 rounded transition-all duration-200 hover:bg-gray-800/50"
                            data-menu="mapel">
                            <i class="fas fa-book text-gray-400 text-xs mr-2.5"></i>
                            <span class="text-xs text-gray-300 hover:text-white">Data Mapel</span>
                        </a>
                    </div>
                </div>
            @endif

            {{-- ================================================= --}}
            {{-- 3. FITUR AKADEMIK (SEMUA USER) --}}
            {{-- ================================================= --}}
            <div class="pt-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 px-2.5">Fitur</p>

                <a href="/nilai"
                    class="sidebar-menu-item group relative flex items-center p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50"
                    data-menu="nilai">
                    <div
                        class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-purple-500/20 to-purple-600/20 flex items-center justify-center group-hover:from-purple-500/30 group-hover:to-purple-600/30">
                        <i class="fas fa-graduation-cap text-purple-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Nilai Akademik</span>
                </a>

                <a href="/poin"
                    class="sidebar-menu-item group relative flex items-center p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50"
                    data-menu="poin">
                    <div
                        class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-amber-500/20 to-amber-600/20 flex items-center justify-center group-hover:from-amber-500/30 group-hover:to-amber-600/30">
                        <i class="fas fa-star text-amber-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Poin Kedisiplinan</span>
                </a>

                {{-- ================================================= --}}
             
                {{-- ================================================= --}}

                {{-- A. STATISTIK (SEMUA USER) --}}
                {{-- Logika: Masukkan 'guru' di array ini --}}
                @if(in_array(auth()->user()->role, ['admin', 'wali_kelas', 'guru']))

                    <div class="sidebar-dropdown-group">
                        <button
                            class="sidebar-dropdown-btn w-full flex items-center justify-between p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50 group">
                            <div class="flex items-center">
                                <div
                                    class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-green-500/20 to-green-600/20 flex items-center justify-center group-hover:from-green-500/30 group-hover:to-green-600/30">
                                    <i class="fas fa-chart-line text-green-400 text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-300 group-hover:text-white">Statistik</span>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 text-xs transition-transform duration-300"></i>
                        </button>

                        <div class="sidebar-submenu ml-9 mt-1 space-y-1 hidden">
                            <a href="/statistik/akademik"
                                class="sidebar-submenu-item flex items-center p-2 rounded transition-all duration-200 hover:bg-gray-800/50">
                                <i class="fas fa-chart-bar text-gray-400 text-xs mr-2.5"></i>
                                <span class="text-xs text-gray-300 hover:text-white">Nilai Akademik</span>
                            </a>
                            <a href="/statistik/kedisiplinan"
                                class="sidebar-submenu-item flex items-center p-2 rounded transition-all duration-200 hover:bg-gray-800/50">
                                <i class="fas fa-chart-pie text-gray-400 text-xs mr-2.5"></i>
                                <span class="text-xs text-gray-300 hover:text-white">Poin Kedisiplinan</span>
                            </a>
                        </div>
                    </div>

                @endif


                {{-- B. LAPORAN (HANYA ADMIN & WALI KELAS) --}}
                {{-- Logika: Hapus 'guru' dari array ini --}}
                @if(in_array(auth()->user()->role, ['admin', 'wali_kelas']))
                    <a href="/laporan"
                        class="sidebar-menu-item group relative flex items-center p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50"
                        data-menu="laporan">
                        <div
                            class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-red-500/20 to-red-600/20 flex items-center justify-center group-hover:from-red-500/30 group-hover:to-red-600/30">
                            <i class="fas fa-file-alt text-red-400 text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-300 group-hover:text-white">Laporan</span>
                    </a>
                @endif

            </div>

            <div class="pt-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 px-2.5">Administrasi</p>

                {{-- ================================================= --}}
                {{-- 5. MANAJEMEN USER (HANYA ADMIN) --}}
                {{-- ================================================= --}}
                @if(auth()->user()->role === 'admin')
                    <a href="/user"
                        class="sidebar-menu-item group relative flex items-center p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50"
                        data-menu="user">
                        <div
                            class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-indigo-500/20 to-indigo-600/20 flex items-center justify-center group-hover:from-indigo-500/30 group-hover:to-indigo-600/30">
                            <i class="fas fa-users text-indigo-400 text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-300 group-hover:text-white">Manajemen User</span>
                    </a>
                @endif

                <a href="/tentang"
                    class="sidebar-menu-item group relative flex items-center p-2.5 rounded-lg transition-all duration-200 hover:bg-gray-800/50"
                    data-menu="tentang">
                    <div
                        class="w-7 h-7 mr-2.5 rounded-md bg-gradient-to-r from-cyan-500/20 to-cyan-600/20 flex items-center justify-center group-hover:from-cyan-500/30 group-hover:to-cyan-600/30">
                        <i class="fas fa-info-circle text-cyan-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Tentang Aplikasi</span>
                </a>
            </div>
        </nav>
    </div>

    <div class="p-4 border-t border-gray-700 bg-gray-900/50 flex-shrink-0">
        <div class="flex items-center space-x-2.5">

            <div class="relative">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center shadow">
                    <span class="text-white text-sm font-bold uppercase">
                        {{ substr(auth()->user()->nama ?? 'U', 0, 1) }}
                    </span>
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-green-500 border-2 border-gray-900"
                    title="Online"></div>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">
                    {{ auth()->user()->nama ?? 'Pengguna' }}
                </p>
                <p class="text-xs text-gray-400 truncate">
                    {{ ucfirst(auth()->user()->role ?? 'User') }}
                </p>
            </div>

            <form action="/logout" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="p-1.5 rounded hover:bg-gray-800 transition-all duration-200 group flex items-center justify-center"
                    title="Keluar dari sistem">
                    <i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-400 text-xs"></i>
                </button>
            </form>
        </div>

        <div class="mt-3 pt-2 border-t border-gray-800">
            <p class="text-xs text-gray-500 text-center">
                &copy; {{ date('Y') }} Homeschooling ABA
            </p>
        </div>
    </div>
</div>
<div class="ml-64">

    {{-- Main Content akan di-render di sini oleh Layout Utama --}}

</div>



<style>
    /* CSS Styling Sidebar (Sama seperti sebelumnya) */

    .sidebar {
        background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
    }

    .sidebar-menu-item.active {
        background: rgba(59, 130, 246, 0.12);
        border-left: 3px solid #3b82f6;
    }

    .sidebar-menu-item.active .text-gray-300 {
        color: white;
        font-weight: 500;
    }

    .sidebar-menu-item.active div {
        background: linear-gradient(to right, rgba(59, 130, 246, 0.25), rgba(59, 130, 246, 0.35));
    }

    .sidebar-menu-item.active i {
        color: #60a5fa;
    }

    .sidebar-dropdown-btn.active {
        background: rgba(31, 41, 55, 0.6);
    }

    .sidebar-dropdown-btn.active i {
        transform: rotate(180deg);
        color: #9ca3af;
    }

    .sidebar-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-submenu.open {
        max-height: 180px;
    }

    .sidebar-submenu-item.active {
        background: rgba(59, 130, 246, 0.12);
    }

    .sidebar-submenu-item.active span {
        color: white;
        font-weight: 500;
    }

    .sidebar-submenu-item.active i {
        color: #60a5fa;
    }

    .sidebar-menu-item,
    .sidebar-submenu-item {
        transition: all 0.2s ease;
    }

    .sidebar::-webkit-scrollbar {
        width: 3px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(31, 41, 55, 0.5);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(75, 85, 99, 0.5);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(107, 114, 128, 0.7);
    }

    @media (max-width: 768px) {

        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .ml-64 {
            margin-left: 0;
        }

    }
</style>



{{-- SCRIPT JAVASCRIPT --}}

{{-- Anda tidak perlu mengubah JS Anda yang lama, karena script tersebut sudah otomatis

mendeteksi link biasa vs link dropdown. Tapi jika ingin memastikan, pakai yg ini --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {



        // 1. LOGIC DROPDOWN

        const dropdownBtns = document.querySelectorAll('.sidebar-dropdown-btn');

        dropdownBtns.forEach(btn => {

            btn.addEventListener('click', function (e) {

                e.preventDefault();

                const parent = this.closest('.sidebar-dropdown-group');

                const submenu = parent.querySelector('.sidebar-submenu');

                const icon = this.querySelector('.fa-chevron-down');



                // Accordion effect (Tutup yang lain)

                document.querySelectorAll('.sidebar-dropdown-group').forEach(group => {

                    if (group !== parent) {

                        const otherSub = group.querySelector('.sidebar-submenu');

                        const otherBtn = group.querySelector('.sidebar-dropdown-btn');

                        const otherIcon = group.querySelector('.fa-chevron-down');

                        if (otherSub) { otherSub.classList.add('hidden'); otherSub.classList.remove('open'); }

                        if (otherBtn) otherBtn.classList.remove('active');

                        if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';

                    }

                });



                // Toggle current

                if (submenu.classList.contains('hidden')) {

                    submenu.classList.remove('hidden'); submenu.classList.add('open');

                    this.classList.add('active');

                    if (icon) icon.style.transform = 'rotate(180deg)';

                } else {

                    submenu.classList.add('hidden'); submenu.classList.remove('open');

                    this.classList.remove('active');

                    if (icon) icon.style.transform = 'rotate(0deg)';

                }

            });

        });



        // 2. ACTIVE STATE

        function setActiveMenu() {

            const currentPath = window.location.pathname;

            const menuItems = document.querySelectorAll('.sidebar-menu-item, .sidebar-submenu-item');



            menuItems.forEach(item => {

                item.classList.remove('active');

                const href = item.getAttribute('href');



                if (href && href !== '#' && currentPath.includes(href)) {

                    item.classList.add('active');

                    if (item.classList.contains('sidebar-submenu-item')) {

                        const parentGroup = item.closest('.sidebar-dropdown-group');

                        if (parentGroup) {

                            const parentBtn = parentGroup.querySelector('.sidebar-dropdown-btn');

                            const parentSub = parentGroup.querySelector('.sidebar-submenu');

                            const parentIcon = parentBtn.querySelector('.fa-chevron-down');

                            if (parentSub && parentBtn) {

                                parentSub.classList.remove('hidden'); parentSub.classList.add('open');

                                parentBtn.classList.add('active');

                                if (parentIcon) parentIcon.style.transform = 'rotate(180deg)';

                            }

                        }

                    }

                }

            });

        }

        setActiveMenu();



        // 3. MOBILE TOGGLE

        const sidebar = document.getElementById('sidebar');

        document.addEventListener('click', function (e) {

            if (e.target.closest('.mobile-menu-button')) {

                sidebar.classList.toggle('-translate-x-full');

            }

        });

    });

</script>