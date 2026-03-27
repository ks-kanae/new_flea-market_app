@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tab-container">
    <div class="tabs">
        <a href="/?{{ http_build_query(['keyword' => request('keyword')]) }}" class="tab-link {{ request('tab') === null ? 'active' : '' }}">おすすめ
        </a>
        <a href="/?{{ http_build_query(['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
        class="tab-link {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト
        </a>
    </div>
</div>

<div class="content">
    <div class="product-list">
        @forelse ($products as $product)
        <a href="/item/{{ $product->id }}" class="product-card-link">
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                    @if($product->is_sold)
                    <span class="sold-badge"></span>
                    @endif
                </div>
                <div class="product-name">{{ $product->name }}
                </div>
            </div>
        </a>
        @empty
        @if(request('tab') === 'mylist' && isset($showLoginMessage))
        <p class="no-like-message">
            マイリスト機能を使うにはログインしてください
        </p>
        @elseif(request('tab') === 'mylist')
        <p class="no-like-message">
            「いいね」した商品がありません
        </p>
        @elseif(request('keyword'))
        <p class="no-like-message">
            該当する商品が見つかりませんでした
        </p>
        @else
        <p class="no-like-message">
            商品がありません
        </p>
        @endif
        @endforelse
    </div>
</div>

<input type="checkbox" id="purchase-complete" class="modal-toggle" {{ session('success') ? 'checked' : '' }}>
<div class="modal">
    <label for="purchase-complete" class="modal-overlay"></label>

    <div class="modal-content">
        <p class="modal-message">{{ session('success') }}</p>
        <label for="purchase-complete" class="modal-button">OK</label>
    </div>
</div>
@endsection
