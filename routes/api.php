<?php

use App\Http\Controllers\Api\HistoryGeneratedController;
use App\Http\Controllers\Api\PromptController;
use App\Http\Controllers\Api\TextGenerationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('authentication')->group(function () {
    /**
     * Rotas publicas | sem autenticacao | Public routes | no authentication
     */
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    /**
     * Rotas privadas | com autenticacao | Private routes | with authentication
     */
    Route::middleware('auth:sanctum')->group(function () {
        /**
         * Rotas de autenticacao | Authentication routes
         */
        Route::get('/me', [AuthController::class, 'me']);

        /**
         * Rotas de prompts | Prompt routes
         */
        Route::get('/my-inputs', [PromptController::class, 'myInputs']);
        Route::post('/prompt-input', [PromptController::class, 'store']);

        /**
         * Rota de geração de texto
         */
        Route::post('/generate', [TextGenerationController::class, 'generate']);

        /**
         * rotas de histórico
         */
        Route::get('/history', [HistoryGeneratedController::class, 'history']);

        /**
         * Rotas de logout | Logout routes
         */
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
