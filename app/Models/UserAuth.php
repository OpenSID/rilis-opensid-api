<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserAuth extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use ConfigId;

    /** {@inheritdoc} */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    /**
     * {@inheritdoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function pamong()
    {
        return $this->hasOne(Pamong::class, 'pamong_id', 'pamong_id');
    }

    public function getFotoProfilAttribute()
    {
        try {
            $path =  Storage::disk('ftp')->exists("/desa/upload/user_pict/{$this->foto}")
                ? "/desa/upload/user_pict/{$this->foto}"
                : "/assets/images/pengguna/{$this->foto}";

            $file = Storage::disk('ftp')->get($path);
            return $file;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
