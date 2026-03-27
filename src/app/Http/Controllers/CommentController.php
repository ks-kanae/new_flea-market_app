<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Product;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        if (!auth()->check()) {
        return back()
        ->withInput()
        ->with('comment_error', 'コメントするにはログインが必要です');
        }

        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'body' => $request->body,
        ]);

        return back();
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
        abort(403);
        }
        $comment->delete();

        return back();
    }
}
