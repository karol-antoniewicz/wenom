<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Fach` class represents a Laravel model for managing remarks associated with Faecher.
 *
 * @package App\Models
 * @property int $id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property int $sortierung
 * @property int $istFremdsprache
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Floskel> $floskeln
 * @property-read int|null $floskeln_count
 * @method static \Database\Factories\FachFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Fach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereIstFremdsprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereSortierung($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Floskel> $floskeln
 * @mixin \Eloquent
 */
class Fach extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'faecher';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'kuerzelAnzeige', 'sortierung', 'istFremdsprache',
    ];

	public $timestamps = false;

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
	public function floskeln(): HasMany
	{
		return $this->hasMany(Floskel::class);
	}
}
