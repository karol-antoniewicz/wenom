<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

/**
 * The `Klasse` class represents a Laravel model for managing remarks associated with Klassen.
 *
 * @package App\Models
 * @property int $id
 * @property int|null $idJahrgang
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property int $sortierung
 * @property bool $editable_teilnoten
 * @property bool $editable_noten
 * @property bool $editable_mahnungen
 * @property bool $editable_fehlstunden
 * @property bool $toggleable_fehlstunden
 * @property bool $editable_fb
 * @property bool $editable_asv
 * @property bool $editable_aue
 * @property bool $editable_zb
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @property-read Collection<int, \App\Models\User> $klassenlehrer
 * @property-read int|null $klassenlehrer_count
 * @property-read Collection<int, \App\Models\Lerngruppe> $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @method static \Database\Factories\KlasseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableAsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableAue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableFb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableFehlstunden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableMahnungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableNoten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableTeilnoten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableZb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereIdJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereToggleableFehlstunden($value)
 * @property-read Collection<int, \App\Models\User> $klassenlehrer
 * @property-read Collection<int, \App\Models\Lerngruppe> $lerngruppen
 * @mixin \Eloquent
 */
class Klasse extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'klassen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'idJahrgang', 'kuerzel', 'kuerzelAnzeige', 'sortierung', 'editable_teilnoten', 'editable_noten',
		'editable_mahnungen', 'editable_fehlstunden', 'toggleable_fehlstunden', 'editable_fb', 'editable_asv',
		'editable_aue', 'editable_zb', 'edit_overrideable',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
		'edit_overrideable' => 'boolean',
		'editable_teilnoten' => 'boolean',
		'editable_noten' => 'boolean',
		'editable_mahnungen' => 'boolean',
		'editable_fehlstunden' => 'boolean',
		'toggleable_fehlstunden' => 'boolean',
		'editable_fb' => 'boolean',
		'editable_asv' => 'boolean',
		'editable_aue' => 'boolean',
		'editable_zb' => 'boolean',
	];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
	public function jahrgang(): BelongsTo
	{
		return $this->belongsTo(Jahrgang::class, 'idJahrgang');
	}

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
	public function lerngruppen(): HasMany
	{
		return $this->hasMany(Lerngruppe::class);
	}

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
    public function klassenlehrer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'klasse_user');
    }

    /**
     * Retrieve a collection of items that do not belong to a specific 'jahrgang' (year) and order them by 'sortierung'.
     *
     * @param string $direction
     * @return Collection
     */
    public static function notBelongingToJahrgangOrdered(string $direction = 'asc'): Collection
	{
		return self::query()
			->whereNull('idJahrgang')
			->orderBy('sortierung', $direction)
			->get();
	}
}
