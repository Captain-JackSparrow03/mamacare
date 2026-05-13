@php
    $colors = [
        'rose'    => 'bg-rose-50 text-rose-400',
        'amber'   => 'bg-amber-50 text-amber-400',
        'emerald' => 'bg-emerald-50 text-emerald-500',
    ];
    $badgeColors = [
        'rose'    => 'bg-rose-50 text-rose-400',
        'amber'   => 'bg-amber-50 text-amber-400',
        'emerald' => 'bg-emerald-50 text-emerald-500',
    ];
    $c = $colors[$reminder->type_color] ?? $colors['rose'];

    // Données sérialisées pour Alpine
    $editPayload = json_encode([
        'id'    => $reminder->id,
        'title' => $reminder->title,
        'date'  => \Carbon\Carbon::parse($reminder->date)->format('Y-m-d\TH:i'),
        'type'  => $reminder->type,
    ]);
@endphp

<div class="reminder-card bg-white rounded-2xl border border-stone-100 p-4 flex items-center gap-4 transition-opacity duration-300
            {{ $reminder->is_done ? 'opacity-50' : '' }}
            {{ $overdue ? 'border-l-4 border-l-amber-300' : '' }}">

    {{-- Icône --}}
    <div class="w-10 h-10 rounded-xl {{ $c }} flex items-center justify-center text-lg flex-shrink-0">
        {{ $reminder->type_icon }}
    </div>

    {{-- Contenu --}}
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-stone-700 truncate {{ $reminder->is_done ? 'line-through text-stone-400' : '' }}">
            {{ $reminder->title }}
        </p>
        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
            <span class="text-xs text-stone-400">
                {{ \Carbon\Carbon::parse($reminder->date)->translatedFormat('j M Y · H\hi') }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeColors[$reminder->type_color] ?? '' }}">
                {{ $reminder->type_label }}
            </span>
            @if($overdue)
                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-500 font-medium">En retard</span>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-1 flex-shrink-0">

        {{-- Toggle --}}
        <form method="POST" action="{{ route('mummy.reminders.toggle', $reminder) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    title="{{ $reminder->is_done ? 'Marquer non terminé' : 'Marquer comme fait' }}"
                    class="w-8 h-8 rounded-lg hover:bg-emerald-50 text-stone-300 hover:text-emerald-500 transition-colors text-sm flex items-center justify-center">
                {{ $reminder->is_done ? '↩' : '✓' }}
            </button>
        </form>

        {{-- Éditer — passe les données à Alpine via @click --}}
        <button type="button"
                @click="openEdit({{ $editPayload }})"
                title="Modifier"
                class="w-8 h-8 rounded-lg hover:bg-stone-100 text-stone-300 hover:text-stone-500 transition-colors text-sm flex items-center justify-center">
            ✎
        </button>

        {{-- Supprimer --}}
        <button type="button"
                @click="openDelete({{ $reminder->id }})"
                title="Supprimer"
                class="w-8 h-8 rounded-lg hover:bg-rose-50 text-stone-300 hover:text-rose-400 transition-colors text-sm flex items-center justify-center">
            ✕
        </button>
    </div>
</div>