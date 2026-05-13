<?php
namespace App\MUMMY\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;
use App\Helpers\Flash;

class ReminderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Grouper par statut et date
        $upcoming = $user->reminders()
            ->where('is_done', false)
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date')
            ->get()
            ->groupBy(fn($r) => \Carbon\Carbon::parse($r->date)->format('Y-m-d'));

        $overdue = $user->reminders()
            ->where('is_done', false)
            ->where('date', '<', now()->startOfDay())
            ->orderByDesc('date')
            ->get();

        $done = $user->reminders()
            ->where('is_done', true)
            ->orderByDesc('date')
            ->take(10)
            ->get();

        return view('MUMMY::reminders.index', compact('upcoming', 'overdue', 'done'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'date'  => 'required|date',
            'type'  => 'required|in:vaccin,rdv,medicament',
        ]);

        Auth::user()->reminders()->create([
            'title' => $request->title,
            'date'  => $request->date,
            'type'  => $request->type,
        ]);

        Flash::success('Rappel ajouté avec succès.');
        return redirect()->route('mummy.reminders.index');
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $request->validate([
            'title' => 'required|string|max:150',
            'date'  => 'required|date',
            'type'  => 'required|in:vaccin,rdv,medicament',
        ]);

        $reminder->update($request->only('title', 'date', 'type'));

        Flash::success('Rappel modifié.');
        return redirect()->route('mummy.reminders.index');
    }

    public function toggle(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);
        $reminder->update(['is_done' => !$reminder->is_done]);

        Flash::success($reminder->is_done ? 'Marqué comme terminé.' : 'Remis en attente.');
        return redirect()->route('mummy.reminders.index');
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);
        $reminder->delete();

        Flash::success('Rappel supprimé.');
        return redirect()->route('mummy.reminders.index');
    }

    private function authorizeReminder(Reminder $reminder): void
    {
        abort_if($reminder->user_id !== Auth::id(), 403);
    }
}