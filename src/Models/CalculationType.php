<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class CalculationType extends TranslateableModel
{
    use HasFactory;
    use SoftDeletes;

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
