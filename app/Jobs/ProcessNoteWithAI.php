<?php

namespace App\Jobs;

use App\Models\AiLog;
use App\Models\Note;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class ProcessNoteWithAI implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $noteId)
    {
    }

    public function handle(): void
    {
        $note = Note::query()->with('user')->find($this->noteId);

        if (!$note) {
            return;
        }

        $note->update(['status' => 'processing']);

        $rawContent = trim((string) ($note->raw_content ?? $note->content ?? ''));

        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-3-5-sonnet-latest');

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

        if ($rawContent === '') {
            AiLog::create([
                'user_id' => $note->user_id,
                'note_id' => $note->id,
                'prompt' => $prompt,
                'status' => 'failed',
                'error_message' => 'La nota no tiene contenido.',
            ]);

            $note->update(['status' => 'failed']);

            return;
        }

        if (empty($apiKey)) {
            AiLog::create([
                'user_id' => $note->user_id,
                'note_id' => $note->id,
                'prompt' => $prompt,
                'status' => 'failed',
                'error_message' => 'ANTHROPIC_API_KEY no configurada.',
            ]);

            $note->update(['status' => 'failed']);

            return;
        }

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
                AiLog::create([
                    'user_id' => $note->user_id,
                    'note_id' => $note->id,
                    'prompt' => $prompt,
                    'response' => $response->body(),
                    'status' => 'failed',
                    'error_message' => 'Error HTTP al consumir Claude API.',
                ]);

                $note->update(['status' => 'failed']);

                return;
            }

            $data = $response->json();
            $aiText = $data['content'][0]['text'] ?? null;

            if (empty($aiText)) {
                AiLog::create([
                    'user_id' => $note->user_id,
                    'note_id' => $note->id,
                    'prompt' => $prompt,
                    'response' => $response->body(),
                    'status' => 'failed',
                    'error_message' => 'La API respondió sin contenido usable.',
                ]);

                $note->update(['status' => 'failed']);

                return;
            }

            $note->update([
                'ai_content' => $aiText,
                'status' => 'ready',
            ]);

            AiLog::create([
                'user_id' => $note->user_id,
                'note_id' => $note->id,
                'prompt' => $prompt,
                'response' => $aiText,
                'status' => 'success',
            ]);
        } catch (Throwable $e) {
            AiLog::create([
                'user_id' => $note->user_id,
                'note_id' => $note->id,
                'prompt' => $prompt,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $note->update(['status' => 'failed']);
        }
    }
}
