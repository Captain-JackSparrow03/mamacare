<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    protected $fillable = ['user_id', 'title', 'date', 'type', 'is_done'];
    protected $casts = [
        'date'    => 'datetime',
        'is_done' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Label couleur selon type */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'rdv'        => 'rose',
            'medicament' => 'amber',
            'vaccin'     => 'emerald',
            default      => 'stone',
        };
    }

    /** Label lisible */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'rdv'        => 'Rendez-vous',
            'medicament' => 'Médicament',
            'vaccin'     => 'Vaccin',
            default      => $this->type,
        };
    }

    /** Icône */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'rdv'        => '🏥',
            'medicament' => '💊',
            'vaccin'     => '💉',
            default      => '📌',
        };
    }

    /** Est-ce aujourd'hui ? */
    public function getIsTodayAttribute(): bool
    {
        return Carbon::parse($this->date)->isToday();
    }

    /** Est-ce en retard ? */
    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_done && Carbon::parse($this->date)->isPast();
    }
}