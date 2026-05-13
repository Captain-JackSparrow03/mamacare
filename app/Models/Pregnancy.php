<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pregnancy extends Model
{
    protected $fillable = ['user_id', 'start_date'];
    protected $casts = ['start_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Semaine courante (1–40) */
    public function getCurrentWeekAttribute(): int
    {
        $days = Carbon::parse($this->start_date)->diffInDays(now());
        return min(40, max(1, intdiv($days, 7) + 1));
    }

    /** Progression en % (0–100) */
    public function getProgressPercentAttribute(): float
    {
        return round(($this->current_week / 40) * 100, 1);
    }

    /** Date terme estimée (start_date + 280 jours) */
    public function getDueDateAttribute(): string
    {
        return Carbon::parse($this->start_date)
            ->addDays(280)
            ->translatedFormat('j M Y');
    }

    /** Trimestre */
    public function getTrimesterAttribute(): string
    {
        $w = $this->current_week;
        if ($w <= 13) return '1er trimestre';
        if ($w <= 26) return '2ème trimestre';
        return '3ème trimestre';
    }

    /** Semaines restantes */
    public function getWeeksRemainingAttribute(): int
    {
        return max(0, 40 - $this->current_week);
    }
}