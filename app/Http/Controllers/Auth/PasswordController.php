<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstLoginRequest;
use App\Models\User;
use App\Notifications\RequestPasswordNotification;
use Hash;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use Str;

class PasswordController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Auth/RequestPassword');
    }

    public function execute(FirstLoginRequest $request): void
    {
        if ($request->get('schulnummer') !== config('wenom.schulnummer')) {
            return;
        }

        try {
            $user = User::where(column: $request->only(keys: ['email', 'kuerzel']))->firstOrFail();
            $token = app(abstract: PasswordBroker::class)->createToken(user: $user);
            $user->notify(instance: new RequestPasswordNotification(token: $token));
        } finally {
            return;
        }
    }

    public function reset_form(string $token, Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->get('email')
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $response = Password::reset(
            $request->only('email', 'token', 'password', 'password_confirmation'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', trans($response));
        }

        return back()->withErrors(['email' => trans($response)]);
    }
}
