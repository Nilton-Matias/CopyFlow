<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneratedText;
use App\Models\PromptInput;
use App\Services\OpenAIService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * O usuário envia um prompt_input_id
 * O sistema valida e confirma que é do usuário
 * Os dados são usados para montar um prompt descritivo
 * O prompt vai para OpenAI -> gera texto
 * Texto é salvo junto com o prompt e user_id
 * O sistema retorna o texto gerado
 */
class TextGenerationController extends Controller
{
    public function generate(Request $request, OpenAIService $openAI)
    {
        try {
            $request->validate([
                'prompt_input_id' => 'required|exists:prompt_inputs,id',
            ]);

            /**
             * Busca o input pelo id, mas somente se o usuário autenticado for o dono do input
             */
            $promptInput = PromptInput::where('id', $request->prompt_input_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            /**
             * Monta o prompt final e preenche com os dados do banco, organizada de forma personalizada para a IA
             */
            $prompt = sprintf(
                'Crie um texto de marketing para um produto chamado "%s". Diferencial: "%s". Público-alvo: %s. Objectivo: %s. Canal: %s. Tom: %s. Idioma: %s.',
                $promptInput->product_name,
                $promptInput->benefits,
                $promptInput->audience,
                $promptInput->goal,
                $promptInput->platform,
                $promptInput->tone,
                $promptInput->language ?? 'Português'
            );

            /**
             * Mande o prompt para o método generate() no OpenAIService, e recebe de volta o texto gerado
             */
            $generated = $openAI->generate($prompt);

            /**
             * Salva no banco o histórico do texto gerado: quem pediu, o que pediu, e o que foi gerado.
             */
            $record = GeneratedText::create([
                'user_id' => Auth::id(),
                'prompt_input_id' => $promptInput->id,
                'prompt' => $prompt,
                'generated_text' => $generated
            ]);

            return response()->json([
                'message' => 'Texto gerado com sucesso',
                'data' => $record
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
