<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;


class PurchaseController extends Controller
{
    public function show(Product $item)
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

        return view('purchase', [
            'product'  => $item,
            'postcode' => $address['postcode'],
            'address'  => $address['address'],
            'building' => $address['building'],
        ]);
    }

    public function store(PurchaseRequest $request, Product $item)
    {
        if ($item->is_sold || $item->user_id === Auth::id()) {
            abort(404);
        }

        if ($request->payment_method === 'convenience') {
        return $this->completePurchase($item, 'convenience');
        }

        if ($request->payment_method === 'card') {
            return $this->redirectToStripe($item);
        }

        abort(400);
    }

    private function redirectToStripe(Product $item)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', $item),
            'cancel_url' => route('purchase.cancel', $item),
        ]);

        return redirect($session->url);
    }

    public function success(Product $item)
    {
        return $this->completePurchase($item, 'card');
    }

    public function cancel(Product $item)
    {
        return redirect()
            ->route('purchase.show', $item)
            ->withErrors(['payment' => '決済がキャンセルされました']);
    }

    private function completePurchase(Product $item, string $method)
    {
        if ($item->is_sold) {
            return redirect('/')->with('success', '購入済みの商品です');
        }

        $user = Auth::user();

        $address = session("purchase_address.{$item->id}", [
            'postcode' => $user->profile->postcode,
            'address'  => $user->profile->address,
            'building' => $user->profile->building,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $item->id,
            'payment_method' => $method,
            'postcode' => $address['postcode'],
            'address' => $address['address'],
            'building' => $address['building'],
        ]);

        $item->update(['is_sold' => true,
        ]);

        session()->forget("purchase_address.{$item->id}");
        session()->forget("purchase_payment_method.{$item->id}");

        return redirect('/')
            ->with('success', '購入が完了しました');
    }
}
