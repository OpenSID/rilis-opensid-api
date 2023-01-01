<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\CommentEntity;
use App\Http\Transformers\KomentarTransformer;
use Exception;
use Illuminate\Http\Request;

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
        try {
            $data = $this->comment->find($id);
            return response()->json(['status' => true, 'data' => $data ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_artikel' => 'required',
            'komentar' => 'required',
        ]);

        try {
            $comment = $this->comment->insert($request);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }

        return $this->fractal($comment, new KomentarTransformer(), 'comment');
    }
}
