<?php
namespace App\MUMMY\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Helpers\Flash;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()
            ->notes()
            ->latest()
            ->get()
            ->groupBy(fn($n) => \Carbon\Carbon::parse($n->created_at)->format('Y-m'));

        $totalWords = Auth::user()->notes()->get()
            ->sum(fn($n) => $n->word_count);

        $totalNotes = Auth::user()->notes()->count();

        return view('MUMMY::notes.index', compact('notes', 'totalWords', 'totalNotes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:5000',
        ]);

        Auth::user()->notes()->create([
            'content' => $request->content,
        ]);

        Flash::success('Note ajoutée.');
        return redirect()->route('mummy.notes.index');
    }

    public function update(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $request->validate([
            'content' => 'required|string|min:3|max:5000',
        ]);

        $note->update(['content' => $request->content]);

        Flash::success('Note modifiée.');
        return redirect()->route('mummy.notes.index');
    }

    public function destroy(Note $note)
    {
        $this->authorizeNote($note);
        $note->delete();

        Flash::success('Note supprimée.');
        return redirect()->route('mummy.notes.index');
    }

    private function authorizeNote(Note $note): void
    {
        abort_if($note->user_id !== Auth::id(), 403);
    }
}