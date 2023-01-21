<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\LaravelAddressable\Traits\Addressable;

/**
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class EventLocation extends TranslateableModel
{
    use Addressable;
    use HasFactory;
    use SoftDeletes;

    public const AS_DEFAULT_NAME = 'EventLocation';

    /**
     * @var string
     */
    protected $table = 'tournament_league_event_locations';

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
}
