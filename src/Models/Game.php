<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $guest_team_id
 * @property int|null $home_team_id
 * @property int|null $game_schedule_id.
 * @property int|null $game_day_id.
 * @property int $day
 * @property int $home_points_legs
 * @property int $guest_points_legs
 * @property int $home_points_games
 * @property int $guest_points_games
 * @property bool $has_an_overtime
 * @property int $home_points_after_draw
 * @property int $guest_points_after_draw
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Game activeMatches(GameSchedule $gameSchedule, Carbon $now)
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
        return $this->belongsTo(GameSchedule::class);
    }

    public function gameDay(): BelongsTo
    {
        return $this->belongsTo(GameDay::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function guestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'guest_team_id');
    }

    public function homePlayers(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_home_players');
    }

    public function guestPlayers(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_guest_players');
    }

    public function scopeCollection(Builder $query, string $collectionName): Builder
    {
        return $query->where('collection_name', $collectionName);
    }

    public function scopeActiveMatches(Builder $query, GameSchedule $gameSchedule, ?Carbon $now = null): Builder
    {
        if (blank($now)) {
            $now = now();
        }

        return $query
            ->whereBelongsTo($gameSchedule)
            ->where('ended_at', '<=', $now)
            ->where(fn ($subQuery) => $subQuery->where('home_points_games', '>', 0)->orWhere('guest_points_games', '>', 0));
    }
}
