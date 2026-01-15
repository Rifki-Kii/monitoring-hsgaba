@extends('layout.main')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header Dashboard -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Monitoring</h1>
                <p class="text-gray-600 mt-1">Sistem Monitoring Homeschooling Group ABA</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="far fa-calendar mr-2"></i>
                    {{ date('d F Y') }}
                </div>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Data Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat Card: Total Siswa -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">248</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 font-medium flex items-center">
                    <i class="fas fa-arrow-up mr-1 text-xs"></i>
                    12.5%
                </span>
                <span class="text-gray-500 ml-2">dari bulan lalu</span>
            </div>
        </div>

        <!-- Stat Card: Total Guru -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Guru</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">24</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 font-medium flex items-center">
                    <i class="fas fa-arrow-up mr-1 text-xs"></i>
                    8.3%
                </span>
                <span class="text-gray-500 ml-2">dari bulan lalu</span>
            </div>
        </div>

        <!-- Stat Card: Rata-rata Nilai -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">85.2</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 font-medium flex items-center">
                    <i class="fas fa-arrow-up mr-1 text-xs"></i>
                    3.2%
                </span>
                <span class="text-gray-500 ml-2">dari bulan lalu</span>
            </div>
        </div>

        <!-- Stat Card: Kelas Aktif -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Kelas Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">12</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i class="fas fa-school text-amber-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium flex items-center">
                        <i class="fas fa-circle text-xs mr-1"></i>
                        Semua aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart Container -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Nilai Semester</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Semester 1</option>
                    <option>Semester 2</option>
                    <option>Tahun 2024</option>
                </select>
            </div>
            
            <!-- Placeholder for Chart -->
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-gray-600">Grafik statistik akan ditampilkan di sini</p>
                    <p class="text-sm text-gray-500 mt-1">Data grafik dapat diintegrasikan dengan Chart.js atau library lainnya</p>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Rata-rata nilai semua kelas: <span class="font-semibold text-gray-900">85.2</span></span>
                    <a href="/statistik" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        Lihat detail
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Hari ini</span>
            </div>
            
            <div class="space-y-4">
                <!-- Activity Item -->
                <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-user-plus text-green-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Siswa baru ditambahkan</p>
                        <p class="text-xs text-gray-500 mt-1">Budi Santoso telah ditambahkan ke kelas 10A</p>
                        <p class="text-xs text-gray-400 mt-1">30 menit yang lalu</p>
                    </div>
                </div>

                <!-- Activity Item -->
                <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-pen text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Nilai diperbarui</p>
                        <p class="text-xs text-gray-500 mt-1">Nilai matematika untuk kelas 9B telah diupdate</p>
                        <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                    </div>
                </div>

                <!-- Activity Item -->
                <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-file-export text-purple-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Laporan dihasilkan</p>
                        <p class="text-xs text-gray-500 mt-1">Laporan kehadiran bulan November telah diekspor</p>
                        <p class="text-xs text-gray-400 mt-1">4 jam yang lalu</p>
                    </div>
                </div>

                <!-- Activity Item -->
                <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-exclamation-circle text-amber-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Poin pelanggaran</p>
                        <p class="text-xs text-gray-500 mt-1">3 siswa mendapatkan poin pelanggaran kedisiplinan</p>
                        <p class="text-xs text-gray-400 mt-1">6 jam yang lalu</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center gap-1">
                    <i class="fas fa-history mr-1"></i>
                    Lihat semua aktivitas
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="/master/siswa" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user-plus text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Tambah Siswa</p>
                        <p class="text-xs text-gray-500">Input data siswa baru</p>
                    </div>
                </a>
                
                <a href="/nilai" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="fas fa-edit text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Input Nilai</p>
                        <p class="text-xs text-gray-500">Input nilai akademik</p>
                    </div>
                </a>
                
                <a href="/laporan" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-file-download text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Generate Laporan</p>
                        <p class="text-xs text-gray-500">Buat laporan bulanan</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Class Performance -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Kelas</h3>
            <div class="space-y-4">
                <!-- Class Item -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <span class="font-semibold text-blue-700">10A</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Kelas 10 A</p>
                            <p class="text-xs text-gray-500">24 siswa</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">86.5</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>
                            2.1%
                        </p>
                    </div>
                </div>

                <!-- Class Item -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <span class="font-semibold text-green-700">9B</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Kelas 9 B</p>
                            <p class="text-xs text-gray-500">22 siswa</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">88.2</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>
                            3.5%
                        </p>
                    </div>
                </div>

                <!-- Class Item -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                            <span class="font-semibold text-amber-700">11C</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Kelas 11 C</p>
                            <p class="text-xs text-gray-500">26 siswa</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">83.7</p>
                        <p class="text-xs text-red-600 flex items-center justify-end">
                            <i class="fas fa-arrow-down mr-1 text-xs"></i>
                            1.2%
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="/master/kelas" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center gap-1">
                    <i class="fas fa-list mr-1"></i>
                    Lihat semua kelas
                </a>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Versi Aplikasi</span>
                    <span class="font-medium text-gray-900">v2.1.0</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Status Server</span>
                    <span class="flex items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="font-medium text-green-600">Online</span>
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Database</span>
                    <span class="font-medium text-gray-900">MySQL 8.0</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Pengguna Aktif</span>
                    <span class="font-medium text-gray-900">3</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Data Tersimpan</span>
                    <span class="font-medium text-gray-900">1.2 GB</span>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="text-xs text-gray-500">
                    <p class="mb-1">Terakhir diupdate: {{ date('d M Y H:i') }}</p>
                    <p>Homeschooling Group ABA © {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection