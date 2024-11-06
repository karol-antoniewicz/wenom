<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * The `Schueler` class represents a Laravel model for managing remarks associated with Schueler.
 *
 * @package App\Models
 * @property int $id
 * @property int $jahrgang_id
 * @property int $klasse_id
 * @property string $nachname
 * @property string $vorname
 * @property string $geschlecht
 * @property \App\Models\Fach|null $bilingualeSprache
 * @property int $istZieldifferent
 * @property int $istDaZFoerderung
 * @property-read \App\Models\Bemerkung|null $bemerkung
 * @property-read \App\Models\BKAbschluss|null $bkabschluss
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @property-read \App\Models\Klasse|null $klasse
 * @property-read Collection<int, \App\Models\Leistung> $leistungen
 * @property-read int|null $leistungen_count
 * @property-read \App\Models\Lernabschnitt|null $lernabschnitt
 * @property-read Collection<int, \App\Models\Sprachenfolge> $sprachenfolgen
 * @property-read int|null $sprachenfolgen_count
 * @property-read \App\Models\Zp10|null $zp10
 * @method static \Database\Factories\SchuelerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereBilingualeSprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIstDaZFoerderung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIstZieldifferent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereJahrgangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereKlasseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereVorname($value)
 * @property-read Collection<int, \App\Models\Leistung> $leistungen
 * @property-read Collection<int, \App\Models\Sprachenfolge> $sprachenfolgen
 * @mixin \Eloquent
 */
class Schueler extends Model
{
    use HasFactory;

    /*
     * Define a list of allowed genders
     *
     * @return string[]
     */
	const GENDERS = ['m', 'w', 'd', 'x'];

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'schueler';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'jahrgang_id', 'klasse_id', 'nachname', 'vorname', 'geschlecht', 'bilingualeSprache', 'istZieldifferent',
        'istDaZFoerderung',
    ];

    protected $casts = [
        'bilingualeSprache' => 'string',
        'istZieldifferent' => 'bool',
        'istDaZFoerderung' => 'bool',
    ];


	public $timestamps = false;

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function bilingualeSprache(): BelongsTo // TODO: not in json
    {
        return $this->belongsTo(Fach::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function bemerkung(): HasOne // TODO: not in json
    {
        return $this->hasOne(Bemerkung::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function bkabschluss(): HasOne // TODO: redo import from hasmany to has one, // TODO: not in json
    {
        return $this->hasOne(BKAbschluss::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(Jahrgang::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function klasse(): BelongsTo
    {
        return $this->belongsTo(Klasse::class);
    }

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function leistungen(): HasMany
    {
        return $this->hasMany(Leistung::class);
    }

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function sprachenfolge(): HasMany
    {
        return $this->hasMany(Sprachenfolge::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function lernabschnitt(): HasOne
    {
        return $this->hasOne(Lernabschnitt::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function zp10(): HasOne // TODO: check imnport after changing hasmany to hasone // TODO: not in json
    {
        return $this->hasOne(Zp10::class);
    }

     /**
      * Determin whether the authenticated users shares "Klasse" with current "Schueler"
      *
      * @return bool
      */
	public function sharesKlasseWithCurrentUser(): bool
	{
        return in_array(
            $this->klasse_id,
            Auth::user()->klassen->pluck('id')->toArray(),
        );
	}
}
