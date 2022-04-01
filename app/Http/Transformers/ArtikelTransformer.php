<?php

namespace App\Http\Transformers;

use App\Models\Artikel;
use League\Fractal\TransformerAbstract;

class ArtikelTransformer extends TransformerAbstract
{
    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected array $availableIncludes = [
        'author',
        'category',
        'comments',
    ];

    /**
     * {@inheritdoc}
     */
    public function transform(Artikel $article)
    {
        return [
            'id' => $article->id,
            'slug' => $article->slug,
            'title' => $article->judul,
            'text' => $article->isi,
            'image' => $article->url_gambar,
            'image1' => $article->url_gambar1,
            'image2' => $article->url_gambar2,
            'iamge3' => $article->url_gambar3,
            'url' => $article->url_artikel,
            'read_count' => $article->hit,
            'estimate_reading' => $article->perkiraan_membaca,
            'created_at' => $article->tgl_upload,
            'jumlah_komentar' => (int) $article->comments_count,
            'komentar' => $article->comments,
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
