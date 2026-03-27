<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $keywords = array_filter(
            preg_split('/[\s　]+/u', trim($request->keyword))
            );

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                $q->orWhere('name', 'like', '%' . $word . '%');
                }
            });
        }

        if ($request->tab === 'mylist') {
            if (!Auth::check()) {
                return view('index', [
                    'products' => collect(),
                    'showLoginMessage' => true,
                ]);
            }

            $query->whereHas('likes', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if (Auth::check()) {
        $query->where('user_id', '!=', Auth::id());
        }

        $products = $query->latest()->take(20)->get();

        return view('index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('product_images', 'public');

        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image_path' => $path,
        ]);

        $product->categories()->sync($request->categories);

        return redirect('/')->with('success', '商品を出品しました！');
    }

    public function show($id)
    {
    $product = Product::with('categories', 'user')->findOrFail($id);

    return view('item', compact('product'));
    }

}
