<?php

namespace App\Models;

use App\Supports\Traits\Authenticatable as AuthAuthenticatable;
use App\Supports\Traits\CanResetPassword;
use App\Supports\Traits\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendudukMandiri extends Model implements
    AuthenticatableContract,
    CanResetPasswordContract,
    MustVerifyEmailContract,
    JWTSubject
{
    use AuthAuthenticatable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
    use HasFactory;

    /** {@inheritdoc} */
    protected $primaryKey = 'id_pend';

    /** {@inheritdoc} */
    protected $table = 'tweb_penduduk_mandiri';

    /** {@inheritdoc} */
    public $incrementing = false;

    /** {@inheritdoc} */
    public const CREATED_AT = 'tanggal_buat';

    /** {@inheritdoc} */
    public const UPDATED_AT = 'updated_at';

    /** {@inheritdoc} */
    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /** {@inheritdoc} */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    /** {@inheritdoc} */
    protected $with = [
        'penduduk',
    ];

    /** {@inheritdoc} */
    protected $fillable = ['pin'];

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

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_pend');
    }

    /**
     * Get email penduduk attribute.
     *
     * @return string
     */
    public function getEmailAttribute()
    {
        return $this->penduduk->email;
    }
}
