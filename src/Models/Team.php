<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Korridor\LaravelHasManyMerged\HasManyMerged;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $league_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Team extends TranslateableModel
{
    use HasFactory;
    use HasManyMergedRelation;
    use HasSlug;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_teams';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class)->withTrashed();
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class)->withTrashed();
    }

    public function gameSchedules(): BelongsToMany
    {
        return $this->belongsToMany(GameSchedule::class)->withTrashed();
    }

    public function games(): HasManyMerged
    {
        return $this->hasManyMerged(Game::class, ['home_team_id', 'guest_team_id'])->withTrashed();
    }

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id', 'id')->withTrashed();
    }

    public function guestGames(): HasMany
    {
        return $this->hasMany(Game::class, 'guest_team_id', 'id')->withTrashed();
    }

    public function opponentHomeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'guest_team_id', 'id')->withTrashed();
    }

    public function opponentGuestGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id', 'id')->withTrashed();
    }

    public function scopeRecalculatePoints(Builder $query, GameSchedule $gameSchedule): Builder
    {
        return $query
            ->where('id', $this->id)
            ->withSum([
                'homeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'home_points_legs')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'guest_points_legs')
            ->withSum([
                'opponentHomeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'home_points_legs')
            ->withSum([
                'opponentGuestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'guest_points_legs')
            ->withSum([
                'homeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'home_points_games')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'guest_points_games')
            ->withSum([
                'opponentGuestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'guest_points_games')
            ->withSum([
                'opponentHomeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id),
            ], 'home_points_games')
            ->withCount([
                'games as total_number_of_encounters' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $gameSchedule->id),

                'games as total_wins' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $this->id)->whereRaw('home_points_games > guest_points_games')
                        )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $this->id)->whereRaw('home_points_games < guest_points_games')
                        )
                        ),
                'games as total_defeats' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $this->id)->whereRaw('home_points_games < guest_points_games')
                        )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $this->id)->whereRaw('home_points_games > guest_points_games')
                        )
                        ),
                'games as total_draws' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $gameSchedule->id)
                        ->whereRaw('home_points_games = guest_points_games'),

                'games as total_victory_after_defeats' => fn (Builder $query) => $query
                        ->where('game_schedule_id', $gameSchedule->id)
                        ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $this->id)->whereRaw('home_points_after_draw > guest_points_after_draw')
                        )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $this->id)->whereRaw('home_points_after_draw < guest_points_after_draw')
                        )
                        ),
            ]);
    }
}
