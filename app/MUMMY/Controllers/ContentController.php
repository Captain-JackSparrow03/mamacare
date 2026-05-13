<?php
namespace App\MUMMY\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Content;
use App\Helpers\Flash;

class ContentController extends Controller
{
    // ─── VUE MAMAN ───────────────────────────────────────────
    public function index()
    {
        $user      = Auth::user();
        $pregnancy = $user->currentPregnancy;
        $week      = $pregnancy?->current_week ?? 1;

        // Contenus de la semaine courante + contenus généraux
        $contents = Content::forWeek($week)
            ->orderByRaw("FIELD(type, 'video', 'audio', 'article')")
            ->get()
            ->groupBy('type');

        // Toutes les semaines qui ont du contenu (pour le filtre)
        $availableWeeks = Content::whereNotNull('week')
            ->distinct()
            ->orderBy('week')
            ->pluck('week');

        return view('MUMMY::contents.index', compact(
            'contents', 'week', 'availableWeeks', 'pregnancy'
        ));
    }

    // Filtre par semaine choisie manuellement
    public function byWeek(int $week)
    {
        $user      = Auth::user();
        $pregnancy = $user->currentPregnancy;

        $contents = Content::forWeek($week)
            ->orderByRaw("FIELD(type, 'video', 'audio', 'article')")
            ->get()
            ->groupBy('type');

        $availableWeeks = Content::whereNotNull('week')
            ->distinct()
            ->orderBy('week')
            ->pluck('week');

        return view('MUMMY::contents.index', compact(
            'contents', 'week', 'availableWeeks', 'pregnancy'
        ));
    }

    // ─── ADMIN ───────────────────────────────────────────────
    public function adminIndex()
    {
        $contents = Content::orderByDesc('created_at')->paginate(15);
        return view('MUMMY::contents.admin.index', compact('contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|in:article,video,audio',
            'url'         => 'nullable|url',
            'thumbnail'   => 'nullable|url',
            'week'        => 'nullable|integer|min:1|max:40',
        ]);

        Content::create($request->only(
            'title', 'description', 'type', 'url', 'thumbnail', 'week'
        ));

        Flash::success('Contenu ajouté.');
        return redirect()->route('admin.contents.index');
    }

    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|in:article,video,audio',
            'url'         => 'nullable|url',
            'thumbnail'   => 'nullable|url',
            'week'        => 'nullable|integer|min:1|max:40',
        ]);

        $content->update($request->only(
            'title', 'description', 'type', 'url', 'thumbnail', 'week'
        ));

        Flash::success('Contenu modifié.');
        return redirect()->route('admin.contents.index');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        Flash::success('Contenu supprimé.');
        return redirect()->route('admin.contents.index');
    }
}