<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit(Product $item)
    {
        if ($item->is_sold || $item->user_id === Auth::id()) {
        abort(404);
        }

        $user = Auth::user();

        $address = session("purchase_address.{$item->id}", [
            'postcode' => $user->profile->postcode ?? '',
            'address'  => $user->profile->address ?? '',
            'building' => $user->profile->building ?? '',
        ]);

        return view('address', [
            'item_id' => $item->id,
            'postcode' => $address['postcode'],
            'address' => $address['address'],
            'building' => $address['building'],
            'paymentMethod' => session("purchase_payment_method.{$item->id}")
        ]);

    }

    public function update(AddressRequest $request, Product $item)
    {
        if ($item->is_sold || $item->user_id === Auth::id()) {
            abort(404);
        }

        session([
            "purchase_address.{$item->id}" => $request->only([
                'postcode',
                'address',
                'building',
            ])
        ]);
        return redirect("/purchase/{$item->id}");
    }
}
