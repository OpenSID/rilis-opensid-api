<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Model;

class UserGrup extends Model
{
    use ConfigId;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_grup';

    public const ADMINISTRATOR = 'administrator';
}
