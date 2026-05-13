@extends('Admin::layouts.app')

@section('breadcrumb', 'Contenus')

@section('content')

<div x-data="{
    showAdd:    false,
    showEdit:   false,
    showDelete: false,
    editData:   { id: null, title: '', description: '', type: 'article', url: '', thumbnail: '', week: '' },
    deleteId:   null,
    openEdit(data) { this.editData = data; this.showEdit = true; },
    openDelete(id) { this.deleteId = id;   this.showDelete = true; }
}">

{{-- HEADER --}}
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="font-serif font-light text-3xl">Gestion des <em class="italic text-rose-400">Contenus</em></h1>
        <p class="text-sm text-zinc-400 mt-1">Articles, vidéos et audios pour les mamans</p>
    </div>
    <button @click="showAdd = true"
            class="flex items-center gap-2 bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
        <span>+</span> Nouveau contenu
    </button>
</div>

{{-- TABLEAU --}}
<div class="bg-white rounded-2xl border border-zinc-100 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-zinc-100">
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3.5">Titre</th>
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3.5">Type</th>
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3.5">Semaine</th>
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3.5">URL</th>
                <th class="px-6 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-50">
            @forelse($contents as $content)
            @php
                $editPayload = json_encode([
                    'id'          => $content->id,
                    'title'       => $content->title,
                    'description' => $content->description ?? '',
                    'type'        => $content->type,
                    'url'         => $content->url ?? '',
                    'thumbnail'   => $content->thumbnail ?? '',
                    'week'        => $content->week ?? '',
                ]);
                $typeBg = [
                    'article' => 'bg-rose-50 text-rose-400',
                    'video'   => 'bg-violet-50 text-violet-400',
                    'audio'   => 'bg-amber-50 text-amber-400',
                ];
            @endphp
            <tr class="hover:bg-zinc-50 transition-colors">
                <td class="px-6 py-3.5">
                    <div class="text-sm font-medium text-zinc-700">{{ Str::limit($content->title, 50) }}</div>
                    @if($content->description)
                    <div class="text-xs text-zinc-400 mt-0.5">{{ Str::limit($content->description, 60) }}</div>
                    @endif
                </td>
                <td class="px-6 py-3.5">
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full {{ $typeBg[$content->type] ?? 'bg-zinc-100 text-zinc-400' }}">
                        {{ $content->type_icon }} {{ $content->type_label }}
                    </span>
                </td>
                <td class="px-6 py-3.5 text-sm text-zinc-500">
                    {{ $content->week ? 'Sem. ' . $content->week : 'Général' }}
                </td>
                <td class="px-6 py-3.5">
                    @if($content->url)
                    <a href="{{ $content->url }}" target="_blank"
                       class="text-xs text-rose-400 hover:underline truncate block max-w-[180px]">
                        {{ Str::limit($content->url, 35) }}
                    </a>
                    @else
                    <span class="text-xs text-zinc-300">—</span>
                    @endif
                </td>
                <td class="px-6 py-3.5">
                    <div class="flex items-center gap-1 justify-end">
                        <button @click="openEdit({{ $editPayload }})"
                                class="w-8 h-8 rounded-lg hover:bg-zinc-100 text-zinc-300 hover:text-zinc-600 transition-colors text-sm flex items-center justify-center">
                            ✎
                        </button>
                        <button @click="openDelete({{ $content->id }})"
                                class="w-8 h-8 rounded-lg hover:bg-rose-50 text-zinc-300 hover:text-rose-400 transition-colors text-sm flex items-center justify-center">
                            ✕
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-400">
                    Aucun contenu ·
                    <button @click="showAdd = true" class="text-rose-400 hover:underline">Ajouter le premier</button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($contents->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100">
        {{ $contents->links() }}
    </div>
    @endif
</div>


{{-- ====== MODALES ====== --}}

{{-- MODALE AJOUT --}}
<div x-show="showAdd"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="showAdd = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showAdd"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-7 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif font-light text-xl">Nouveau contenu</h2>
            <button @click="showAdd = false" class="w-8 h-8 rounded-lg hover:bg-zinc-100 text-zinc-400 flex items-center justify-center">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.contents.store') }}" class="space-y-4">
            @csrf
            @include('Admin::contents._form_fields')
            <button type="submit"
                    class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3 rounded-xl transition-colors text-sm">
                Enregistrer
            </button>
        </form>
    </div>
</div>

{{-- MODALE ÉDITION --}}
<div x-show="showEdit"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="showEdit = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showEdit"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-7 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif font-light text-xl">Modifier le contenu</h2>
            <button @click="showEdit = false" class="w-8 h-8 rounded-lg hover:bg-zinc-100 text-zinc-400 flex items-center justify-center">✕</button>
        </div>

        <form method="POST" :action="`/admin/contenus/${editData.id}`" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Titre</label>
                <input type="text" name="title" :value="editData.title"
                       class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
            </div>

            <div>
                <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Description</label>
                <textarea name="description" rows="3" x-text="editData.description"
                          class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Type</label>
                    <select name="type"
                            class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 bg-white transition">
                        <option value="article" :selected="editData.type === 'article'">📄 Article</option>
                        <option value="video"   :selected="editData.type === 'video'">🎬 Vidéo</option>
                        <option value="audio"   :selected="editData.type === 'audio'">🎧 Audio</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Semaine (1–40)</label>
                    <input type="number" name="week" :value="editData.week"
                           min="1" max="40" placeholder="Général si vide"
                           class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
                </div>
            </div>

            <div>
                <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">URL du contenu</label>
                <input type="url" name="url" :value="editData.url" placeholder="https://..."
                       class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
            </div>

            <div>
                <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">URL miniature (optionnel)</label>
                <input type="url" name="thumbnail" :value="editData.thumbnail" placeholder="https://..."
                       class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
            </div>

            <button type="submit"
                    class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3 rounded-xl transition-colors text-sm">
                Sauvegarder
            </button>
        </form>
    </div>
</div>

{{-- MODALE SUPPRESSION --}}
<div x-show="showDelete"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="showDelete = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showDelete"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-7 text-center">
        <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center text-2xl mx-auto mb-4">🗑️</div>
        <h2 class="font-serif font-light text-xl mb-2">Supprimer ce contenu ?</h2>
        <p class="text-sm text-zinc-400 mb-6">Cette action est irréversible.</p>
        <div class="flex gap-3">
            <button @click="showDelete = false"
                    class="flex-1 px-4 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-600 hover:bg-zinc-50 transition-colors">
                Annuler
            </button>
            <form method="POST" :action="`/admin/contenus/${deleteId}`" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-3 rounded-xl bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

</div>{{-- fin x-data --}}
@endsection