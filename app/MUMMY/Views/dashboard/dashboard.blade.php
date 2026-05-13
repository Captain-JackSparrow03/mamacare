@extends('MUMMY::layouts.app')

@section('content')

{{-- HEADER --}}
<div class="mb-8">
    <h1 class="font-serif font-light text-3xl">
        Bonjour, <em class="italic text-rose-400">{{ auth()->user()->name }}</em> 🌿
    </h1>
    <p class="text-sm text-stone-400 mt-1">Voici votre suivi du jour</p>
</div>

@if($pregnancy)

{{-- PREGNANCY HERO --}}
<div class="bg-stone-900 rounded-2xl p-7 flex items-center gap-8 mb-6 relative overflow-hidden">
    {{-- Cercles décoratifs --}}
    <div class="absolute -right-8 -top-8 w-48 h-48 rounded-full bg-rose-400/20 pointer-events-none"></div>
    <div class="absolute right-20 -bottom-10 w-32 h-32 rounded-full bg-sage-400/10 pointer-events-none"></div>

    {{-- Semaine --}}
    <div class="w-24 h-24 rounded-full border border-white/20 flex flex-col items-center justify-center flex-shrink-0 z-10">
        <span class="font-serif text-4xl font-light text-white leading-none">{{ $pregnancy->current_week }}</span>
        <span class="text-[10px] text-white/50 tracking-widest mt-1">SEMAINE</span>
    </div>

    {{-- Infos --}}
    <div class="flex-1 z-10">
        <p class="font-serif text-white text-lg font-light mb-1">Votre bébé grandit bien</p>
        <p class="text-white/50 text-sm mb-4">
            {{ $pregnancy->trimester }} · {{ $pregnancy->weeks_remaining }} semaines restantes · Terme estimé le {{ $pregnancy->due_date }}
        </p>
        {{-- Barre de progression --}}
        <div class="w-full bg-white/10 rounded-full h-1.5">
            <div class="bg-rose-400 h-1.5 rounded-full transition-all duration-700"
                 style="width: {{ $pregnancy->progress_percent }}%"></div>
        </div>
        <div class="flex justify-between mt-1.5 text-[10px] text-white/30">
            <span>Sem. 1</span><span>T1</span><span>T2</span><span>T3</span><span>Sem. 40</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="flex gap-6 z-10">
        @foreach([
            ['num' => $pregnancy->weeks_remaining, 'label' => 'RESTANTES'],
            ['num' => $reminders->count(),          'label' => 'RAPPELS'],
            ['num' => $notesCount,                  'label' => 'NOTES'],
        ] as $s)
        <div class="text-center">
            <div class="font-serif text-2xl font-light text-white">{{ $s['num'] }}</div>
            <div class="text-[10px] text-white/40 tracking-widest">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

@else
{{-- Pas de grossesse --}}
<div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 mb-6 text-center">
    <p class="font-serif text-lg text-rose-400 mb-2">Aucune grossesse enregistrée</p>
    <p class="text-sm text-stone-400 mb-4">Complétez votre profil pour commencer le suivi.</p>
    <a href="{{ route('mummy.profile') }}" class="inline-block bg-rose-400 text-white text-sm px-5 py-2 rounded-xl hover:bg-rose-500 transition">
        Compléter le profil
    </a>
</div>
@endif

{{-- CARDS --}}
<div class="grid grid-cols-3 gap-4 mb-6">

    {{-- Prochain rappel --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-5">
        <p class="text-xs text-stone-400 uppercase tracking-wider mb-3">Prochain rappel</p>
        @if($reminders->isNotEmpty())
        @php $next = $reminders->first() @endphp
        <p class="font-serif text-base font-light mb-2">{{ $next->title }}</p>
        <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-400 text-xs font-medium px-3 py-1 rounded-full">
            📅 {{ \Carbon\Carbon::parse($next->date)->translatedFormat('j M') }}
        </span>
        @else
        <p class="text-sm text-stone-300 italic">Aucun rappel à venir</p>
        @endif
    </div>

    {{-- Conseil du jour --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-5">
        <p class="text-xs text-stone-400 uppercase tracking-wider mb-3">Conseil semaine {{ $pregnancy->current_week ?? '—' }}</p>
        <p class="font-serif text-sm font-light leading-relaxed">
            Hydratez-vous régulièrement, au moins 1,5 L d'eau par jour.
        </p>
        <span class="inline-flex items-center gap-1 mt-3 bg-emerald-50 text-emerald-600 text-xs font-medium px-3 py-1 rounded-full">
            ✦ Semaine {{ $pregnancy->current_week ?? '—' }}
        </span>
    </div>

    {{-- Humeur --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-5">
        {{-- Remplace la card "Humeur du jour" ou ajoute une 4ème --}}
        <a href="{{ route('mummy.baby.index') }}"
        class="bg-stone-900 rounded-2xl border border-stone-800 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow group">
            <p class="text-xs text-white/40 uppercase tracking-wider">Bébé cette semaine</p>

            <div class="flex items-center gap-3">
                <span class="text-4xl">{{ $babyData['emoji'] }}</span>
                <div>
                    <p class="font-serif text-white font-light text-base">{{ $babyData['fruit'] }}</p>
                    <p class="text-white/40 text-xs mt-0.5">
                        {{ $babyData['size'] < 1
                            ? round($babyData['size'] * 10, 1) . ' mm'
                            : $babyData['size'] . ' cm' }}
                        · {{ \App\MUMMY\Data\BabyWeekData::formatWeight($babyData['weight']) }}
                    </p>
                </div>
            </div>

            <p class="text-white/50 text-xs leading-relaxed line-clamp-2">
                {{ $babyData['description'] }}
            </p>

            <span class="text-xs text-rose-400 group-hover:text-rose-300 transition-colors">
                Voir le développement →
            </span>
        </a>
    </div>
</div>

{{-- BAS --}}
<div class="grid grid-cols-2 gap-4">

    {{-- Rappels --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-5">
        <p class="text-xs text-stone-400 uppercase tracking-wider mb-4">Rappels à venir</p>
        @forelse($reminders as $reminder)
        <div class="flex items-center gap-3 py-2.5 border-b border-stone-50 last:border-0">
            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                {{ $reminder->type === 'rdv' ? 'bg-rose-400' : ($reminder->type === 'medicament' ? 'bg-amber-400' : 'bg-emerald-400') }}">
            </span>
            <span class="text-sm flex-1">{{ $reminder->title }}</span>
            <span class="text-xs text-stone-300">{{ \Carbon\Carbon::parse($reminder->date)->translatedFormat('j M') }}</span>
        </div>
        @empty
        <p class="text-sm text-stone-300 italic">Aucun rappel</p>
        @endforelse
    </div>

    {{-- Accès rapide --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-5">
        <p class="text-xs text-stone-400 uppercase tracking-wider mb-4">Accès rapide</p>
        <div class="space-y-2">
            @foreach([
                ['icon' => '📋', 'label' => 'Ajouter un rappel',      'href' => '#'],
                ['icon' => '✏️', 'label' => 'Écrire une note', 'href' => route('mummy.notes.index')],
                ['icon' => '📖', 'label' => 'Contenus de la semaine', 'href' => route('mummy.contents.index')],
                ['icon' => '⚖️', 'label' => 'Suivre mon poids',        'href' => '#'],
            ] as $action)
            <a href="{{ $action['href'] }}"
               class="flex items-center gap-3 px-4 py-3 bg-stone-50 hover:bg-rose-50 rounded-xl transition-colors group">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-base shadow-sm">
                    {{ $action['icon'] }}
                </div>
                <span class="text-sm font-medium text-stone-600 group-hover:text-rose-500 transition-colors">
                    {{ $action['label'] }}
                </span>
            </a>
            @endforeach
        </div>
    </div>

</div>

@endsection