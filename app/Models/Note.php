<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The `Note` class represents a Laravel model for managing remarks associated with Noten.
 *
 * @package App\Models
 * @property int $id
 * @property string $kuerzel
 * @property int|null $notenpunkte
 * @property string|null $text
 * @method static \Database\Factories\NoteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereNotenpunkte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereText($value)
 * @property string $sortierung
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereSortierung($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'noten';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'notenpunkte', 'text', 'sortierung',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;
}
