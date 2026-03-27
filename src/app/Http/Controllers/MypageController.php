<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller

{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $products = Product::whereHas('purchase', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        } else {
            $products = Product::where('user_id', $user->id)->get();
        }

        return view('mypage', [
            'products' => $products,
            'page' => $page,
        ]);
    }
}
