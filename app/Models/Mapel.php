<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Mapel extends Model
{
    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kategori',
        'kkm',
    ];

    // Relasi Many-to-Many ke Guru
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel');
    }
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}