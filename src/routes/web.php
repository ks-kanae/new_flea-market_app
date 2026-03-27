<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index'])
    ->name('home');

Route::get('/email/verify', function () {
    $user = auth()->user();
    if ($user->hasVerifiedEmail()) {
        return redirect('/');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/item/{id}', [ProductController::class, 'show']);
Route::post('/like/{product}', [LikeController::class, 'toggle']);
Route::post('/comment/{product}', [CommentController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/mypage', function() {
        return view('mypage');
    });

    Route::get('/sell', [ProductController::class, 'create']);
    Route::post('/sell', [ProductController::class, 'store']);

    Route::delete('/comment/{comment}', [CommentController::class, 'destroy']);

    Route::get('/purchase/{item}', [PurchaseController::class, 'show']);
    Route::post('/purchase/{item}', [PurchaseController::class, 'store']);
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'success'])
        ->name('purchase.success');
    Route::get('/purchase/cancel/{item}', [PurchaseController::class, 'cancel'])
        ->name('purchase.cancel');

    Route::get('/purchase/address/{item}', [AddressController::class, 'edit']);
    Route::post('/purchase/address/{item}', [AddressController::class, 'update']);

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
});
