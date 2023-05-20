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
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $league_id
 * @property string $name
 * @property int $total_number_of_encounters
 * @property int $total_wins
 * @property int $total_defeats
 * @property int $total_draws
 * @property int $total_victory_after_defeats
 * @property int $guest_games_sum_guest_points_legs
 * @property int $home_games_sum_home_points_legs
 * @property int $opponent_guest_games_sum_guest_points_legs
 * @property int $opponent_home_games_sum_home_points_legs
 * @property int $guest_games_sum_guest_points_games
 * @property int $home_games_sum_home_points_games
 * @property int $opponent_guest_games_sum_guest_points_games
 * @property int $opponent_home_games_sum_home_points_games
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Team extends TranslateableModel implements HasMedia
{
    use HasFactory;
    use HasManyMergedRelation;
    use HasSlug;
    use InteractsWithMedia;
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
        'upload_image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('upload_image')->singleFile();
    }

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
        return $this->belongsTo(League::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function gameSchedules(): BelongsToMany
    {
        return $this->belongsToMany(GameSchedule::class);
    }

    public function games(): HasManyMerged
    {
        return $this->hasManyMerged(Game::class, ['home_team_id', 'guest_team_id']);
    }

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id', 'id');
    }

    public function guestGames(): HasMany
    {
        return $this->hasMany(Game::class, 'guest_team_id', 'id');
    }

    public function opponentHomeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'guest_team_id', 'id');
    }

    public function opponentGuestGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id', 'id');
    }

    public function scopeRecalculatePointsOld(Builder $query, GameSchedule $gameSchedule): Builder
    {
        $now = now();

        return $query
            ->where('id', $this->id)
            ->withSum([
                'homeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)
                    ->where('ended_at', '<=', $now),
            ], 'home_points_legs')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'guest_points_legs')
            ->withSum([
                'opponentHomeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'home_points_legs')
            ->withSum([
                'opponentGuestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'guest_points_legs')
            ->withSum([
                'homeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'home_points_games')
            ->withSum([
                'guestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'guest_points_games')
            ->withSum([
                'opponentGuestGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'guest_points_games')
            ->withSum([
                'opponentHomeGames' => fn (Builder $query) => $query->where('game_schedule_id', $gameSchedule->id)->where('ended_at', '<=', $now),
            ], 'home_points_games')
            ->withCount([
                'games as total_number_of_encounters' => fn (Builder $query) => $query
                    ->where('game_schedule_id', $gameSchedule->id)
                    ->where('ended_at', '<=', $now),

                'games as total_wins' => fn (Builder $query) => $query
                    ->where('game_schedule_id', $gameSchedule->id)
                    ->where('ended_at', '<=', $now)
                    ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $this->id)->whereRaw('home_points_games > guest_points_games')
                    )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $this->id)->whereRaw('home_points_games < guest_points_games')
                    )
                    ),
                'games as total_defeats' => fn (Builder $query) => $query
                    ->where('game_schedule_id', $gameSchedule->id)
                    ->where('ended_at', '<=', $now)
                    ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $this->id)->whereRaw('home_points_games < guest_points_games')
                    )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $this->id)->whereRaw('home_points_games > guest_points_games')
                    )
                    ),
                'games as total_draws' => fn (Builder $query) => $query
                    ->where('game_schedule_id', $gameSchedule->id)
                    ->where('ended_at', '<=', $now)
                    ->whereRaw('home_points_games = guest_points_games'),

                'games as total_victory_after_defeats' => fn (Builder $query) => $query
                    ->where('game_schedule_id', $gameSchedule->id)
                    ->where('ended_at', '<=', $now)
                    ->where(fn ($subQuery) => $subQuery->where(fn ($subQuery) => $subQuery->where('home_team_id', $this->id)->whereRaw('home_points_after_draw > guest_points_after_draw')
                    )->orWhere(fn ($subQuery) => $subQuery->where('guest_team_id', $this->id)->whereRaw('home_points_after_draw < guest_points_after_draw')
                    )
                    ),
            ]);
    }
}
