<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Note extends Model
{
    protected $fillable = ['user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Extrait les 120 premiers caractères pour la preview */
    public function getPreviewAttribute(): string
    {
        return \Str::limit($this->content, 120);
    }

    /** Nombre de mots */
    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags($this->content));
    }

    /** Date lisible */
    public function getReadableDateAttribute(): string
    {
        $date = Carbon::parse($this->created_at);
        if ($date->isToday())     return 'Aujourd\'hui · ' . $date->format('H:i');
        if ($date->isYesterday()) return 'Hier · ' . $date->format('H:i');
        return $date->translatedFormat('j M Y · H:i');
    }
}