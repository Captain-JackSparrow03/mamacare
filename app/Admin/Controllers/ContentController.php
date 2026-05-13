<?php
namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Content;
use App\Helpers\Flash;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::orderByDesc('created_at')->paginate(15);
        return view('Admin::contents.index', compact('contents'));
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