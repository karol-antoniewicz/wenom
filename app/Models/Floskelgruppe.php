<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Floskelgruppe` class represents a Laravel model for managing remarks associated with Floskelgruppen.
 *
 * @package App\Models
 * @property int $id
 * @property string $kuerzel
 * @property string $bezeichnung
 * @property string $hauptgruppe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Floskel> $floskeln
 * @property-read int|null $floskeln_count
 * @method static \Database\Factories\FloskelgruppeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereHauptgruppe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereKuerzel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Floskel> $floskeln
 * @mixin \Eloquent
 */
class Floskelgruppe extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'floskelgruppen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'bezeichnung', 'hauptgruppe',
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
    public function floskeln(): HasMany
    {
        return $this->hasMany( Floskel::class);
    }
}
