<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'content' => ['required', 'string', 'max:10000'],
        ]);

        $rawContent = $request->input('content');

        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-sonnet-4-6');

        if (empty($apiKey)) {
            return response()->json(['error' => 'La API Key de Anthropic no está configurada.'], 500);
        }

        $prompt = "Eres un asistente académico. Organiza y reestructura estos apuntes en español.\n"
            ."Responde SOLO en Markdown con esta estructura:\n"
            ."# Título\n"
            ."## Ideas clave\n"
            ."- ...\n"
            ."## Desarrollo\n"
            ."...\n"
            ."## Resumen\n"
            ."...\n\n"
            ."Apuntes originales:\n\n"
            .$rawContent;

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => 1200,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Error de la IA: ' . $response->body()
                ], 500);
            }

            $data = $response->json();
            $aiText = $data['content'][0]['text'] ?? null;

            if (empty($aiText)) {
                return response()->json(['error' => 'La IA no devolvió contenido válido.'], 500);
            }

            return response()->json(['ai_content' => $aiText]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function refine(Request $request): JsonResponse
    {
        $request->validate([
            'raw_content' => ['required', 'string', 'max:10000'],
            'ai_content' => ['required', 'string', 'max:100000'],
            'prompt' => ['required', 'string', 'max:500'],
        ]);

        $rawContent = $request->input('raw_content');
        $aiContent = $request->input('ai_content');
        $userPrompt = $request->input('prompt');

        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-sonnet-4-6');

        if (empty($apiKey)) {
            return response()->json(['error' => 'La API Key de Anthropic no está configurada.'], 500);
        }

        $prompt = "Eres un asistente académico. Tienes los apuntes originales de un alumno y la versión estructurada que generaste anteriormente. "
            ."El alumno te ha dado una nueva instrucción para refinar o mejorar el contenido estructurado.\n\n"
            ."INSTRUCCIÓN DEL ALUMNO: \"{$userPrompt}\"\n\n"
            ."APUNTES ORIGINALES DEL ALUMNO:\n{$rawContent}\n\n"
            ."VERSIÓN ESTRUCTURADA ACTUAL:\n{$aiContent}\n\n"
            ."Aplica la instrucción a la versión estructurada y devuelve SOLO el nuevo Markdown completo, sin comentarios adicionales.";

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => 1500,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Error al comunicarse con la IA para refinar.'], 500);
            }

            $data = $response->json();
            $aiText = $data['content'][0]['text'] ?? null;

            if (empty($aiText)) {
                return response()->json(['error' => 'La IA no devolvió contenido válido al refinar.'], 500);
            }

            return response()->json(['ai_content' => $aiText]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }
}
