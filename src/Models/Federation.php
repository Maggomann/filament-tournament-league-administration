<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Federation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tournament_league_federations';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];
}
