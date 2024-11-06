<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Foerderschwerpunkt` class represents a Laravel model for managing remarks associated with Foerderschwerpunkte.
 *
 * @package App\Models
 * @property int $id
 * @property string $kuerzel
 * @property string $beschreibung
 * @method static \Database\Factories\FoerderschwerpunktFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereBeschreibung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereKuerzel($value)
 * @mixin \Eloquent
 */
class Foerderschwerpunkt extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'foerderschwerpunkte';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'beschreibung', 'sortierung',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;
}
