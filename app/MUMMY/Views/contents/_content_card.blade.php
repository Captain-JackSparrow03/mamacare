@php
    $bgColors = [
        'rose'   => 'bg-rose-50',
        'violet' => 'bg-violet-50',
        'amber'  => 'bg-amber-50',
    ];
    $textColors = [
        'rose'   => 'text-rose-400',
        'violet' => 'text-violet-400',
        'amber'  => 'text-amber-400',
    ];
    $bg   = $bgColors[$content->type_color]   ?? 'bg-stone-50';
    $text = $textColors[$content->type_color] ?? 'text-stone-400';

    $readPayload = json_encode([
        'title'     => $content->title,
        'description' => $content->description ?? '',
        'url'       => $content->url ?? '',
        'type'      => $content->type,
        'typeLabel' => $content->type_label,
        'icon'      => $content->type_icon,
    ]);
@endphp

<div class="bg-white rounded-2xl border border-stone-100 p-5 flex flex-col gap-3 hover:shadow-sm transition-shadow group cursor-pointer"
     @click="readData = {{ $readPayload }}; showRead = true">

    {{-- Badge type --}}
    <div class="flex items-center justify-between">
        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full {{ $bg }} {{ $text }}">
            {{ $content->type_icon }} {{ $content->type_label }}
        </span>
        @if($content->week)
        <span class="text-xs text-stone-300">Sem. {{ $content->week }}</span>
        @else
        <span class="text-xs text-stone-300">Général</span>
        @endif
    </div>

    {{-- Titre --}}
    <h3 class="font-serif font-light text-base text-stone-700 leading-snug group-hover:text-rose-400 transition-colors">
        {{ $content->title }}
    </h3>

    {{-- Description --}}
    @if($content->description)
    <p class="text-xs text-stone-400 leading-relaxed line-clamp-2">
        {{ $content->description }}
    </p>
    @endif

    {{-- Footer --}}
    <div class="flex items-center justify-between pt-2 border-t border-stone-50 mt-auto">
        <span class="text-xs text-stone-300">Voir le contenu →</span>
        @if($content->url)
        <span class="w-6 h-6 rounded-lg {{ $bg }} {{ $text }} flex items-center justify-center text-xs">→</span>
        @endif
    </div>
</div>