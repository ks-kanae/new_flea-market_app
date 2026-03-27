@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1 class="address-title">住所の変更</h1>

    <form class="address-form" method="POST" action="/purchase/address/{{ $item_id }}" class="h-adr">
        @csrf
        <span class="p-country-name" style="display:none;">Japan</span>

        <div class="form-group">
            <label for="postcode" class="form-label">郵便番号</label>
            <input type="text" id="postcode" name="postcode" class="form-input p-postal-code" value="{{ old('postcode', $postcode ?? '') }}">
            @error('postcode')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address" class="form-label">住所</label>
            <input type="text" id="address" name="address" class="form-input p-region p-locality p-street-address p-extended-address" value="{{ old('address', $address ?? '') }}" >
            @error('address')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building" class="form-label">建物名</label>
            <input type="text" id="building" name="building" class="form-input" value="{{ old('building', $building ?? '') }}" >
            @error('building')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="submit-button">更新する
        </button>
    </form>
</div>
@endsection
