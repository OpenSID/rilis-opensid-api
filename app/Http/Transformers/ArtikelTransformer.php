<?php

namespace App\Http\Transformers;

use App\Models\Artikel;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ArtikelTransformer extends TransformerAbstract
{
    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = [
        'author',
        'category',
        'comments',
    ];

    /**
     * {@inheritdoc}
     */
    public function transform(Artikel $article)
    {
        $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $article->tgl_upload)->format('Y/m/d');

        return [
            'id' => $article->id,
            'slug' => $article->slug,
            'title' => $article->judul,
            'text' => $article->isi,
            'image' => $article->url_gambar,
            'image1' => $article->url_gambar1,
            'image2' => $article->url_gambar2,
            'iamge3' => $article->url_gambar3,
            'url' => env('APP_URL') . '/index.php/artikel/' . $tanggal . '/' . $article->slug,
            'read_count' => $article->hit,
            'estimate_reading' => $article->perkiraan_membaca,
            'created_at' => $article->tgl_upload,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function includeAuthor(?Artikel $articles = null)
    {
        if ($articles === null) {
            return [];
        }

        return $this->item($articles->author, new AuthorTransformer(), 'author');
    }

    /**
     * {@inheritdoc}
     */
    public function includeCategory(?Artikel $articles = null)
    {
        if ($articles === null) {
            return [];
        }

        return $this->item($articles->category, new KategoriTransformer(), 'category');
    }

    /**
     * {@inheritdoc}
     */
    public function includeComments(?Artikel $articles = null)
    {
        if ($articles === null) {
            return [];
        }

        return $this->collection($articles->comments, new KomentarTransformer(), 'comments');
    }
}
