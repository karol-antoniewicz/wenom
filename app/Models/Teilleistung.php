<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Teilleistung` class represents a Laravel model for managing remarks associated with Teilleistungn.
 *
 * @package App\Models
 * @property int $id
 * @property int $leistung_id
 * @property int $teilleistungsart_id
 * @property string|null $datum
 * @property string|null $bemerkung
 * @property int|null $note_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Leistung|null $leistung
 * @property-read \App\Models\Teilleistungsart|null $teilleistungsart
 * @method static \Database\Factories\TeilleistungFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereBemerkung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereDatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereLeistungId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTeilleistungsartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereUpdatedAt($value)
 * @property string $tsArtID
 * @property string $tsDatum
 * @property string $tsBemerkung
 * @property string $tsNote
 * @property-read \App\Models\Note|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTsArtID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTsBemerkung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTsDatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTsNote($value)
 * @mixin \Eloquent
 */
class Teilleistung extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'teilleistungen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'leistung_id', 'teilleistungsart_id', 'tsArtID', 'datum', 'tsDatum', 'bemerkung', 'tsBemerkung',
        'note_id', 'tsNote',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function leistung(): BelongsTo
    {
        return $this->belongsTo(Leistung::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function teilleistungsart(): BelongsTo
    {
        return $this->belongsTo(Teilleistungsart::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
