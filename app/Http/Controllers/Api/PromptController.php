<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromptInput;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromptController extends Controller
{
    public function myInputs(Request $request): JsonResponse
    {
        $input = PromptInput::where('user_id', $request->user()->id)->get();

        return response()->json([
            'inputs' => $input,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'benefits' => 'required|string',
                'audience' => 'required|string',
                'goal' => 'required|string',
                'platform' => 'required|string',
                'tone' => 'required|string',
                'contact' => 'nullable|string',
                'email' => 'nullable|string',
                'location' => 'nullable|string',
                'language' => 'nullable|string',
            ], [
                'product_name.required' => 'O campo nome do producto é obrigatório.',
                'benefits.required' => 'O campo beneficios é obrigatório.',
                'audience.required' => 'O campo público-alvo é obrigatório.',
                'goal.required' => 'O campo objectivos é obrigatório.',
                'platform.required' => 'O campo platforma é obrigatório.',
                'tone.required' => 'O campo Tom é obrigatório.',
            ]);

            /**
             * Set the user_id in the validated data to the current user's id.
             */
            $validated['user_id'] = $request->user()->id;

            $input = PromptInput::create($validated);

            DB::commit();

            return response()->json([
                'message' => 'Input salvo com sucesso.',
                'input' => $input,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação de dados.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro de servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
