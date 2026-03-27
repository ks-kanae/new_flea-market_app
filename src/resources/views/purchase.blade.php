@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-left">

        <div class="purchase-item">
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="商品画像">
            <div class="item-info">
                <h2>{{ $product->name }}</h2>
                <p class="price">¥{{ number_format($product->price) }}</p>
            </div>
        </div>

        <form method="POST" action="/purchase/{{ $product->id }}" id="purchase-form">
            @csrf
            <div class="purchase-section">
                <h3>支払い方法</h3>
                <select name="payment_method" id="payment-method" class="payment-method">
                    <option value="">選択してください</option>
                    <option value="convenience">コンビニ払い</option>
                    <option value="card">カード支払い</option>
                </select>
                @error('payment_method')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <input type="hidden" name="postcode" value="{{ $postcode }}">
            <input type="hidden" name="address" value="{{ $address }}">
            <input type="hidden" name="building" value="{{ $building }}">
        </form>

        <div class="purchase-section">
            <div class="section-header">
                <h3>配送先</h3>
                <a href="/purchase/address/{{ $product->id }}" class="change-link">変更する</a>
            </div>
            <p class="address-detail">〒 {{ $postcode }}</p>
            <p class="address-detail">{{ $address }}</p>
            <p class="address-detail">{{ $building ?? '' }}</p>
            @if ($errors->has('postcode') || $errors->has('address'))
                <p class="form-error">
                    配送先住所が設定されていません。
                </p>
            @endif
        </div>
    </div>

    <div class="purchase-right">
        <div class="summary-box">
            <div class="summary-row">
                <span>商品代金</span>
                <span class="amount">¥{{ number_format($product->price) }}</span>
            </div>
            <div class="summary-row">
                <span>支払い方法</span>
                <span id="summary-payment">
                    {{ ($paymentMethod ?? '') === 'convenience'
                    ? 'コンビニ払い'
                    : (($paymentMethod ?? '') === 'card' ? 'カード支払い' : '') }}
                </span>
            </div>
        </div>
        <button type="submit" class="purchase-button" form="purchase-form">
            購入する
        </button>
    </div>
</div>

@if ($errors->has('payment'))
<input type="checkbox" id="payment-cancel" class="modal-toggle" checked>

<div class="modal">
    <label for="payment-cancel" class="modal-overlay"></label>

    <div class="modal-content">
        <p class="modal-message">
            {{ $errors->first('payment') }}
        </p>
        <label for="payment-cancel" class="modal-button">OK</label>
    </div>
</div>
@endif


<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('payment-method');
    const summary = document.getElementById('summary-payment');

    select.addEventListener('change', function() {
        if (select.value === 'convenience') {
            summary.textContent = 'コンビニ払い';
        } else if (select.value === 'card') {
            summary.textContent = 'カード支払い';
        } else {
            summary.textContent = '';
        }
    });
});
</script>

@endsection
