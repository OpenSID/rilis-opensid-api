<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keluarga extends Model
{
    use ConfigId;

    /** {@inheritdoc} */
    protected $table = 'tweb_keluarga';

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'id_kk')->orderBy('kk_level')->orderBy('tanggallahir');
    }
}
