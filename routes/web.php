<?php

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');

Route::get('/login', function (Request $request) {
    return response()->json([
        'message' => 'Não autenticado',
        'error'   => 'Você deve estar autenticado para acessar este recurso.'
    ], 401);
})->name('login');

Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook', [SocialController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);
