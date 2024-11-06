<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Floskel` class represents a Laravel model for managing remarks associated with Floskeln.
 *
 * @package App\Models
 * @property int $id
 * @property int $floskelgruppe_id
 * @property string $kuerzel
 * @property string $text
 * @property int|null $fach_id
 * @property int|null $jahrgang_id
 * @property int|null $niveau
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Floskelgruppe|null $floskelgruppe
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @method static \Database\Factories\FloskelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereFloskelgruppeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereJahrgangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereText($value)
 * @mixin \Eloquent
 */
class Floskel extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'floskeln';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'floskelgruppe_id', 'kuerzel', 'text', 'fach_id', 'jahrgang_id', 'niveau',
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
    public function floskelgruppe(): BelongsTo
    {
        return $this->belongsTo(Floskelgruppe::class);
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
    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }
}
