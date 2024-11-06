<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `BKAbschluss` class represents a Laravel model for managing remarks associated with BKAbschlüsse.
 *
 * @package App\Models
 * @property int $id
 * @property int $schueler_id
 * @property int $hatZulassung
 * @property int $hatBestanden
 * @property int $hatZulassungErweiterteBeruflicheKenntnisse
 * @property int $hatErworbenErweiterteBeruflicheKenntnisse
 * @property \App\Models\Note|null $notePraktischePruefung
 * @property \App\Models\Note|null $noteKolloqium
 * @property int $hatZulassungBerufsabschlusspruefung
 * @property int $hatBestandenBerufsabschlusspruefung
 * @property string $themaAbschlussarbeit
 * @property int $istVorhandenBerufsabschlusspruefung
 * @property \App\Models\Note|null $noteFachpraxis
 * @property int $istFachpraktischerTeilAusreichend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BKFach> $bkFaecher
 * @property-read int|null $bk_faecher_count
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\BKAbschlussFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss query()
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatBestanden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatBestandenBerufsabschlusspruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatErworbenErweiterteBeruflicheKenntnisse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatZulassung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatZulassungBerufsabschlusspruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatZulassungErweiterteBeruflicheKenntnisse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereIstFachpraktischerTeilAusreichend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereIstVorhandenBerufsabschlusspruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereNoteFachpraxis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereNoteKolloqium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereNotePraktischePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereThemaAbschlussarbeit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BKFach> $bkFaecher
 * @mixin \Eloquent
 */
class BKAbschluss extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'bkabschluesse';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'schueler_id', 'hatZulassung', 'hatBestanden', 'hatZulassungErweiterteBeruflicheKenntnisse',
        'hatErworbenErweiterteBeruflicheKenntnisse', 'notePraktischePruefung', 'noteKolloqium',
        'hatZulassungBerufsabschlusspruefung', 'hatBestandenBerufsabschlusspruefung', 'themaAbschlussarbeit',
        'istVorhandenBerufsabschlusspruefung', 'noteFachpraxis', 'istFachpraktischerTeilAusreichend',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteFachpraxis(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteKolloqium(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function notePraktischePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
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
    public function bkFaecher(): HasMany
    {
        return $this->hasMany(BKFach::class);
    }
}
