<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $federation_id
 * @property int|null $league_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class GameSchedule extends TranslateableModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_game_schedules';

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
        'name',
        'started_at',
        'ended_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function federation(): BelongsTo
    {
        return $this->belongsTo(Federation::class)->withTrashed();
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class)->withTrashed();
    }

    public function days(): HasMany
    {
        return $this->hasMany(GameDay::class, 'game_schedule_id', 'id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withTrashed();
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class)->withTrashed();
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class)->withTrashed();
    }

    public function totalTeamPoints(): HasMany
    {
        return $this->hasMany(TotalTeamPoint::class, 'game_schedule_id', 'id');
    }
}
