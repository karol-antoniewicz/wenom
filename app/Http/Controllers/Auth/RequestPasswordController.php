<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstLoginRequest;
use App\Models\User;
use App\Notifications\RequestPasswordNotification;
use Illuminate\Auth\Passwords\PasswordBroker;

class RequestPasswordController extends Controller
{
    // TODO: To be removed?
	public function execute(FirstLoginRequest $request): void
    {
        if ($request->get('schulnummer') !== config('wenom.schulnummer')) {
            return;
        }

        try {
			$user = User::query()
                ->where(column: $request->only(keys: ['email', 'kuerzel']))
                ->firstOrFail();

			$token = app(abstract: PasswordBroker::class)->createToken(user: $user);
            $user->notify(instance: new RequestPasswordNotification(token: $token));
		} finally {
            return;
        }
    }
}

