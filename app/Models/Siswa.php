<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;   // <--- PENTING
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- PENTING

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'kelas_id',
        'foto',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'nama_ayah',
        'nama_ibu',
        'status_sanksi'
    ];

    // Relasi: Satu Siswa milik Satu Kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi: Satu Siswa punya Banyak Nilai
    // INI YANG KEMARIN ERROR ("Call to undefined relationship [nilais]")
    // PASTIKAN INI ADA DI SINI (SISWA.PHP), BUKAN DI NILAI.PHP
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
    
    public function catatanPelanggarans()
    {
        return $this->hasMany(CatatanPelanggaran::class);
    }

    // FUNGSI PINTAR: Hitung Poin Bulan Ini
    // Kita taruh logika "Reset Bulanan" disini agar rapi
    public function getPoinBulanIniAttribute()
    {
        return $this->catatanPelanggarans()
            ->whereMonth('tanggal', now()->month) // Filter Bulan Ini
            ->whereYear('tanggal', now()->year)   // Filter Tahun Ini
            ->join('master_pelanggarans', 'catatan_pelanggarans.master_pelanggaran_id', '=', 'master_pelanggarans.id')
            ->sum('master_pelanggarans.poin');
    }
}