<?php

namespace App\Observers;

use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Validator;
use Str;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     * @return void
     */
    public function creating(User $user): void
    {
        $user->password = app()->environment('production')
            ? Str::random(32)
            : Hash::make('password');

        $user->email = $this->validateEmail($user->email, $this->safeEmail(32));
        $user->geschlecht = $this->validateGender($user->geschlecht, User::FALLBACK_GENDER);
    }

    /**
     * Handle the User "updating" event.
     *
     * @param User $user
     * @return void
     */
    public function updating(User $user): void
    {
        $user->email = $this->validateEmail($user->email, $user->getOriginal('email'));
        $user->geschlecht = $this->validateGender($user->geschlecht, $user->getOriginal('geschlecht'));
    }

    /**
     * Generate random safe email address
     *
     * @param int $length
     * @return string
     */
    private function safeEmail(int $length = 16): string
    {
        return sprintf('%s@%s', Str::random($length), Str::random($length));
    }

    /**
     * Format email
     * If none provided, generate a random one
     *
     * @param string|null $email
     * @param string $fallback
     * @return string
     */
    private function validateEmail(string|null $email, string $fallback): string
    {
        $validator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email:rfc,dns']]
        );

        if ($validator->valid()) {
            return strtolower($email);
        }

        return $fallback;
    }

    /**
     * Validate the gender
     *
     * @param string|null $gender
     * @param string $fallback
     * @return string
     */
    private function validateGender(string|null $gender, string $fallback): string
    {
        return in_array($gender, User::GENDERS) ? $gender : $fallback;
    }
}

