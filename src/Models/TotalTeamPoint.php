<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $game_schedule_id
 * @property int|null $team_id
 * @property int $day
 * @property int $total_number_of_encounters
 * @property int $total_wins
 * @property int $total_defeats
 * @property int $total_draws
 * @property int $total_victory_after_defeats
 * @property int $total_home_points_legs
 * @property int $total_guest_points_legs
 * @property int $total_home_points_games
 * @property int $total_guest_points_games
 * @property int $total_home_points_after_draw
 * @property int $total_guest_points_after_draw
 * @property int $total_points
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class TotalTeamPoint extends TranslateableModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_total_team_points';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'palcement',
        'total_number_of_encounters',
        'total_wins',
        'total_defeats',
        'total_draws',
        'total_victory_after_defeats',
        'total_home_points_legs',
        'total_guest_points_legs',
        'total_home_points_games',
        'total_guest_points_games',
        'total_home_points_after_draw',
        'total_guest_points_after_draw',
        'total_points',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function gameSchedule(): BelongsTo
    {
        return $this->belongsTo(GameSchedule::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
