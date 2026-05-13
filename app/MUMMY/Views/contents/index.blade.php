@extends('MUMMY::layouts.app')

@section('content')

<div x-data="{ showRead: false, readData: { title: '', description: '', url: '', type: '' } }">

{{-- HEADER --}}
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="font-serif font-light text-3xl">
            Contenus <em class="italic text-rose-400">semaine {{ $week }}</em>
        </h1>
        <p class="text-sm text-stone-400 mt-1">
            @if($pregnancy)
                {{ $pregnancy->trimester }} · {{ $pregnancy->weeks_remaining }} semaines restantes
            @else
                Parcourez les contenus par semaine
            @endif
        </p>
    </div>

    {{-- Lien admin --}}
    @if(Auth::user()->is_admin)
    <a href="{{ route('admin.contents.index') }}"
       class="text-xs text-stone-300 hover:text-rose-400 transition-colors flex items-center gap-1">
        ⚙ Gérer les contenus
    </a>
    @endif
</div>

{{-- FILTRE SEMAINES --}}
<div class="mb-8 overflow-x-auto">
    <div class="flex gap-2 pb-1" style="width: max-content">
        <a href="{{ route('mummy.contents.index') }}"
           class="px-4 py-2 rounded-xl text-xs font-medium transition-colors whitespace-nowrap
                  {{ request()->routeIs('mummy.contents.index') ? 'bg-rose-400 text-white' : 'bg-white border border-stone-100 text-stone-500 hover:border-rose-200' }}">
            Ma semaine ({{ $pregnancy?->current_week ?? '—' }})
        </a>
        @foreach($availableWeeks as $w)
        <a href="{{ route('mummy.contents.week', $w) }}"
           class="px-4 py-2 rounded-xl text-xs font-medium transition-colors whitespace-nowrap
                  {{ request()->route('week') == $w ? 'bg-rose-400 text-white' : 'bg-white border border-stone-100 text-stone-500 hover:border-rose-200' }}">
            Sem. {{ $w }}
        </a>
        @endforeach
    </div>
</div>

{{-- CONTENUS groupés par type --}}
@if($contents->isEmpty())
<div class="bg-white rounded-2xl border border-stone-100 p-16 text-center">
    <div class="text-5xl mb-4">📭</div>
    <p class="font-serif font-light text-xl text-stone-400 mb-2">Aucun contenu pour cette semaine</p>
    <p class="text-sm text-stone-300">Revenez bientôt ou explorez une autre semaine.</p>
</div>
@else

{{-- VIDÉOS --}}
@if($contents->has('video'))
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="text-xs font-medium text-violet-400 uppercase tracking-wider">🎬 Vidéos</span>
        <div class="flex-1 h-px bg-stone-100"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($contents['video'] as $content)
            @include('MUMMY::contents._content_card', ['content' => $content])
        @endforeach
    </div>
</div>
@endif

{{-- AUDIO --}}
@if($contents->has('audio'))
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="text-xs font-medium text-amber-400 uppercase tracking-wider">🎧 Audio</span>
        <div class="flex-1 h-px bg-stone-100"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($contents['audio'] as $content)
            @include('MUMMY::contents._content_card', ['content' => $content])
        @endforeach
    </div>
</div>
@endif

{{-- ARTICLES --}}
@if($contents->has('article'))
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="text-xs font-medium text-rose-400 uppercase tracking-wider">📄 Articles</span>
        <div class="flex-1 h-px bg-stone-100"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($contents['article'] as $content)
            @include('MUMMY::contents._content_card', ['content' => $content])
        @endforeach
    </div>
</div>
@endif

@endif


{{-- MODALE LECTURE --}}
<div x-show="showRead"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showRead = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showRead"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-7">

        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-2">
                <span x-text="readData.icon" class="text-xl"></span>
                <span x-text="readData.typeLabel"
                      class="text-xs font-medium px-2 py-0.5 rounded-full bg-stone-100 text-stone-500"></span>
            </div>
            <button @click="showRead = false"
                    class="w-8 h-8 rounded-lg hover:bg-stone-100 text-stone-400 flex items-center justify-center transition-colors">✕</button>
        </div>

        <h2 class="font-serif font-light text-xl mb-3" x-text="readData.title"></h2>
        <p class="text-sm text-stone-500 leading-relaxed mb-6" x-text="readData.description"></p>

        <template x-if="readData.url">
            <a :href="readData.url" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2 w-full bg-stone-900 hover:bg-stone-800 text-white text-sm font-medium py-3 rounded-xl transition-colors">
                <span x-text="readData.type === 'video' ? '▶ Regarder la vidéo' : readData.type === 'audio' ? '🎧 Écouter' : '📄 Lire l\'article'"></span>
            </a>
        </template>
    </div>
</div>

</div>{{-- fin x-data --}}
@endsection