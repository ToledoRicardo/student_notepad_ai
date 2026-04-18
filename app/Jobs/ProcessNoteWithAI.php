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

        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-3-5-sonnet-latest');

        if (empty($apiKey)) {
            AiLog::create([
                'user_id' => $note->user_id,
                'note_id' => $note->id,
                'prompt' => $note->content,
                'status' => 'failed',
                'error_message' => 'ANTHROPIC_API_KEY no configurada.',
            ]);

            return;
        }

        $prompt = "Eres un asistente académico. Organiza, complementa y reestructura estos apuntes de estudiante en español. Devuelve secciones claras, puntos clave y resumen final.\n\n".$note->content;

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

                return;
            }

            $note->update(['ai_content' => $aiText]);

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
        }
    }
}
