<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'role',
        
        // Profil
        'foto',
        'foto_profil',
        'telepon',
        'no_telepon',
        'jabatan',
        'alamat',
        
        // Notification settings
        'notif_email_berita',
        'notif_email_pengaduan',
        'notif_push',
        
        // Display settings
        'theme',
        'accent_color',
        'font_size',
        
        // Preferences
        'bahasa',
        'timezone',
        'date_format',
        'items_per_page',
        
        // Other
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notif_email_berita' => 'boolean',
        'notif_email_pengaduan' => 'boolean',
        'notif_push' => 'boolean',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function pelanggan() {
        return $this->hasOne(Pelanggan::class, 'user_id');
    }

    public function getFotoUrlAttribute() {
        if ($this->foto_profil && file_exists(public_path($this->foto_profil))) {
            return asset($this->foto_profil);
        }
        return asset('images/default-avatar.png');
    }
}