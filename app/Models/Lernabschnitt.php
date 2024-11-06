<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Lernabschnitt` class represents a Laravel model for managing remarks associated with Lernabschnitte.
 *
 * @package App\Models
 * @property int $id
 * @property int $schueler_id
 * @property int|null $fehlstundenGesamt
 * @property string $tsFehlstundenGesamt
 * @property int|null $fehlstundenGesamtUnentschuldigt
 * @property string $tsFehlstundenGesamtUnentschuldigt
 * @property string $pruefungsordnung
 * @property int|null $lernbereich1note
 * @property int|null $lernbereich2note
 * @property int|null $foerderschwerpunkt1
 * @property int|null $foerderschwerpunkt2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt1Relation
 * @property-read \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt2Relation
 * @property-read \App\Models\Note|null $lernbereich1Note
 * @property-read \App\Models\Note|null $lernbereich2Note
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\LernabschnittFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFehlstundenGesamt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFehlstundenGesamtUnentschuldigt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFoerderschwerpunkt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFoerderschwerpunkt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereLernbereich1note($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereLernbereich2note($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt wherePruefungsordnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereTsFehlstundenGesamt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereTsFehlstundenGesamtUnentschuldigt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Lernabschnitt extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'lernabschnitte';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'schueler_id', 'fehlstundenGesamt', 'tsFehlstundenGesamt', 'fehlstundenGesamtUnentschuldigt',
        'tsFehlstundenGesamtUnentschuldigt', 'pruefungsordnung', 'lernbereich1note', 'lernbereich2note',
        'foerderschwerpunkt1', 'foerderschwerpunkt2',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function foerderschwerpunkt1Relation(): BelongsTo
    {
        return $this->belongsTo(Foerderschwerpunkt::class, 'foerderschwerpunkt1', 'id');
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function foerderschwerpunkt2Relation(): BelongsTo
    {
        return $this->belongsTo(Foerderschwerpunkt::class, 'foerderschwerpunkt2', 'id');
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lernbereich1Note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'lernbereich1note', 'id');
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lernbereich2Note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'lernbereich2note', 'id');
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
