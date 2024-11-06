<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * The `Leistung` class represents a Laravel model for managing remarks associated with Leistungen.
 *
 * @package App\Models
 * @property int $id
 * @property int $schueler_id
 * @property int $lerngruppe_id
 * @property int|null $note_id
 * @property string $tsNote
 * @property int $istSchriftlich
 * @property string|null $abiturfach
 * @property int|null $fehlstundenFach
 * @property string $tsFehlstundenFach
 * @property int|null $fehlstundenUnentschuldigtFach
 * @property string $tsFehlstundenUnentschuldigtFach
 * @property string|null $fachbezogeneBemerkungen
 * @property string $tsFachbezogeneBemerkungen
 * @property string|null $neueZuweisungKursart
 * @property bool $istGemahnt
 * @property string $tsIstGemahnt
 * @property \Illuminate\Support\Carbon|null $mahndatum
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lerngruppe|null $lerngruppe
 * @property-read \App\Models\Note|null $note
 * @property-read \App\Models\Schueler|null $schueler
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teilleistung> $teilleistungen
 * @property-read int|null $teilleistungen_count
 * @method static \Database\Factories\LeistungFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereAbiturfach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFachbezogeneBemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFehlstundenFach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFehlstundenUnentschuldigtFach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereIstGemahnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereIstSchriftlich($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereLerngruppeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereMahndatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereNeueZuweisungKursart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereTsFachbezogeneBemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereTsFehlstundenFach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereTsFehlstundenUnentschuldigtFach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereTsIstGemahnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereTsNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teilleistung> $teilleistungen
 * @mixin \Eloquent
 */
class Leistung extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'leistungen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'schueler_id', 'lerngruppe_id', 'note_id', 'tsNote', 'note_quartal_id', 'tsNoteQuartal', 'istSchriftlich',
        'abiturfach', 'fehlstundenFach', 'tsFehlstundenFach', 'fehlstundenUnentschuldigtFach',
        'tsFehlstundenUnentschuldigtFach', 'fachbezogeneBemerkungen', 'tsFachbezogeneBemerkungen',
        'neueZuweisungKursart', 'istGemahnt', 'tsIstGemahnt', 'mahndatum',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
	protected $casts = [
		'mahndatum' => 'datetime',
        'istGemahnt' => 'boolean',
        'istSchriftlich' => 'boolean',
	];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lerngruppe(): BelongsTo
    {
        return $this->belongsTo(Lerngruppe::class);
    }

    /**
     * The "Note" that the model belongs to
     *
     * @return BelongsTo
     */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The "NoteQuartal" that the model belongs to
     *
     * @return BelongsTo
     */
    public function quartalnote(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_quartal_id', 'id');
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

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }

    /**
     * Check if the current user shares the same 'klasse' (class) with this model instance.
     *
     * @return bool
     */
    public function sharesKlasseWithCurrentUser(): bool
	{
		return in_array($this->schueler->klasse_id, Auth::user()->klassen->pluck('id')->toArray());
	}

    /**
     * Check if the current user shares the same 'lerngruppe' (study group) with this model instance.
     *
     * @return bool
     */
    public function sharesLerngruppeWithCurrentUser(): bool
	{
		return in_array($this->lerngruppe_id, Auth::user()->lerngruppen->pluck('id')->toArray());
	}
}
