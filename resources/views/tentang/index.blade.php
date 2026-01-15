@extends('layout.main')

@section('content')
<div class="min-h-screen bg-slate-50 p-6 font-sans">

    {{-- 1. HERO SECTION (Banner Atas) --}}
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl shadow-xl overflow-hidden mb-8 relative text-white">
        {{-- Dekorasi Background --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500 opacity-10 rounded-full -ml-10 -mb-10 blur-2xl"></div>

        <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
            {{-- Logo --}}
            <div class="bg-white p-4 rounded-2xl shadow-lg transform rotate-3 hover:rotate-0 transition duration-500">
                <img src="{{ asset('assets/logo-aba.png') }}" alt="Logo HSG ABA" class="w-24 h-24 object-contain">
            </div>
            
            {{-- Teks Judul --}}
            <div class="text-center md:text-left flex-1">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Monitoring System</h1>
                <p class="text-slate-300 text-lg font-light mb-4">
                    Sistem Informasi Monitoring Nilai Akademik & Poin Kedisiplinan Siswa
                </p>
                <div class="inline-flex items-center gap-2 bg-slate-700/50 px-4 py-1.5 rounded-full border border-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-mono text-slate-300">Versi {{ $version }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- 2. KOLOM KIRI: TENTANG SISTEM --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Card Deskripsi --}}
            <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Tentang Aplikasi
                </h3>
                <p class="text-slate-600 leading-relaxed text-justify">
                    Aplikasi ini dirancang khusus untuk <strong>{{ $school }}</strong> guna mempermudah proses pemantauan perkembangan siswa. Sistem ini mengintegrasikan dua aspek vital pendidikan, yaitu <strong>Akademik</strong> dan <strong>Karakter (Kedisiplinan)</strong>, dalam satu platform terpadu.
                </p>
                <p class="text-slate-600 leading-relaxed text-justify mt-4">
                    Dibangun menggunakan teknologi web modern, aplikasi ini bertujuan untuk menggantikan sistem pencatatan manual/konvensional, meminimalisir kesalahan rekap data, serta mempercepat proses pelaporan (Rapor & Surat Peringatan) kepada orang tua siswa.
                </p>
            </div>

            {{-- Card Fitur Unggulan --}}
            <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-star text-amber-500"></i> Fitur Unggulan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fitur 1 --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-graduation-cap text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700">Manajemen Nilai</h4>
                            <p class="text-xs text-slate-500 mt-1">Input nilai harian, UTS, UAS, dan kalkulasi otomatis rata-rata serta predikat rapor.</p>
                        </div>
                    </div>
                    {{-- Fitur 2 --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-gavel text-rose-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700">Poin Kedisiplinan</h4>
                            <p class="text-xs text-slate-500 mt-1">Pencatatan pelanggaran, perhitungan poin otomatis, dan status sanksi berjenjang.</p>
                        </div>
                    </div>
                    {{-- Fitur 3 --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-print text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700">Cetak Laporan Otomatis</h4>
                            <p class="text-xs text-slate-500 mt-1">Generate Leger Nilai, Rapor Siswa, dan Surat Peringatan (SP) dalam format siap cetak/Excel.</p>
                        </div>
                    </div>
                    {{-- Fitur 4 --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chart-line text-cyan-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700">Statistik Real-time</h4>
                            <p class="text-xs text-slate-500 mt-1">Dashboard visual untuk memantau tren pelanggaran dan prestasi siswa secara langsung.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- 3. KOLOM KANAN: TEKNOLOGI & DEVELOPER --}}
        <div class="space-y-8">
            
            {{-- Stack Teknologi --}}
            <div class="bg-slate-800 text-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-lg mb-4 border-b border-slate-600 pb-2">Teknologi</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="fab fa-laravel text-red-500"></i> Framework</span>
                        <span class="font-mono text-slate-300">Laravel 12</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="fas fa-bolt text-yellow-400"></i> Interactivity</span>
                        <span class="font-mono text-slate-300">Livewire 3</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="fab fa-css3 text-blue-400"></i> Styling</span>
                        <span class="font-mono text-slate-300">Tailwind CSS</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="fas fa-database text-orange-400"></i> Database</span>
                        <span class="font-mono text-slate-300">MySQL</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="fas fa-code-branch text-purple-400"></i> Metode</span>
                        <span class="font-mono text-slate-300">RAD</span>
                    </li>
                </ul>
            </div>

          {{-- Profil Developer (Skripsi Info) --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 text-center">
                
                {{-- BAGIAN FOTO PROFIL (UPDATED) --}}
                <div class="w-28 h-28 mx-auto bg-slate-100 rounded-full mb-4 border-4 border-white shadow-md overflow-hidden relative group cursor-pointer">
                    {{-- Pastikan file ada di: public/assets/profile.jpg --}}
                    <img src="{{ asset('assets/profil.jpg') }}" 
                         alt="Foto Developer" 
                         class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">
                </div>

                <h4 class="text-lg font-bold text-slate-800">{{ $devName }}</h4>
                <p class="text-sm text-slate-500 font-mono mb-4">{{ $devNim }}</p>
                
                <div class="text-xs text-slate-600 space-y-1 bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p>Program Studi Informatika</p>
                    <p class="font-bold">{{ $campus }}</p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Disusun Sebagai</p>
                    <p class="text-xs font-bold text-slate-700 mt-1">Tugas Akhir / Skripsi {{ $year }}</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Footer Kecil --}}
    <div class="mt-12 text-center text-slate-400 text-xs">
        <p>&copy; {{ $year }} {{ $school }}. All rights reserved.</p>
        <p class="mt-1">Dibuat dengan <i class="fas fa-heart text-rose-500 mx-1"></i> di Bogor.</p>
    </div>

</div>
@endsection