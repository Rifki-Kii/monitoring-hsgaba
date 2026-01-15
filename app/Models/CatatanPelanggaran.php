<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanPelanggaran extends Model
{
    protected $guarded = [];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Jenis Pelanggaran (untuk ambil poinnya)
    public function masterPelanggaran()
    {
        return $this->belongsTo(MasterPelanggaran::class);
    }
}