<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameSchedule extends TranslateableModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_game_schedules';

    protected $dates = [
        'period_start',
        'period_end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'period_start',
        'period_end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function federation(): BelongsTo
    {
        return $this->belongsTo(Federation::class);
    }

    public function league(): MorphOne
    {
        return $this->morphOne(League::class, 'gameschedulable', null, null, 'gameschedulable_id');
    }

    public function leagueBT(): BelongsTo
    {
        return $this->belongsTo(League::class, 'gameschedulable_id', 'id', 'league');
    }

    public function days(): HasMany
    {
        return $this->hasMany(GameDay::class);
    }
}
