<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `BKFach` class represents a Laravel model for managing remarks associated with BKFacher.
 *
 * @package App\Models
 * @property int $id
 * @property int $b_k_abschluss_id
 * @property int $fach_id
 * @property int $user_id
 * @property int $istSchriftlich
 * @property \App\Models\Note|null $vornote
 * @property \App\Models\Note|null $noteSchriftlichePruefung
 * @property int $muendlichePruefung
 * @property int $muendlichePruefungFreiwillig
 * @property \App\Models\Note|null $noteMuendlichePruefung
 * @property int $istSchriftlichBerufsabschluss
 * @property \App\Models\Note|null $noteBerufsabschluss
 * @property \App\Models\Note|null $abschlussnote
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BKAbschluss|null $bkabschluss
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\User|null $lehrer
 * @method static \Database\Factories\BKFachFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach query()
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereAbschlussnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereBKAbschlussId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereIstSchriftlich($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereIstSchriftlichBerufsabschluss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereMuendlichePruefungFreiwillig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereNoteBerufsabschluss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereNoteMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereNoteSchriftlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereVornote($value)
 * @mixin \Eloquent
 */
class BKFach extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'bkfaecher';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'bkabschluss_id', 'fach_id', 'lehrer_id', 'istSchriftlich', 'vornote', 'noteSchriftlichePruefung', 
        'muendlichePruefung', 'muendlichePruefungFreiwillig', 'noteMuendlichePruefung', 'istSchriftlichBerufsabschluss',
        'noteBerufsabschluss', 'abschlussnote',  
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function abschlussnote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function bkabschluss(): BelongsTo
    {
        return $this->belongsTo(BKAbschluss::class);
    }

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
    public function lehrer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteBerufsabschluss(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteMuendlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteSchriftlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function vornote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
