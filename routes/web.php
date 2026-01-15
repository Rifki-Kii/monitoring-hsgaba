<?php

use App\Livewire\GuruIndex;
use App\Livewire\KelasIndex;
use App\Livewire\MapelIndex;
use App\Livewire\NilaiIndex;
use App\Livewire\SiswaIndex;
use App\Livewire\LaporanIndex;
use App\Livewire\DashboardIndex;
use App\Livewire\StatistikIndex;
use App\Livewire\LaporanAkademik;
use App\Livewire\InputPelanggaran;
use App\Livewire\PoinKedisiplinan;
use App\Livewire\StatistikAkademik;
use App\Livewire\LaporanKedisiplinan;
use Illuminate\Support\Facades\Route;
use App\Livewire\StatistikKedisiplinan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatistikController;


/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect('/login'));

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProses']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('login');
;

/*
|--------------------------------------------------------------------------
| AREA LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    // Atau jadikan halaman utama
    Route::get('/', DashboardIndex::class)->name('home');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::resource('user', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | DATA MASTER
    | Admin & Wali Kelas
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin,wali_kelas'])
        ->prefix('master') // Semua route di bawah ini otomatis diawali "/master"
        ->group(function () {

            // --- MODULE LAIN (Controller Biasa) ---
    
            Route::resource('mapel', MapelController::class);

            // --- GURU (Livewire) ---
            Route::get('/guru', GuruIndex::class)->name('guru.index');
            Route::get('kelas', KelasIndex::class)->name('kelas.index');
            Route::get('mapel', MapelIndex::class)->name('mapel.index');



            // 1. Route Export (Tetap pakai Controller)
            // Hapus 'master/' di depannya, karena sudah ada prefix di atas.
            Route::get('siswa/export/excel', [SiswaController::class, 'exportExcel'])->name('siswa.export.excel');
            Route::get('siswa/export/pdf', [SiswaController::class, 'exportPdf'])->name('siswa.export.pdf');
            Route::get('siswa', SiswaIndex::class)->name('siswa.index');


        });





    /*
    |--------------------------------------------------------------------------
    | NILAI & POIN
    | Admin, Guru, Wali Kelas
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,guru,wali_kelas')->group(function () {
        Route::get('nilai', NilaiIndex::class)->name('nilai.index');
        Route::get('/poin', PoinKedisiplinan::class)->name('poin.index');

    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN & STATISTIK
    | Admin & Wali Kelas
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,wali_kelas')->group(function () {
    
    // Route Khusus Akademik
    Route::get('/statistik/akademik', StatistikAkademik::class)->name('statistik.akademik');

    // Route Khusus Kedisiplinan
    Route::get('/statistik/kedisiplinan', StatistikKedisiplinan::class)->name('statistik.kedisiplinan');
    Route::get('/laporan', LaporanIndex::class)->name('laporan.index');
});

Route::get('/tentang', [TentangController::class, 'index'])->name('tentang.index');
   

});


//CRUD
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('user', UserController::class);
});
