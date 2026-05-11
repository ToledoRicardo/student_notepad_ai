<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessNoteWithAI;
use App\Models\Note;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $notes = $request->user()
            ->notes()
            ->with('subject')
            ->latest()
            ->get();

        return view('notes.index', compact('notes'));
    }

    public function create(Request $request): View
    {
        $subjects = $request->user()
            ->subjects()
            ->orderBy('name')
            ->get();

        return view('notes.create', compact('subjects'));
    }

    public function show(Note $note): View
    {
        $this->authorize('view', $note);

        $note->load('subject');

        return view('notes.show', compact('note'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:10000'],
            'ai_content' => ['nullable', 'string', 'max:100000'],
        ]);

        if (!empty($validated['subject_id'])) {
            $ownsSubject = Subject::query()
                ->where('id', $validated['subject_id'])
                ->where('user_id', $request->user()->id)
                ->exists();

            if (!$ownsSubject) {
                return back()
                    ->withInput()
                    ->withErrors(['subject_id' => 'La materia seleccionada no te pertenece.']);
            }
        }

        $payload = [
            'subject_id' => $validated['subject_id'] ?? null,
            'title' => $validated['title'],
            'raw_content' => $validated['content'],
            'status' => 'ready',
        ];

        if (Schema::hasColumn('notes', 'content')) {
            $payload['content'] = $validated['content'];
        }

        if (!empty($validated['ai_content'])) {
            $payload['ai_content'] = $validated['ai_content'];
        }

        $request->user()->notes()->create($payload);

        return redirect()
            ->route('notes.index')
            ->with('status', 'Nota guardada exitosamente.');
    }
}
