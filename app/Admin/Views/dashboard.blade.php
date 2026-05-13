@extends('Admin::layouts.app')

@section('breadcrumb', 'Dashboard')

@section('content')

<div class="mb-8">
    <h1 class="font-serif font-light text-3xl">Tableau de <em class="italic text-rose-400">bord</em></h1>
    <p class="text-sm text-zinc-400 mt-1">Vue globale de l'application</p>
</div>

{{-- STATS --}}
<div class="grid grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label' => 'Utilisatrices', 'count' => $stats['users'],     'icon' => '👩', 'color' => 'rose'],
        ['label' => 'Contenus',      'count' => $stats['contents'],  'icon' => '📖', 'color' => 'violet'],
        ['label' => 'Rappels',       'count' => $stats['reminders'], 'icon' => '◷',  'color' => 'amber'],
        ['label' => 'Notes',         'count' => $stats['notes'],     'icon' => '✎',  'color' => 'emerald'],
    ] as $s)
    <div class="bg-white rounded-2xl border border-zinc-100 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-{{ $s['color'] }}-50 flex items-center justify-center text-xl flex-shrink-0">
            {{ $s['icon'] }}
        </div>
        <div>
            <div class="font-serif text-2xl font-light">{{ $s['count'] }}</div>
            <div class="text-xs text-zinc-400">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- DERNIÈRES INSCRIPTIONS --}}
<div class="bg-white rounded-2xl border border-zinc-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between">
        <h2 class="text-sm font-medium text-zinc-700">Dernières inscriptions</h2>
        <span class="text-xs text-zinc-400">5 dernières</span>
    </div>
    <table class="w-full">
        <thead>
            <tr class="border-b border-zinc-50">
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3">Nom</th>
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3">Email</th>
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3">Profil</th>
                <th class="text-left text-xs font-medium text-zinc-400 uppercase tracking-wider px-6 py-3">Inscription</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-50">
            @forelse($latestUsers as $user)
            <tr class="hover:bg-zinc-50 transition-colors">
                <td class="px-6 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-xs text-rose-400 font-medium">
                            {{ strtoupper(substr($user->name ?? '?', 0, 2)) }}
                        </div>
                        <span class="text-sm text-zinc-700">{{ $user->name ?? '—' }}</span>
                    </div>
                </td>
                <td class="px-6 py-3.5 text-sm text-zinc-500">{{ $user->email }}</td>
                <td class="px-6 py-3.5">
                    @if($user->is_profile_completed)
                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-medium">✓ Complété</span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-500 font-medium">En attente</span>
                    @endif
                </td>
                <td class="px-6 py-3.5 text-xs text-zinc-400">
                    {{ $user->created_at->translatedFormat('j M Y') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-400">Aucun utilisateur</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection