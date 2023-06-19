<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\Addressable\Traits\Addressable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int|null $team_id
 * @property int|null $player_role_id
 * @property string $name
 * @property string $email
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Player extends TranslateableModel implements HasMedia
{
    use Addressable;
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_players';

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
        'email',
        'nickname',
        'id_number',
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

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function league(): HasOneThrough
    {
        return $this->hasOneThrough(
            League::class,
            Team::class,
            'tournament_league_teams.id',
            'tournament_league_leagues.id',
            'team_id',
            'league_id'
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(PlayerRole::class, 'player_role_id', 'id');
    }

    public function gameSchedules(): BelongsToMany
    {
        return $this->belongsToMany(GameSchedule::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_player')
            ->withPivot('is_home', 'is_guest');
    }

    public function homeGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_player')
            ->wherePivot('is_home', true)
            ->withPivot('is_home');
    }

    public function guestGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_player')
            ->wherePivot('is_guest', true)
            ->withPivot('is_guest');
    }
}
