<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'nip', 
        'nama_guru', 
        'email', 
        'no_hp', 
        'jenis_kelamin', 
        'alamat' // <--- Tambahkan ini
    ];

    // ... relasi mapels dan waliKelas tetap sama ...
    public function mapels() { return $this->belongsToMany(Mapel::class, 'guru_mapel'); }
    public function waliKelas() { return $this->hasOne(Kelas::class, 'wali_guru_id'); }
}
