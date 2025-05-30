<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneratedText;
use App\Models\HistoryGenerated;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryGeneratedController extends Controller
{
    public function history(Request $request): JsonResponse
    {
        try {
            $history = HistoryGenerated::with('generated_text')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            return response()->json([
                $history->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'generated_text' => $item->generated_text->generated_text,
                    ];
                })
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => 'texto não encontrado ou não pertence ao usuário'
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro de servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
