<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $game_id
 * @property int|null $player_encounter_type_id
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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function homePlayers(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_home_players');
    }

    public function guestPlayers(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'game_guest_players');
    }
}
