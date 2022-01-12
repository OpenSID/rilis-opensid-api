<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\ArticleEntity;
use App\Http\Transformers\ArtikelTransformer;

class ArtikelController extends Controller
{
    /** @var ArticleEntity */
    protected $article;

    /**
     * Article controller constructor.
     */
    public function __construct(ArticleEntity $article)
    {
        $this->article = $article;
    }

    public function index()
    {
        return $this->fractal($this->article->get(), new ArtikelTransformer(), 'articles');
    }

    public function show(string $slug)
    {
        return $this->fractal($this->article->find($slug), new ArtikelTransformer(), 'article');
    }

    public function headline()
    {
        return $this->fractal($this->article->headline(), new ArtikelTransformer(), 'headline');
    }
}
