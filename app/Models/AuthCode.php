<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The `AuthCode` class represents a Laravel model for managing two-factor authentication (2FA) authentication codes.
 *
 * @package App\Models
 * @property int $id
 * @property int|null $user_id
 * @property string|null $2fa_auth_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode where2faAuthCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereUserId($value)
 * @mixin \Eloquent
 */
class AuthCode extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    public $table = "2fa_auth_codes";

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', '2fa_auth_code',
    ];
}