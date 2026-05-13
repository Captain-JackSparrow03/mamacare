<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['title', 'description', 'type', 'url', 'thumbnail', 'week'];

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'article' => '📄',
            'video'   => '🎬',
            'audio'   => '🎧',
            default   => '📌',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'article' => 'Article',
            'video'   => 'Vidéo',
            'audio'   => 'Audio',
            default   => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'article' => 'rose',
            'video'   => 'violet',
            'audio'   => 'amber',
            default   => 'stone',
        };
    }

    /** Contenu général (sem. null) ou pour une semaine précise */
    public function scopeForWeek($query, int $week)
    {
        return $query->where(function ($q) use ($week) {
            $q->where('week', $week)->orWhereNull('week');
        });
    }
}