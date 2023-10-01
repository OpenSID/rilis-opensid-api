<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;

class LogTolak extends Model
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_tolak';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];
}
