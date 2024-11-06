<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Sprachenfolge` class represents a Laravel model for managing remarks associated with Sprachenfolgen.
 *
 * @package App\Models
 * @property int $id
 * @property int $schueler_id
 * @property int $fach_id
 * @property int $reihenfolge
 * @property int|null $belegungVonJahrgang
 * @property int|null $belegungVonAbschnitt
 * @property int|null $belegungBisJahrgang
 * @property int|null $belegungBisAbschnitt
 * @property string|null $referenzniveau
 * @property int|null $belegungSekI
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\SprachenfolgeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungBisAbschnitt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungBisJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungSekI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungVonAbschnitt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungVonJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereReferenzniveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereReihenfolge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sprachenfolge extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'sprachenfolge';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [     // TODO: Check spelling of schueler
        'schuler_id', 'sprache', 'fach_id', 'reihenfolge', 'belegungVonJahrgang', 'belegungVonAbschnitt',
        'belegungBisJahrgang', 'belegungBisAbschnitt', 'referenzniveau', 'belegungSekI',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }
}
