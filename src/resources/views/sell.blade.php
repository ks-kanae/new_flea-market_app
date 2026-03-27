@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h1 class="page-title">商品の出品</h1>

    <form class="sell-form" method="POST" action="/sell" enctype="multipart/form-data">
        @csrf

        <div class="form-section">
            <label class="form-label">商品画像
            @error('image')
                <span class="error-message">{{ $message }}</span>
            @enderror
            </label>
            <div class="image-upload-area">
                <input type="file" name="image" id="image-input" class="image-input" accept="image/*">
                <label for="image-input" class="image-upload-label">
                    画像を選択する
                </label>
                <div id="image-preview" class="image-preview"></div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="section-title">商品の詳細</h2>
            <div class="form-group">
                <label class="form-label">カテゴリー
                @error('categories')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                </label>
                <div class="category-tags">
                @foreach($categories as $category)
                    <label class="category-tag">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">商品の状態
                @error('condition')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                </label>
                <select name="condition" class="form-select">
                    <option value="">選択してください</option>
                    <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="傷や汚れあり" {{ old('condition') == '傷や汚れあり' ? 'selected' : '' }}>状態が悪い</option>
                </select>
            </div>
        </div>

        <div class="form-section">
            <h2 class="section-title">商品名と説明</h2>

            <div class="form-group">
                <label class="form-label">商品名
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                </label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label class="form-label">ブランド名</label>
                <input type="text" name="brand" class="form-input" value="{{ old('brand') }}">
            </div>

            <div class="form-group">
                <label class="form-label">商品の説明
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                </label>
                <textarea name="description" class="form-textarea" rows="6">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">販売価格
                @error('price')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                </label>
                <div class="price-input-wrapper">
                    <span class="currency-symbol">¥</span>
                    <input type="number" name="price" class="form-input price-input" value="{{ old('price') }}">
                </div>
            </div>
        </div>

        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>

<script>
document.getElementById('image-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="プレビュー">';
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
