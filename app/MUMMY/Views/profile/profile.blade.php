<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Compléter mon profil — MamaCare+</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>body{font-family:'DM Sans',sans-serif;}.font-serif{font-family:'Fraunces',serif;}</style>
</head>
<body class="min-h-screen bg-stone-50 flex">

<x-flash />

{{-- IMAGE GAUCHE --}}
<div class="hidden lg:block w-1/2 relative bg-stone-900 overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-40"
         style="background-image: url('{{ asset('mummy/mamecarebglogin.png') }}')"></div>
    <div class="absolute inset-0 flex flex-col justify-end p-12">
        <p class="font-serif font-light text-white/90 text-4xl leading-snug mb-3">
            Votre voyage<br><em class="italic text-rose-300">commence ici</em>
        </p>
        <p class="text-white/50 text-sm max-w-xs">
            Renseignez quelques informations pour personnaliser votre suivi de grossesse.
        </p>
    </div>
</div>

{{-- FORMULAIRE --}}
<div class="w-full lg:w-1/2 flex items-center justify-center p-8">
    <div class="w-full max-w-md">

        <div class="mb-8">
            <div class="inline-flex items-center gap-2 font-serif text-lg mb-6">
                <span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span>MamaCare+
            </div>
            <h1 class="font-serif font-light text-3xl text-stone-800 mb-2">Bienvenue 👶</h1>
            <p class="text-stone-400 text-sm">Complétez votre profil pour commencer le suivi</p>
        </div>

        @if($errors->any())
        <div class="mb-5 p-4 bg-rose-50 border border-rose-100 rounded-xl text-sm text-rose-600">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('mummy.profile.add') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       placeholder="Ex: Aïssata Mariam"
                       class="w-full px-4 py-3 bg-white border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
            </div>

            <div>
                <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Téléphone</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                       placeholder="+224 620 000 000"
                       class="w-full px-4 py-3 bg-white border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
            </div>

            <div>
                <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">
                    Début de grossesse
                </label>
                <input type="date" name="pregnancy_start" value="{{ old('pregnancy_start') }}"
                       max="{{ now()->format('Y-m-d') }}"
                       class="w-full px-4 py-3 bg-white border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
                <p class="text-xs text-stone-300 mt-1.5">
                    La semaine de grossesse sera calculée automatiquement.
                </p>
            </div>

            <button type="submit"
                    class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3.5 rounded-xl transition-colors text-sm mt-2">
                Commencer mon suivi →
            </button>
        </form>

    </div>
</div>

</body>
</html>