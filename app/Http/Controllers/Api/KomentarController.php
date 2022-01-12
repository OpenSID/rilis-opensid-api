<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\CommentEntity;
use App\Http\Transformers\KomentarTransformer;

class KomentarController extends Controller
{
    /** @var CommentEntity */
    protected $comment;

    /**
     * Article controller constructor.
     */
    public function __construct(CommentEntity $comment)
    {
        $this->comment = $comment;
    }

    public function index()
    {
        return $this->fractal($this->comment->get(), new KomentarTransformer(), 'comments');
    }

    public function show(int $id)
    {
        return $this->fractal($this->comment->find($id), new KomentarTransformer(), 'comment');
    }
}
