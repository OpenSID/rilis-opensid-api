<?php

namespace App\Http\Transformers;

use App\Models\UserAuth;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(UserAuth $user)
    {
        return array_merge(
            $user->token ? [
                'id' => $user->id,
                'access_token' => [
                    'token' => $user->token,
                    'token_type' => 'Bearer',
                    'expires_in' => auth('jwt')->factory()->getTTL() * 60,
                ],
            ] : [],
            [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nama' => $user->nama,
            ]
        );
    }
}
