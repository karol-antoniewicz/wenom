<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany, BelongsToMany};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * The `User` class represents a Laravel model for managing remarks associated with Users.
 *
 * @package App\Models
 * @property int $id
 * @property int|null $ext_id
 * @property string $kuerzel
 * @property string $vorname
 * @property string $nachname
 * @property string $geschlecht
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property int $is_administrator
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Daten|null $daten
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Klasse> $klassen
 * @property-read int|null $klassen_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lerngruppe> $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\UserSetting|null $userSettings
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVorname($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Klasse> $klassen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lerngruppe> $lerngruppen
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @method static Builder|User administrator()
 * @method static Builder|User lehrer()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLogin> $loginLogs
 * @property-read int|null $login_logs_count
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
	use HasApiTokens;
	use HasFactory;
	use HasProfilePhoto;
	use Notifiable;
	use TwoFactorAuthenticatable;

    /*
     * Define a list of allowed genders
     *
     * @return string[]
     */
	const GENDERS = ['m', 'w', 'd', 'x'];

    /*
    * Define the fallback gender
    *
    * @return string
    */
    const FALLBACK_GENDER = 'x';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
		'id', 'ext_id', 'kuerzel', 'vorname', 'nachname', 'geschlecht', 'email', 'password', 'is_administrator',
    ];

    /**
     * Attributes listed here will be hidden when the model is converted to an array or JSON response,
     *
     * @var string[]
     */
    protected $hidden = [
		'password',
		'remember_token',
		'two_factor_recovery_codes',
		'two_factor_secret',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
		'administrator' => 'boolean',
        'settings' => 'object',
    ];

    /**
     * Defines additional computed attributes that are appended to the model's array or JSON representation,
     *
     * @var string[]
     */
    protected $appends = [
        'profile_photo_url',
    ];


    /**
     * @var bool $otpVerified
     */
    protected bool $otpVerified = false;

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
	public function lerngruppen(): BelongsToMany
	{
		return $this->belongsToMany(Lerngruppe::class, 'lerngruppe_user');
	}

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
	public function klassen(): BelongsToMany
	{
		return $this->belongsToMany(Klasse::class, 'klasse_user');
	}

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
	public function daten(): HasOne // TODO Karol
	{
		return $this->hasOne(Daten::class);
	}

    /**
     * Scope a query to only include administrator users.
     *
     * @return void
     */
    public function scopeAdministrator(Builder $query): void
    {
        $query->where('is_administrator', true);
    }

    /**
     * Scope a query to only include lehrer users.
     *
     * @return void
     */
    public function scopeLehrer(Builder $query): void
    {
        $query->where('is_administrator', false);
    }

    /**
     * Determine whether user is an administrator
     *
     * @return bool
     */
    public function isAdministrator(): bool
	{
		return $this->is_administrator;
	}

    /**
     * Determine whether user is an administrator
     *
     * @return bool
     */
	public function isLehrer(): bool
	{
		return ! $this->isAdministrator();
	}

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function userSettings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Retrieve filters for a specific column from user settings or configuration.
     *
     * @param string $column
     * @return array
     */
    public function filters(string $column): array
    {
        $filterColumn = "filters_{$column}";

        return $this->userSettings()->exists() && $this->userSettings->$filterColumn !== null
            ? json_decode(json_encode($this->userSettings->$filterColumn), true)
            : config("wenom.filters.{$column}");
    }

    /**
     * Get the login logs for the user.
     *
     * @return HasMany
     */
    public function loginLogs(): HasMany
    {
        return $this->hasMany(UserLogin::class);
    }

    /**
     * OTP Verified setter
     *
     * @var bool $verified
     * @return void
     */
    public function setOtpVerified(bool $verified): void
    {
        $this->otpVerified = $verified;
    }

    /**
     * OTP Verified getter
     *
     * @return bool
     */
    public function getOtpVerified(): bool
    {
        return $this->otpVerified;
    }

    /**
     * Defines if current user must verify via OTP
     *
     * @return bool
     */
    public function mustVerifyOtp(): bool
    {
        // Admin can override the setting
        if ((bool) config('wenom.two_factor_authentication')) {
            return true;
        }

        return $this?->userSettings?->twofactor_otp == true
            && session()->get('otp_verified') == false;
    }

}
