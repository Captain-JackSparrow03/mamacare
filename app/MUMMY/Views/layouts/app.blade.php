<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MamaCare+</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
    body { font-family: 'DM Sans', sans-serif; background: #FAF8F5; color: #2C2420; }
    .font-serif { font-family: 'Fraunces', serif; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex min-h-screen">

<x-flash />

<!-- SIDEBAR -->
<aside class="w-56 flex-shrink-0 bg-white border-r border-stone-100 flex flex-col py-6">

    <div class="px-5 pb-5 border-b border-stone-100">
        <div class="font-serif text-xl flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span>
            MamaCare+
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-0.5">
        @php
            $navItems = [
                ['route' => 'mummy.dashboard', 'label' => 'Dashboard',  'icon' => '◈'],
                ['route' => 'mummy.baby.index', 'label' => 'Grossesse', 'icon' => '♡'],
                ['route' => 'mummy.reminders.index', 'label' => 'Rappels', 'icon' => '◷'],
                ['route' => 'mummy.contents.index', 'label' => 'Contenus', 'icon' => '✦'],
                ['route' => 'mummy.notes.index', 'label' => 'Notes', 'icon' => '✎'],
                ['route' => 'mummy.profile',   'label' => 'Profil',     'icon' => '◉'],
            ];
        @endphp

        @foreach($navItems as $item)
        @php
            $isActive = $item['route'] !== '#' && request()->routeIs($item['route']);
        @endphp
        <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}"
           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all
                  {{ $isActive ? 'bg-rose-50 text-rose-500 font-medium' : 'text-stone-400 hover:bg-stone-50 hover:text-stone-700' }}">
            <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    <div class="px-5 pt-4 border-t border-stone-100 flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-xs font-medium text-rose-500">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
        </div>
        <div>
            <div class="text-xs font-medium text-stone-700">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs text-stone-400 hover:text-rose-400 transition-colors">Déconnexion</button>
            </form>
        </div>
    </div>
</aside>

<!-- CONTENT -->
<main class="flex-1 px-10 py-8 overflow-x-hidden">
    @yield('content')
</main>

</body>
</html>