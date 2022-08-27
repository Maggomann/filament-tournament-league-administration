<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationType extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tournament_league_calculation_types';

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
        'description',
        'calculator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
