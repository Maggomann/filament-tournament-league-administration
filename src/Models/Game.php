<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'is_admin' => 'has_an_overtime',
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

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'game_schedule_id', 'game_schedule_id');
    }

    public function guestGames(): HasMany
    {
        return $this->hasMany(Game::class, 'game_schedule_id', 'game_schedule_id');
    }

    public function scopeCollection(Builder $query, string $collectionName): Builder
    {
        return $query->where('collection_name', $collectionName);
    }

    public function scopeRecalculate(Builder $query, Team $team): Builder
    {
        return $query
            ->where('id', $this->id)
            ->withSum([
                'games' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('home_team_id', $team->id),
            ], 'home_points_legs')
            ->withSum([
                'games' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('guest_team_id', $team->id),
            ], 'guest_points_legs')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('guest_team_id', $team->id),
            ], 'home_points_legs')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('home_team_id', $team->id),
            ], 'guest_points_legs')

            ->withSum([
                'games' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('home_team_id', $team->id),
            ], 'home_points_games')
            ->withSum([
                'games' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('guest_team_id', $team->id),
            ], 'guest_points_games')

            ->withSum([
                'guestGames' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('home_team_id', $team->id),
            ], 'guest_points_games')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where('guest_team_id', $team->id),
            ], 'home_points_games')

            ->withCount([
                'games as total_number_of_encounters' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where('home_team_id', $team->id)
                                ->orWhere('guest_team_id', $team->id)
                        ),
                'games as total_wins' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $team->id)->whereRaw('home_points_games > guest_points_games')
                                )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $team->id)->whereRaw('home_points_games < guest_points_games')
                                )
                        ),
                'games as total_defeats' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $team->id)->whereRaw('home_points_games < guest_points_games')
                                )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $team->id)->whereRaw('home_points_games > guest_points_games')
                                )
                        ),
                'games as total_draws' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->whereRaw('home_points_games = guest_points_games')
                        ->where(fn ($query) => $query->where('home_team_id', $team->id)
                                    ->orWhere('guest_team_id', $team->id)
                        ),
                'games as total_victory_after_defeats' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $this->gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $team->id)->whereRaw('home_points_after_draw > guest_points_after_draw')
                                )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $team->id)->whereRaw('home_points_after_draw < guest_points_after_draw')
                                )
                        ),
            ]);
    }
}
