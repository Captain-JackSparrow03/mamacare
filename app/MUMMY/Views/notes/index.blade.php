@extends('MUMMY::layouts.app')

@section('content')

<div x-data="{
    showAdd:    false,
    showEdit:   false,
    showDelete: false,
    editData:   { id: null, content: '' },
    deleteId:   null,
    openEdit(data)   { this.editData = data; this.showEdit = true; },
    openDelete(id)   { this.deleteId = id;   this.showDelete = true; }
}">

{{-- HEADER --}}
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="font-serif font-light text-3xl">Mon <em class="italic text-rose-400">Journal</em></h1>
        <p class="text-sm text-stone-400 mt-1">Vos pensées, ressentis et souvenirs de grossesse</p>
    </div>
    <button @click="showAdd = true"
            class="flex items-center gap-2 bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
        <span class="text-lg leading-none">+</span> Nouvelle note
    </button>
</div>

{{-- STATS --}}
<div class="grid grid-cols-2 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-stone-100 p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-400 text-lg">✎</div>
        <div>
            <div class="font-serif text-2xl font-light">{{ $totalNotes }}</div>
            <div class="text-xs text-stone-400">Notes écrites</div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-stone-100 p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-400 text-lg">◉</div>
        <div>
            <div class="font-serif text-2xl font-light">{{ number_format($totalWords) }}</div>
            <div class="text-xs text-stone-400">Mots écrits</div>
        </div>
    </div>
</div>

{{-- NOTES groupées par mois --}}
@forelse($notes as $yearMonth => $monthNotes)
    @php
        $label = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->translatedFormat('F Y');
    @endphp

    <div class="mb-8">
        {{-- Séparateur mois --}}
        <div class="flex items-center gap-3 mb-4">
            <span class="text-xs font-medium text-stone-400 uppercase tracking-wider">{{ $label }}</span>
            <div class="flex-1 h-px bg-stone-100"></div>
            <span class="text-xs text-stone-300">{{ $monthNotes->count() }} note{{ $monthNotes->count() > 1 ? 's' : '' }}</span>
        </div>

        {{-- Grille de notes --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($monthNotes as $note)
            @php
                // Couleurs de fond alternées façon post-it doux
                $palettes = [
                    'bg-white',
                    'bg-rose-50/60',
                    'bg-amber-50/60',
                    'bg-emerald-50/60',
                    'bg-sky-50/60',
                ];
                $bg = $palettes[$note->id % count($palettes)];
                // Données pour Alpine
                $editPayload = json_encode([
                    'id'      => $note->id,
                    'content' => $note->content,
                ]);
            @endphp

            <div class="note-card {{ $bg }} rounded-2xl border border-stone-100 p-5 flex flex-col gap-3 group transition-shadow hover:shadow-sm">

                {{-- Contenu --}}
                <p class="text-sm text-stone-600 leading-relaxed flex-1 whitespace-pre-wrap">{{ $note->preview }}</p>

                @if(strlen($note->content) > 120)
                    <button @click="openEdit({{ $editPayload }})"
                            class="text-xs text-stone-300 hover:text-rose-400 transition-colors text-left">
                        Lire la suite →
                    </button>
                @endif

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-2 border-t border-stone-100">
                    <div>
                        <span class="text-xs text-stone-300">{{ $note->readable_date }}</span>
                        <span class="text-xs text-stone-200 ml-2">· {{ $note->word_count }} mots</span>
                    </div>

                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        {{-- Éditer --}}
                        <button @click="openEdit({{ $editPayload }})"
                                title="Modifier"
                                class="w-7 h-7 rounded-lg hover:bg-stone-100 text-stone-300 hover:text-stone-500 transition-colors text-xs flex items-center justify-center">
                            ✎
                        </button>
                        {{-- Supprimer --}}
                        <button @click="openDelete({{ $note->id }})"
                                title="Supprimer"
                                class="w-7 h-7 rounded-lg hover:bg-rose-50 text-stone-300 hover:text-rose-400 transition-colors text-xs flex items-center justify-center">
                            ✕
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

@empty
    {{-- État vide --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-16 text-center">
        <div class="text-5xl mb-4">📓</div>
        <p class="font-serif font-light text-xl text-stone-400 mb-2">Votre journal est vide</p>
        <p class="text-sm text-stone-300 mb-6">Commencez à noter vos ressentis, vos questions, vos petits bonheurs du quotidien.</p>
        <button @click="showAdd = true"
                class="inline-flex items-center gap-2 bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
            <span>+</span> Écrire ma première note
        </button>
    </div>
@endforelse


{{-- ======= MODALES ======= --}}

{{-- MODALE AJOUT --}}
<div x-show="showAdd"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showAdd = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showAdd"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-7">

        <div class="flex items-center justify-between mb-5">
            <h2 class="font-serif font-light text-xl">Nouvelle note</h2>
            <button @click="showAdd = false"
                    class="w-8 h-8 rounded-lg hover:bg-stone-100 text-stone-400 flex items-center justify-center transition-colors">✕</button>
        </div>

        <form method="POST" action="{{ route('mummy.notes.store') }}">
            @csrf
            <div class="mb-4">
                <textarea name="content" rows="7"
                          placeholder="Écrivez vos pensées, ressentis, questions du moment…"
                          class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition resize-none leading-relaxed"
                          autofocus></textarea>
                <p class="text-xs text-stone-300 mt-1.5 text-right">Max 5 000 caractères</p>
            </div>
            <button type="submit"
                    class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3 rounded-xl transition-colors text-sm">
                Enregistrer la note
            </button>
        </form>
    </div>
</div>


{{-- MODALE ÉDITION --}}
<div x-show="showEdit"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showEdit = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showEdit"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-7">

        <div class="flex items-center justify-between mb-5">
            <h2 class="font-serif font-light text-xl">Modifier la note</h2>
            <button @click="showEdit = false"
                    class="w-8 h-8 rounded-lg hover:bg-stone-100 text-stone-400 flex items-center justify-center transition-colors">✕</button>
        </div>

        <form method="POST" :action="`/notes/${editData.id}`">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <textarea name="content" rows="7"
                          x-text="editData.content"
                          placeholder="Vos pensées…"
                          class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition resize-none leading-relaxed"></textarea>
                <p class="text-xs text-stone-300 mt-1.5 text-right">Max 5 000 caractères</p>
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
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showDelete = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showDelete"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-7 text-center">

        <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center text-2xl mx-auto mb-4">🗑️</div>
        <h2 class="font-serif font-light text-xl mb-2">Supprimer cette note ?</h2>
        <p class="text-sm text-stone-400 mb-6">Cette action est irréversible.</p>

        <div class="flex gap-3">
            <button @click="showDelete = false"
                    class="flex-1 px-4 py-3 rounded-xl border border-stone-200 text-sm font-medium text-stone-600 hover:bg-stone-50 transition-colors">
                Annuler
            </button>
            <form method="POST" :action="`/notes/${deleteId}`" class="flex-1">
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