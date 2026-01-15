<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- PENTING

class Nilai extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi: Nilai milik Siswa
    public function siswa(): BelongsTo 
    { 
        return $this->belongsTo(Siswa::class); 
    }

    // Relasi: Nilai milik Kelas
    public function kelas(): BelongsTo 
    { 
        return $this->belongsTo(Kelas::class); 
    }

    // Relasi: Nilai milik Mapel
    public function mapel(): BelongsTo 
    { 
        return $this->belongsTo(Mapel::class); 
    }

    // Relasi: Nilai diinput oleh Guru (User)
    public function guru(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'guru_id'); 
    }
}