<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \GuzzleHttp\Client loginOpenSID(string $password)
 *
 * @see \App\Libraries\OpenSID
 */
class OpenSID extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'opensid';
    }
}
