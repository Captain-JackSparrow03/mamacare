@extends('MUMMY::layouts.app')

@section('content')

{{-- Composant Alpine global pour la page --}}
<div x-data="{
    showAdd:    false,
    showEdit:   false,
    showDelete: false,
    editData:   { id: null, title: '', date: '', type: '' },
    deleteId:   null,
    openEdit(data) {
        this.editData = data;
        this.showEdit = true;
    },
    openDelete(id) {
        this.deleteId = id;
        this.showDelete = true;
    }
}">

{{-- HEADER --}}
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="font-serif font-light text-3xl">Mes <em class="italic text-rose-400">Rappels</em></h1>
        <p class="text-sm text-stone-400 mt-1">Gérez vos rendez-vous, médicaments et vaccins</p>
    </div>
    <button @click="showAdd = true"
            class="flex items-center gap-2 bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
        <span class="text-lg leading-none">+</span> Nouveau rappel
    </button>
</div>

{{-- STATS --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    @php
        $totalUpcoming = $upcoming->flatten()->count();
        $totalOverdue  = $overdue->count();
        $totalDone     = $done->count();
    @endphp
    @foreach([
        ['label' => 'À venir',   'count' => $totalUpcoming, 'icon' => '◷', 'bg' => 'bg-rose-50',    'text' => 'text-rose-400'],
        ['label' => 'En retard', 'count' => $totalOverdue,  'icon' => '⚠', 'bg' => 'bg-amber-50',   'text' => 'text-amber-400'],
        ['label' => 'Terminés',  'count' => $totalDone,     'icon' => '✓', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-500'],
    ] as $s)
    <div class="bg-white rounded-2xl border border-stone-100 p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl {{ $s['bg'] }} flex items-center justify-center {{ $s['text'] }} text-lg">
            {{ $s['icon'] }}
        </div>
        <div>
            <div class="font-serif text-2xl font-light">{{ $s['count'] }}</div>
            <div class="text-xs text-stone-400">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- EN RETARD --}}
@if($overdue->isNotEmpty())
<div class="mb-6">
    <div class="flex items-center gap-3 mb-3">
        <span class="text-xs font-medium text-amber-500 uppercase tracking-wider">⚠ En retard</span>
        <div class="flex-1 h-px bg-amber-100"></div>
    </div>
    <div class="space-y-2">
        @foreach($overdue as $reminder)
            @include('MUMMY::reminders._reminder_card', ['reminder' => $reminder, 'overdue' => true])
        @endforeach
    </div>
</div>
@endif

{{-- À VENIR --}}
@if($upcoming->isNotEmpty())
<div class="mb-6">
    <div class="flex items-center gap-3 mb-3">
        <span class="text-xs font-medium text-stone-400 uppercase tracking-wider">À venir</span>
        <div class="flex-1 h-px bg-stone-100"></div>
    </div>
    @foreach($upcoming as $date => $reminders)
    <div class="mb-4">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-xs font-medium text-stone-500">
                @php
                    $d = \Carbon\Carbon::parse($date);
                    if ($d->isToday())        echo "Aujourd'hui";
                    elseif ($d->isTomorrow()) echo "Demain";
                    else                      echo $d->translatedFormat('l j M');
                @endphp
            </span>
            <div class="flex-1 h-px bg-stone-100"></div>
        </div>
        <div class="space-y-2">
            @foreach($reminders as $reminder)
                @include('MUMMY::reminders._reminder_card', ['reminder' => $reminder, 'overdue' => false])
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif

@if($upcoming->isEmpty() && $overdue->isEmpty())
<div class="bg-white rounded-2xl border border-stone-100 p-12 text-center mb-6">
    <div class="text-4xl mb-3">🎉</div>
    <p class="font-serif text-lg text-stone-400 font-light">Aucun rappel à venir</p>
    <p class="text-sm text-stone-300 mt-1">Ajoutez vos rendez-vous et médicaments</p>
</div>
@endif

{{-- TERMINÉS --}}
@if($done->isNotEmpty())
<details class="group">
    <summary class="flex items-center gap-2 cursor-pointer mb-3 list-none">
        <span class="text-xs font-medium text-stone-300 uppercase tracking-wider">Terminés ({{ $done->count() }})</span>
        <span class="text-stone-300 text-xs group-open:rotate-180 transition-transform inline-block">▾</span>
        <div class="flex-1 h-px bg-stone-100"></div>
    </summary>
    <div class="space-y-2 mt-3">
        @foreach($done as $reminder)
            @include('MUMMY::reminders._reminder_card', ['reminder' => $reminder, 'overdue' => false])
        @endforeach
    </div>
</details>
@endif


{{-- ================= MODALES ================= --}}

{{-- MODALE AJOUT --}}
<div x-show="showAdd"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showAdd = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showAdd"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-7">

        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif font-light text-xl">Nouveau rappel</h2>
            <button @click="showAdd = false" class="w-8 h-8 rounded-lg hover:bg-stone-100 text-stone-400 flex items-center justify-center transition-colors">✕</button>
        </div>

        <form method="POST" action="{{ route('mummy.reminders.store') }}" class="space-y-4">
            @csrf
            @include('MUMMY::reminders._reminder_form_fields')
            <button type="submit"
                    class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3 rounded-xl transition-colors text-sm">
                Enregistrer le rappel
            </button>
        </form>
    </div>
</div>


{{-- MODALE ÉDITION --}}
<div x-show="showEdit"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showEdit = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showEdit"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-7">

        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif font-light text-xl">Modifier le rappel</h2>
            <button @click="showEdit = false" class="w-8 h-8 rounded-lg hover:bg-stone-100 text-stone-400 flex items-center justify-center transition-colors">✕</button>
        </div>

        <form method="POST" :action="`/rappels/${editData.id}`" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Titre</label>
                <input type="text" name="title" :value="editData.title"
                       placeholder="Ex: Consultation obstétricale"
                       class="w-full px-4 py-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
            </div>

            <div>
                <label class="text-xs text-stone-400 uppercase tracking-wider block mb-1.5">Date & heure</label>
                <input type="datetime-local" name="date" :value="editData.date"
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
                    <label class="cursor-pointer border border-stone-200 rounded-xl p-3 text-center transition-all hover:border-rose-300"
                           :class="editData.type === '{{ $t['value'] }}' ? 'border-rose-400 bg-rose-50' : ''">
                        <input type="radio" name="type" value="{{ $t['value'] }}"
                               x-bind:checked="editData.type === '{{ $t['value'] }}'"
                               class="sr-only">
                        <div class="text-xl mb-1">{{ $t['icon'] }}</div>
                        <div class="text-xs font-medium text-stone-600">{{ $t['label'] }}</div>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3 rounded-xl transition-colors text-sm">
                Sauvegarder les modifications
            </button>
        </form>
    </div>
</div>


{{-- MODALE SUPPRESSION --}}
<div x-show="showDelete"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="showDelete = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
     style="display:none">
    <div x-show="showDelete"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-7 text-center">

        <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center text-2xl mx-auto mb-4">🗑️</div>
        <h2 class="font-serif font-light text-xl mb-2">Supprimer ce rappel ?</h2>
        <p class="text-sm text-stone-400 mb-6">Cette action est irréversible.</p>

        <div class="flex gap-3">
            <button @click="showDelete = false"
                    class="flex-1 px-4 py-3 rounded-xl border border-stone-200 text-sm font-medium text-stone-600 hover:bg-stone-50 transition-colors">
                Annuler
            </button>
            <form method="POST" :action="`/rappels/${deleteId}`" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-3 rounded-xl bg-rose-400 hover:bg-rose-500 text-white text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

</div>{{-- fin x-data --}}

@endsection