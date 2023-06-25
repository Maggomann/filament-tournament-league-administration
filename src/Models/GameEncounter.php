<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\Maggomann\FilamentTournamentLeagueAdministration\Models\Player|null firstHomePlayer()
 * @method static \Illuminate\Database\Eloquent\Builder|\Maggomann\FilamentTournamentLeagueAdministration\Models\Player|null secondHomePlayer()
 * @method static \Illuminate\Database\Eloquent\Builder|\Maggomann\FilamentTournamentLeagueAdministration\Models\Player|null firstGuestPlayer()
 * @method static \Illuminate\Database\Eloquent\Builder|\Maggomann\FilamentTournamentLeagueAdministration\Models\Player|null secondGuestPlayer()
 *
 * @property int $id
 * @property int|null $game_id
 * @property int|null $game_encounter_type_id.
 * @property int $order
 * @property int $home_team_win
 * @property int $home_team_defeat
 * @property int $guest_team_win
 * @property int $guest_team_defeat
 * @property int $home_team_points_leg
 * @property int $guest_team_points_leg
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Maggomann\FilamentTournamentLeagueAdministration\Models\Player[] $guestPlayers
 * @property-read \Illuminate\Database\Eloquent\Collection|\Maggomann\FilamentTournamentLeagueAdministration\Models\Player[] $homePlayers
 */
class GameEncounter extends TranslateableModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_game_encounters';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'order',
        'home_team_win',
        'home_team_defeat',
        'guest_team_win',
        'guest_team_defeat',
        'home_team_points_leg',
        'guest_team_points_leg',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function homePlayers(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_encounter_player')
            ->wherePivot('is_home', true)
            ->withPivot('is_home');
    }

    public function guestPlayers(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_encounter_player')
            ->wherePivot('is_guest', true)
            ->withPivot('is_guest');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function scopeFirstHomePlayer(): ?Player
    {
        return $this->homePlayers()->first();
    }

    public function scopeSecondHomePlayer(): ?Player
    {
        return $this->homePlayers()->skip(1)->first();
    }

    public function scopeFirstGuestPlayer(): ?Player
    {
        return $this->guestPlayers()->first();
    }

    public function scopeSecondGuestPlayer(): ?Player
    {
        return $this->guestPlayers()->skip(1)->first();
    }
}
