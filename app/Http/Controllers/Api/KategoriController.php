<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\CategoryEntities;
use App\Http\Repository\CategoryEntity;
use App\Http\Transformers\KategoriTransformer;

class KategoriController extends Controller
{
    /** @var CategoryEntities */
    protected $category;

    /**
     * Article controller constructor.
     */
    public function __construct(CategoryEntity $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        return $this->fractal($this->category->get(), new KategoriTransformer(), 'categories');
    }

    public function show(string $slug)
    {
        return $this->fractal($this->category->find($slug), new KategoriTransformer(), 'category');
    }
}
