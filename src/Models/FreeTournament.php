<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\LaravelAddressable\Traits\Addressable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int|null $mode_id
 * @property int|null $dart_type_id
 * @property int|null $qualification_level_id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property int $maximum_number_of_participants
 * @property int $coin_money
 * @property string $prize_money_depending_on_placement
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class FreeTournament extends TranslateableModel implements HasMedia
{
    use Addressable;
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tournament_league_free_tournaments';

    protected $dates = [
        'started_at',
        'ended_at',
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
        'maximum_number_of_participants',
        'coin_money',
        'prize_money_depending_on_placement',
        'upload_image',
        'started_at',
        'ended_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'prize_money_depending_on_placement' => 'json',
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

    public function mode(): BelongsTo
    {
        return $this->belongsTo(Mode::class);
    }

    public function dartType(): BelongsTo
    {
        return $this->belongsTo(DartType::class);
    }

    public function qualificationLevel(): BelongsTo
    {
        return $this->belongsTo(QualificationLevel::class);
    }
}
