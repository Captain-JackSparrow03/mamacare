@extends('MUMMY::layouts.app')

@section('content')

<div x-data="{ activeTab: 'info' }">

{{-- HEADER --}}
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="font-serif font-light text-3xl">
            Mon <em class="italic text-rose-400">Bébé</em>
        </h1>
        <p class="text-sm text-stone-400 mt-1">
            Suivi du développement semaine par semaine
        </p>
    </div>
    @if($pregnancy)
    <div class="text-right">
        <div class="text-xs text-stone-400 mb-1">Votre semaine actuelle</div>
        <div class="inline-flex items-center gap-2 bg-rose-50 text-rose-400 font-medium text-sm px-4 py-2 rounded-xl">
            Semaine {{ $pregnancy->current_week }}
            @if($week !== $pregnancy->current_week)
                <span class="text-rose-300">·</span>
                <a href="{{ route('mummy.baby.index') }}" class="text-xs underline underline-offset-2">
                    Retour ma semaine
                </a>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- NAVIGATION SEMAINES --}}
<div class="mb-8 overflow-x-auto">
    <div class="flex gap-1.5 pb-1" style="width: max-content">
        @foreach(range(1, 40) as $w)
        @php
            $isCurrent  = $pregnancy && $w === $pregnancy->current_week;
            $isSelected = $w === $week;
        @endphp
        <a href="{{ route('mummy.baby.week', $w) }}"
           class="w-9 h-9 rounded-xl text-xs font-medium flex items-center justify-center transition-all flex-shrink-0
                  {{ $isSelected
                      ? 'bg-stone-900 text-white shadow-sm'
                      : ($isCurrent
                          ? 'bg-rose-100 text-rose-500 ring-2 ring-rose-300'
                          : 'bg-white border border-stone-100 text-stone-400 hover:border-rose-200 hover:text-rose-400') }}">
            {{ $w }}
        </a>
        @endforeach
    </div>
</div>

{{-- CARD PRINCIPALE --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

    {{-- GAUCHE — Visuel bébé --}}
    <div class="lg:col-span-2">
        <div class="bg-stone-900 rounded-2xl p-8 text-center relative overflow-hidden h-full flex flex-col items-center justify-center min-h-[280px]">
            {{-- Cercles déco --}}
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-rose-400/10 pointer-events-none"></div>
            <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full bg-amber-400/10 pointer-events-none"></div>

            {{-- Emoji fruit --}}
            <div class="text-8xl mb-4 relative z-10">{{ $babyData['emoji'] }}</div>

            <p class="font-serif font-light text-white text-lg mb-1 z-10 relative">
                {{ $babyData['fruit'] }}
            </p>
            <p class="text-white/40 text-xs z-10 relative">
                Semaine {{ $week }} sur 40
            </p>

            {{-- Barre trimestre --}}
            <div class="w-full mt-6 z-10 relative">
                <div class="flex justify-between text-[10px] text-white/30 mb-1.5">
                    <span>T1</span><span>T2</span><span>T3</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-1">
                    <div class="bg-rose-400 h-1 rounded-full transition-all duration-500"
                         style="width: {{ round(($week / 40) * 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- DROITE — Données --}}
    <div class="lg:col-span-3 flex flex-col gap-4">

        {{-- Semaine + stats --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="text-xs text-stone-400 uppercase tracking-wider mb-1">Semaine {{ $week }}</div>
                    <h2 class="font-serif font-light text-2xl text-stone-800">
                        {{ $babyData['fruit'] }}
                    </h2>
                </div>
                @if($pregnancy && $week === $pregnancy->current_week)
                <span class="bg-rose-50 text-rose-400 text-xs font-medium px-3 py-1 rounded-full">
                    Votre semaine ✨
                </span>
                @endif
            </div>

            {{-- Mesures --}}
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div class="bg-stone-50 rounded-xl p-4 text-center">
                    <div class="text-xs text-stone-400 uppercase tracking-wider mb-1">Taille</div>
                    <div class="font-serif text-2xl font-light text-stone-700">
                        {{ $babyData['size'] < 1
                            ? round($babyData['size'] * 10, 1) . ' mm'
                            : $babyData['size'] . ' cm' }}
                    </div>
                </div>
                <div class="bg-stone-50 rounded-xl p-4 text-center">
                    <div class="text-xs text-stone-400 uppercase tracking-wider mb-1">Poids</div>
                    <div class="font-serif text-2xl font-light text-stone-700">
                        {{ \App\MUMMY\Data\BabyWeekData::formatWeight($babyData['weight']) }}
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <p class="text-sm text-stone-500 leading-relaxed">
                {{ $babyData['description'] }}
            </p>
        </div>

        {{-- Développements clés --}}
        <div class="bg-white rounded-2xl border border-stone-100 p-6">
            <div class="text-xs text-stone-400 uppercase tracking-wider mb-4">Développements clés</div>
            <ul class="space-y-2.5">
                @foreach($babyData['developments'] as $dev)
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-rose-50 text-rose-400 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">✓</span>
                    <span class="text-sm text-stone-600">{{ $dev }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- NAVIGATION PREV / NEXT --}}
<div class="flex items-center justify-between">
    @if($week > 1)
    <a href="{{ route('mummy.baby.week', $week - 1) }}"
       class="flex items-center gap-2 bg-white border border-stone-100 hover:border-rose-200 text-stone-500 hover:text-rose-400 text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        ← Semaine {{ $week - 1 }}
    </a>
    @else
    <div></div>
    @endif

    @if($week < 40)
    <a href="{{ route('mummy.baby.week', $week + 1) }}"
       class="flex items-center gap-2 bg-white border border-stone-100 hover:border-rose-200 text-stone-500 hover:text-rose-400 text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        Semaine {{ $week + 1 }} →
    </a>
    @endif
</div>

</div>
@endsection