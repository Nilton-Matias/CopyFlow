<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneratedText;
use App\Models\HistoryGenerated;
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
                'Crie um texto de marketing para um produto chamado "%s". Destaque os principais diferenciais do produto: "%s". O texto deve ser escrito para o seguinte público-alvo: %s. O objetivo da mensagem é: %s. A mensagem será veiculada nos seguintes canais: %s. Use um tom de voz: %s (exemplo: direto, vibrante, ousado, casca grossa, inspirador, técnico, informal). Inclua uma chamada para ação clara. Adicione as informações de contacto: Telefone: %s | Email: %s | Localização: %s. Escreva em: %s. mais direta e com pegada firme',
                $promptInput->product_name,
                $promptInput->benefits,
                $promptInput->audience,
                $promptInput->goal,
                $promptInput->platform,
                $promptInput->tone,
                $promptInput->contact,
                $promptInput->email,
                $promptInput->location,
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
                'data' => [
                    'id' => $record->id,
                    'user_name' => $record->user->name,
                    'prompt' => $record->prompt,
                    'generated_text' => $record->generated_text,
                    'created_at' => $record->created_at
                ]
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
