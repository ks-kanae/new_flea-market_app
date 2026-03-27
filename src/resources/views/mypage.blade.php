@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
@if (session('success'))
    <div class="profile-alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="mypage-header">
    <div class="profile-section">
    @if(Auth::user()->profile && Auth::user()->profile->profile_image)
        <img class="profile-avatar" src="{{ asset('storage/' . Auth::user()->profile->profile_image) }}">
    @else
        <div class="profile-avatar"></div>
    @endif
        <h1 class="profile-name">{{ Auth::user()->name }}</h1>
    </div>
    <a href="/mypage/profile" class="edit-profile-button">プロフィールを編集</a>
</div>

<div class="tab-container">
    <div class="tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="tab-link {{ $page === 'sell' ? 'active' : '' }}">出品した商品
        </a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="tab-link {{ $page === 'buy' ? 'active' : '' }}">購入した商品
        </a>
    </div>
</div>

<div class="content">
    <div class="product-list">
        @forelse ($products as $product)
        <a href="/item/{{ $product->id }}" class="product-card-link">
            <div class="product-card">
                <div class="product-image">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                    @else
                        <span>商品画像</span>
                    @endif
                    @if($product->is_sold)
                        <span class="sold-badge"></span>
                    @endif
                </div>
                <div class="product-name">{{ $product->name }}</div>
            </div>
        </a>
        @empty
            @if($page === 'sell')
                <p class="no-like-message">出品した商品がありません</p>
            @elseif($page === 'buy')
                <p class="no-like-message">購入した商品がありません</p>
            @else
                <p class="no-like-message">商品がありません</p>
            @endif
        @endforelse
    </div>
</div>
@endsection
