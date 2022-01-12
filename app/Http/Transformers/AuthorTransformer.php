<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'active' => $user->active,
            'name' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'image' => $user->foto,
        ];
    }
}
