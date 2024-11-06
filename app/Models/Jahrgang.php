<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Jahrgang` class represents a Laravel model for managing remarks associated with Jahrgange.
 *
 * @package App\Models
 * @property int $id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property string $beschreibung
 * @property string $stufe
 * @property int $sortierung
 * @property-read Collection<int, \App\Models\Klasse> $klassen
 * @property-read int|null $klassen_count
 * @method static \Database\Factories\JahrgangFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereBeschreibung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereStufe($value)
 * @property-read Collection<int, \App\Models\Klasse> $klassen
 * @mixin \Eloquent
 */
class Jahrgang extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'jahrgaenge';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'kuerzelAnzeige', 'beschreibung', 'stufe', 'sortierung',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
	public function klassen(): HasMany
	{
		return $this->hasMany(Klasse::class, 'idJahrgang');
	}

    /**
     * Retrieve a collection of items ordered by 'sortierung' field and eager load related 'klassen' with sorting.
     *
     * @param string $direction
     * @return Collection
     */
    public static function orderedWithKlassenOrdered(string $direction = 'asc'): Collection
	{
		return self::query()
			->whereHas('klassen')
			->with('klassen', fn (HasMany $klassen): HasMany =>
				$klassen->orderBy('sortierung', $direction)
			)
			->orderBy('sortierung', $direction)
			->get();
	}

}
