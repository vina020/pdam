<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengaturan extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'role',
        
        // Profil
        'foto',
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
        
        // Login activity
        'last_login',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notif_email_berita' => 'boolean',
        'notif_email_pengaduan' => 'boolean',
        'notif_push' => 'boolean',
        'last_login' => 'datetime',
    ];
}