<nav class="w-full bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm">
    <!-- Left Section: Mobile Toggle & Breadcrumb -->
    <div class="flex items-center space-x-4">
        <!-- Mobile Menu Toggle -->
        <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
            onclick="document.getElementById('sidebar').classList.toggle('hidden')">
            <i class="fas fa-bars text-gray-700 text-lg"></i>
        </button>

        <!-- Breadcrumb -->
        <div class="hidden md:flex items-center space-x-2">
            <a href="/dashboard" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-home text-xs"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            <span class="text-sm font-medium text-gray-700">{{ $title ?? 'Dashboard' }}</span>
        </div>

        <!-- Page Title for Mobile -->
        <h1 class="md:hidden text-lg font-semibold text-gray-900 truncate max-w-[180px]">
            {{ $title ?? 'Dashboard' }}
        </h1>
    </div>

    <!-- Center Section: Page Title for Desktop -->
    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2">
        <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard Monitoring' }}</h1>
    </div>

    <!-- Right Section: User & Actions -->
    <div class="flex items-center space-x-4">
        <!-- Notification Bell -->
        <div class="relative">
            <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                onclick="toggleNotifications()">
                <i class="fas fa-bell text-gray-600 group-hover:text-gray-800 text-lg"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            </button>

            <!-- Notifications Dropdown -->
            <div id="notifications-dropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-40">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">3 baru</span>
                    </div>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    <!-- Notification Item -->
                    <div class="p-3 border-b border-gray-50 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Sistem diperbarui</p>
                                <p class="text-xs text-gray-500 mt-1">Versi terbaru telah diinstal</p>
                                <p class="text-xs text-gray-400 mt-1">1 jam lalu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Item -->
                    <div class="p-3 border-b border-gray-50 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-user-plus text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">User baru</p>
                                <p class="text-xs text-gray-500 mt-1">Guru baru ditambahkan ke sistem</p>
                                <p class="text-xs text-gray-400 mt-1">3 jam lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 border-t border-gray-100 text-center">
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat semua</a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button class="flex items-center space-x-3 p-1 rounded-lg hover:bg-gray-100 transition-colors group"
                onclick="toggleUserMenu()">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white text-sm font-medium">
                    {{ substr(auth()->user()->nama, 0, 1) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->nama }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <i class="fas fa-chevron-down text-gray-500 text-xs hidden md:block group-hover:text-gray-700"></i>
            </button>

            <!-- User Dropdown Menu -->
            <div id="user-dropdown"
                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 z-40">
                <div class="p-4 border-b border-gray-100">
                    <p class="font-medium text-gray-900">{{ auth()->user()->nama }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email ?? 'admin@homeschooling-aba.com' }}</p>
                </div>

                <div class="py-2">
                    <a href="/profile" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-user text-gray-500 w-4"></i>
                        <span>Profil Saya</span>
                    </a>

                    <a href="/settings" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-cog text-gray-500 w-4"></i>
                        <span>Pengaturan</span>
                    </a>

                    <div class="border-t border-gray-100 my-1"></div>

                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center space-x-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt text-red-500 w-4"></i>
                        <span>Keluar</span>
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Custom styles for navbar */
    #notifications-dropdown,
    #user-dropdown {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }
</style>

<script>
    // Toggle functions
    function toggleNotifications() {
        const dropdown = document.getElementById('notifications-dropdown');
        const userDropdown = document.getElementById('user-dropdown');

        dropdown.classList.toggle('hidden');
        userDropdown.classList.add('hidden');

        // Close dropdown when clicking outside
        document.addEventListener('click', function closeDropdown(e) {
            if (!dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleNotifications()"]')) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', closeDropdown);
            }
        });
    }

    function toggleUserMenu() {
        const dropdown = document.getElementById('user-dropdown');
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        dropdown.classList.toggle('hidden');
        notificationsDropdown.classList.add('hidden');

        // Close dropdown when clicking outside
        document.addEventListener('click', function closeDropdown(e) {
            if (!dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleUserMenu()"]')) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', closeDropdown);
            }
        });
    }

    // Close dropdowns when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('notifications-dropdown').classList.add('hidden');
            document.getElementById('user-dropdown').classList.add('hidden');
        }
    });

    // Auto-close dropdowns after 10 seconds if open
    setInterval(() => {
        const notifications = document.getElementById('notifications-dropdown');
        const userMenu = document.getElementById('user-dropdown');

        if (!notifications.classList.contains('hidden')) {
            notifications.classList.add('hidden');
        }
        if (!userMenu.classList.contains('hidden')) {
            userMenu.classList.add('hidden');
        }
    }, 10000);
</script>