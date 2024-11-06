<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `UserSetting` class represents a Laravel model for managing remarks associated with UserSettings.
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property object|null $filters_leistungsdatenuebersicht
 * @property object|null $filters_meinunterricht
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereFiltersLeistungsdatenuebersicht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereFiltersMeinunterricht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUserId($value)
 * @mixin \Eloquent
 */
class UserSetting extends Model
{
    use HasFactory;

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'filters_leistungsdatenuebersicht', 'filters_meinunterricht', 'twofactor_otp',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
        'filters_leistungsdatenuebersicht' => 'object',
        'filters_meinunterricht' => 'object',
        'twofactor_otp' => 'bool',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
