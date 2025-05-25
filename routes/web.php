<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function (Request $request) {
    return response()->json([
        'message' => 'Não autenticado',
        'error'   => 'Você deve estar autenticado para acessar este recurso.'
    ], 401);
})->name('login');
