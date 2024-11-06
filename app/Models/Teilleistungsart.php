<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Teilleistungsart` class represents a Laravel model for managing remarks associated with Teilleistungsarten.
 *
 * @package App\Models
 * @property int $id
 * @property string $bezeichnung
 * @property int|null $sortierung
 * @property float|null $gewichtung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teilleistung> $teilleistungen
 * @property-read int|null $teilleistungen_count
 * @method static \Database\Factories\TeilleistungsartFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereGewichtung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teilleistung> $teilleistungen
 * @mixin \Eloquent
 */
class Teilleistungsart extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'teilleistungsarten';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'bezeichnung', 'sortierung', 'gewichtung',
    ];

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }
}
