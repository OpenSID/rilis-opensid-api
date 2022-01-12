<?php

namespace App\Http\Transformers;

use App\Models\Kategori;
use League\Fractal\TransformerAbstract;

class KategoriTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(Kategori $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->kategori,
            'slug' => $category->slug,
        ];
    }
}
