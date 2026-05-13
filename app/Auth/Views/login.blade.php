<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion — MamaCare+</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>body{font-family:'DM Sans',sans-serif;}.font-serif{font-family:'Fraunces',serif;}</style>
</head>
<body class="min-h-screen flex items-center justify-center bg-stone-50 relative overflow-hidden">

<x-flash />

{{-- Fond décoratif --}}
<div class="absolute top-0 left-0 w-72 h-72 rounded-full bg-rose-50 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
<div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-stone-100 translate-x-1/3 translate-y-1/3 pointer-events-none"></div>

<div class="relative w-full max-w-sm mx-auto px-6">

    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 font-serif text-xl mb-6">
            <span class="w-2 h-2 rounded-full bg-rose-400"></span>MamaCare+
        </div>
        <h1 class="font-serif font-light text-3xl text-stone-800 mb-2">Bon retour</h1>
        <p class="text-stone-400 text-sm">Entrez votre email pour recevoir un code de connexion</p>
    </div>

    {{-- Étape 1 : email --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-6 shadow-sm">

        @if($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-100 rounded-xl text-sm text-rose-600">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('send.otp') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', session('email')) }}"
                       placeholder="votre@email.com"
                       class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition"
                       required>
            </div>
            <button class="w-full bg-stone-900 hover:bg-stone-800 text-white text-sm font-medium py-3 rounded-xl transition-colors">
                Recevoir un code →
            </button>
        </form>

        {{-- Étape 2 : OTP --}}
        @if(session('otp_sent') && session('email'))
        <div class="mt-5 pt-5 border-t border-stone-100">
            <p class="text-xs text-stone-400 mb-3 text-center">
                Code envoyé à <strong class="text-stone-600">{{ session('email') }}</strong>
            </p>
            <form method="POST" action="{{ route('verify.otp') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">
                <div>
                    <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Code à 6 chiffres</label>
                    <input type="text" name="code" placeholder="_ _ _ _ _ _"
                           maxlength="6" inputmode="numeric"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm text-center tracking-[.5em] font-mono focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
                </div>
                <button class="w-full bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium py-3 rounded-xl transition-colors">
                    Vérifier le code ✓
                </button>
            </form>
        </div>
        @endif

    </div>

    <p class="text-center text-xs text-stone-300 mt-6">
        Pas de mot de passe. Connexion sécurisée par email.
    </p>
</div>

</body>
</html>