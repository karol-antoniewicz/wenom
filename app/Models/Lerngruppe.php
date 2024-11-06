<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * The `Lerngruppe` class represents a Laravel model for managing remarks associated with Lerngruppen.
 *
 * @package App\Models
 * @property int $id
 * @property int|null $klasse_id
 * @property int $fach_id
 * @property string $kID
 * @property int|null $kursartID
 * @property string $bezeichnung
 * @property string|null $kursartKuerzel
 * @property string|null $bilingualeSprache
 * @property int $wochenstunden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Klasse|null $klasse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $lehrer
 * @property-read int|null $lehrer_count
 * @method static \Database\Factories\LerngruppeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereBilingualeSprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKlasseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKursartID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKursartKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereWochenstunden($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $lehrer
 * @mixin \Eloquent
 */
class Lerngruppe extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'lerngruppen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'klasse_id', 'fach_id', 'kID', 'kursartID', 'bezeichnung', 'kursartKuerzel', 'bilingualeSprache',
        'wochenstunden',
    ];

    /**
     * Cast to native types
     *
     * @var string[]
     */
    protected $casts = [
        'kID' => 'int',
    ];

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
    public function lehrer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lerngruppe_user');
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
    public function klasse(): BelongsTo
    {
        return $this->belongsTo(Klasse::class);
    }
}
