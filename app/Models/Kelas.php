<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas'; // Pastikan nama tabel benar
    protected $fillable = ['nama_kelas', 'jenjang', 'wali_guru_id'];

    // Relasi ke Guru (Wali Kelas)
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_guru_id');
    }

    // Relasi ke Siswa (Untuk menghitung jumlah siswa nanti)
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}