<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Str;
use App\Http\Traits\ConfigId;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
 use App\Notifications\ResetPasswordNotificationLink;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'nama',
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

    public $timestamps = false;

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

    // jabatan
    public function getJabatanAttribute()
    {
        if ($this->pamong && $this->pamong->jabatan_id == kades()->id) {
            return 'kades';
        } elseif ($this->pamong && $this->pamong->jabatan_id == sekdes()->id) {
            return 'sekdes';
        } else {
            return 'operator';
        }
    }

    public function getFotoProfilAttribute()
    {
        try {
            $path =  Storage::disk('ftp')->exists("/desa/upload/user_pict/kecil_{$this->foto}")
                ? "/desa/upload/user_pict/kecil_{$this->foto}"
                : "/assets/images/pengguna/{$this->foto}";
            $file = Storage::disk('ftp')->download($path);

            return $file;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function sendPasswordResetNotification($token)
{
    $password = Str::random(10);
    $url = URL::to('/api/admin/reset?token=').$token.'&email='.$this->email.'&password='.$password;
    $this->notify(new ResetPasswordNotificationLink($url));
}
}
