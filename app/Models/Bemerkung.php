<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Bemerkung` class represents a Laravel model for managing remarks associated with students.
 *
 * @package App\Models
 * @property int $id
 * @property int $schueler_id
 * @property string|null $ASV
 * @property string $tsASV Timestamp
 * @property string|null $AUE
 * @property string $tsAUE Timestamp
 * @property string|null $ZB
 * @property string $tsZB Timestamp
 * @property string|null $LELS
 * @property string|null $schulformEmpf
 * @property string|null $individuelleVersetzungsbemerkungen
 * @property string $tsIndividuelleVersetzungsbemerkungen Timestamp
 * @property string|null $foerderbemerkungen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\BemerkungFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereASV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereAUE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereFoerderbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereIndividuelleVersetzungsbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereLELS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereSchulformEmpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsASV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsAUE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsIndividuelleVersetzungsbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsZB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereZB($value)
 * @mixin \Eloquent
 */
class Bemerkung extends Model
{
    use HasFactory;

    /*
     * Define a list of allowed bemerkungen
     *
     * @return array<int, string> ALLOWED_BEMERKUNGEN
     */
    public const ALLOWED_BEMERKUNGEN = [
        'ASV', 'AUE', 'ZB',
    ];

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'bemerkungen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var array<int, string> $fillable
     */
    protected $fillable = [
        'schueler_id', 'ASV', 'tsASV', 'AUE', 'tsAUE', 'ZB', 'tsZB', 'LELS', 'schulformEmpf',
        'individuelleVersetzungsbemerkungen', 'tsIndividuelleVersetzungsbemerkungen', 'foerderbemerkungen',
    ];

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
