<div>
    <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Titre</label>
    <input type="text" name="title"
           placeholder="Ex: Consultation obstétricale"
           class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
</div>

<div>
    <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Date & heure</label>
    <input type="datetime-local" name="date"
           class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
</div>

<div>
    <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Type</label>
    <div class="grid grid-cols-3 gap-2">
        @foreach([
            ['value' => 'rdv',       'label' => 'Rendez-vous', 'icon' => '🏥'],
            ['value' => 'medicament', 'label' => 'Médicament',  'icon' => '💊'],
            ['value' => 'vaccin',     'label' => 'Vaccin',      'icon' => '💉'],
        ] as $t)
        <label class="cursor-pointer border border-stone-200 rounded-xl p-3 text-center transition-all hover:border-rose-300 has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50">
            <input type="radio" name="type" value="{{ $t['value'] }}" class="sr-only">
            <div class="text-xl mb-1">{{ $t['icon'] }}</div>
            <div class="text-xs font-medium text-stone-600">{{ $t['label'] }}</div>
        </label>
        @endforeach
    </div>
</div>