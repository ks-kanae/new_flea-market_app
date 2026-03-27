<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Product $product)
    {
        if (!Auth::check()) {
        return back()->with('like_error', 'いいねするにはログインが必要です');
        }

        $like = Like::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
        }

        return back();
    }
}
