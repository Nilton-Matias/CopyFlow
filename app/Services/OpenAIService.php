<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl;

    /**
     * Constructor da classe, ele lê a chave da API e a URL base, armazenadas no .env
     */
    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->baseUrl = env('OPENAI_API_BASE_URL', 'https://openrouter.ai/api/v1/chat/completions');
    }

    public function generate(string $prompt): string
    {
        try {
            /**
             * Usa a facade Http para enviar uma requisão POST à API
             */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'openai/gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content', 'Texto não pode ser gerado.');
            } else {
                // Retorna mensagem de erro amigável como string
                return 'Ocorreu um erro ao gerar texto. Tente novamente mais tarde. Status: ' . $response->status();
            }
        } catch (Exception $e) {
            return 'Erro de sistema ao gerar o texto. Detalhe: ' . $e->getMessage();
        }
    }
}
