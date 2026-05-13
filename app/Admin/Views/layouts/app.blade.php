<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin — MamaCare+</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  body { font-family: 'DM Sans', sans-serif; background: #F4F4F5; color: #18181B; }
  .font-serif { font-family: 'Fraunces', serif; }
</style>
</head>

<body class="flex min-h-screen">

<x-flash />

{{-- SIDEBAR ADMIN --}}
<aside class="w-60 flex-shrink-0 bg-zinc-900 flex flex-col py-6">

    {{-- Logo --}}
    <div class="px-5 pb-5 border-b border-zinc-800">
        <div class="font-serif text-lg text-white flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span>
            MamaCare+
        </div>
        <div class="text-xs text-zinc-500 mt-1 pl-4">Administration</div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">
        @php
            $adminNav = [
                ['route' => 'admin.dashboard',      'label' => 'Dashboard',  'icon' => '◈'],
                ['route' => 'admin.contents.index',  'label' => 'Contenus',   'icon' => '✦'],
            ];
        @endphp

        @foreach($adminNav as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm transition-all
                  {{ $active
                      ? 'bg-rose-500/20 text-rose-400 font-medium'
                      : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-100' }}">
            <span class="w-5 text-center text-base">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- Séparateur --}}
    <div class="px-5 py-3 border-t border-zinc-800">
        <a href="{{ route('mummy.dashboard') }}"
           class="flex items-center gap-2 text-xs text-zinc-500 hover:text-zinc-300 transition-colors mb-3">
            <span>←</span> Retour app maman
        </a>
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-full bg-rose-500/20 flex items-center justify-center text-xs font-medium text-rose-400">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div>
                <div class="text-xs font-medium text-zinc-300">{{ auth()->user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-xs text-zinc-600 hover:text-rose-400 transition-colors">Déconnexion</button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- TOPBAR --}}
<div class="flex-1 flex flex-col">
    <header class="bg-white border-b border-zinc-100 px-8 py-3.5 flex items-center justify-between">
        <div class="text-xs text-zinc-400">
            Administration · <span class="text-zinc-600">@yield('breadcrumb', 'Dashboard')</span>
        </div>
        <div class="flex items-center gap-2 text-xs text-zinc-400">
            <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
            Système opérationnel
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="flex-1 px-8 py-8 overflow-x-hidden">
        @yield('content')
    </main>
</div>

</body>
</html>