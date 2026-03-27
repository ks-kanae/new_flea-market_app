@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-detail-container">
        <div class="item-detail-left">
            <div class="item-image">
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                @if($product->is_sold)
                <span class="sold-badge"></span>
                @endif
            </div>
        </div>

        <div class="item-detail-right">
            <h1 class="item-name">{{ $product->name }}
            </h1>
            <p class="item-brand">{{ $product->brand ?? ' ' }}</p>
            <p class="item-price">
            ¥{{ number_format($product->price) }} <span class="tax">(税込)</span>
            </p>
            <div class="item-actions">
                <form action="/like/{{ $product->id }}" method="POST">
                @csrf
                    <button type="submit" class="action-button" style="border:none; background:none;">
                        <img
                        src="{{ $product->likes->where('user_id', auth()->id())->count()
                        ? asset('img/ハートロゴ_ピンク.png')
                        : asset('img/ハートロゴ_デフォルト.png') }}"
                        class="action-icon heart">
                        <span class="count">
                        {{ $product->likes->count() }}
                        </span>
                    </button>
                </form>
                <button class="action-button">
                    <img src="{{ asset('img/ふきだしロゴ.png') }}" class="action-icon">
                    <span class="count">{{ $product->comments->count() }}</span>
                </button>
            </div>
            @if (session('like_error'))
            <span class="like-error">{{ session('like_error') }}
            </span>
            @endif
            @auth
            @if(!$product->is_sold && auth()->id() !== $product->user_id)
            <a href="/purchase/{{ $product->id }}" class="purchase-button">購入手続きへ
            </a>
            @endif
            @else
            <a href="{{ route('login') }}" class="purchase-button">購入手続きへ</a>
            @endauth
            <div class="item-description">
                <h2 class="section-title">商品説明
                </h2>
                <p class="description-text">{{ $product->description }}
                </p>
            </div>
            <div class="item-info">
                <h2 class="section-title">商品の情報</h2>
                <div class="info-row">
                    <span class="info-label">カテゴリー</span>
                    <div class="info-value">
                        @foreach ($product->categories as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label">商品の状態</span>
                    <span class="info-value">{{ $product->condition }}</span>
                </div>
            </div>
            <div class="comments">
                <h2 class="section-title">
                    コメント ({{ $product->comments->count() }})
                </h2>
                @if ($product->comments->count() > 0)
                @foreach ($product->comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        @if ($comment->user->profile && $comment->user->profile->profile_image)
                        <img class="comment-avatar"
                            src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" >
                        @else
                        <div class="comment-avatar">
                        </div>
                        @endif
                        <span class="comment-author">
                        {{ $comment->user->name }}
                        </span>
                        @if (auth()->id() === $comment->user_id)
                        <form action="/comment/{{ $comment->id }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                            <button type="submit" class="delete-button">削除</button>
                        </form>
                        @endif
                    </div>
                    <p class="comment-text">
                        {{ $comment->body }}
                    </p>
                </div>
                @endforeach
                @else
                <div class="comment-item">
                    <div class="comment-header">
                        <div class="comment-avatar"></div>
                        <span class="comment-author">コメントはまだありません</span>
                    </div>
                    <p class="comment-text">ここにコメントが表示されます</p>
                </div>
                @endif
            </div>
            @if (!$product->is_sold)
            <div class="comment-form">
                <h2 class="section-title">商品へのコメント</h2>
                @if (session('comment_error'))
                <p class="comment-error">{{ session('comment_error') }}</p>
                @endif
                @error('body')
                    <p class="comment-error">{{ $message }}</p>
                @enderror
                <form action="/comment/{{ $product->id }}" method="POST">
                @csrf
                    <textarea name="body" class="comment-textarea" placeholder="コメントを入力">{{ old('body') }}</textarea>
                    <button type="submit" class="comment-submit">
                    コメントを送信する
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
