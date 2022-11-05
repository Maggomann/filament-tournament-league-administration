<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $day
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Game extends TranslateableModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_games';

    protected $dates = [
        'started_at',
        'ended_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'home_points_legs',
        'guest_points_legs',
        'home_points_games',
        'guest_points_games',
        'has_an_overtime',
        'home_points_after_draw',
        'guest_points_after_draw',
        'started_at',
        'ended_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'has_an_overtime' => 'boolean',
    ];

    public function gameSchedule(): BelongsTo
    {
        return $this->belongsTo(GameSchedule::class)->withTrashed();
    }

    public function gameDay(): BelongsTo
    {
        return $this->belongsTo(GameDay::class)->withTrashed();
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id')->withTrashed();
    }

    public function guestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'guest_team_id')->withTrashed();
    }

    public function scopeCollection(Builder $query, string $collectionName): Builder
    {
        return $query->where('collection_name', $collectionName);
    }
}
