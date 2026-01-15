<?php  
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'nomor_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
      // 1 User → 1 Guru
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }
}
