<?php

namespace App\Policies;

use App\Models\Dokumen;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;

class DokumenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the dokumen.
     *
     * @param  Authenticatable  $user
     * @param  Dokumen  $dokumen
     * @return mixed
     */
    public function view(Authenticatable $user, Dokumen $dokumen)
    {
        return $user->getAuthIdentifier() === $dokumen->id_pend
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk dokumen ini.', 403);
    }

    /**
     * Determine whether the user can update the dokumen.
     *
     * @param  Authenticatable  $user
     * @param  Dokumen  $dokumen
     * @return mixed
     */
    public function update(Authenticatable $user, Dokumen $dokumen)
    {
        return $user->getAuthIdentifier() === $dokumen->id_pend
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk dokumen ini.', 403);
    }

    /**
     * Determine whether the user can delete the dokumen.
     *
     * @param  Authenticatable  $user
     * @param  Dokumen  $dokumen
     * @return mixed
     */
    public function delete(Authenticatable $user, Dokumen $dokumen)
    {
        return $user->getAuthIdentifier() === $dokumen->id_pend
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk dokumen ini.', 403);
    }
}
