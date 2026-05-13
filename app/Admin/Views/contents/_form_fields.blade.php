<div>
    <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Titre</label>
    <input type="text" name="title" value="{{ old('title') }}"
           placeholder="Titre du contenu"
           class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
</div>

<div>
    <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Description</label>
    <textarea name="description" rows="3" placeholder="Brève description…"
              class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition resize-none">{{ old('description') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Type</label>
        <select name="type"
                class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 bg-white transition">
            <option value="article">📄 Article</option>
            <option value="video">🎬 Vidéo</option>
            <option value="audio">🎧 Audio</option>
        </select>
    </div>
    <div>
        <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">Semaine (1–40)</label>
        <input type="number" name="week" value="{{ old('week') }}"
               min="1" max="40" placeholder="Général si vide"
               class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
    </div>
</div>

<div>
    <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">URL du contenu</label>
    <input type="url" name="url" value="{{ old('url') }}"
           placeholder="https://..."
           class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
</div>

<div>
    <label class="text-xs text-zinc-400 uppercase tracking-wider block mb-1.5">URL miniature (optionnel)</label>
    <input type="url" name="thumbnail" value="{{ old('thumbnail') }}"
           placeholder="https://..."
           class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-100 transition">
</div>