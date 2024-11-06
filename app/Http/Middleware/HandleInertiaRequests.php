<?php

namespace App\Http\Middleware;

use App\Settings\FilterSettings;
use App\Settings\GdprSettings;
use App\Settings\GeneralSettings;
use App\Settings\MatrixSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Middleware to handle Inertia.js requests.
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * The root view for Inertia responses. This is the Blade view file that Inertia will render.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Optionally customizes the asset versioning strategy of Inertia.
     * Helps Inertia to determine if the assets need to be reloaded (useful for cache busting).
     *
     * @param Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Shares data across all Inertia responses.
     * This method is automatically called by Inertia to inject shared data into the Inertia responses.
     *
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $lastLogin = null;

        if ($user) {
            $loginLogs = $user->loginLogs()->latest()->skip(1)->first(); // Second last Login-Entry
            if ($loginLogs) {
                $lastLogin = $loginLogs->login?->format('d.m.Y H:i').' Uhr';
            }
        }

        return array_merge(parent::share($request), [
            'auth.user' => fn (): array|null => $user
				? $user->only('id', 'vorname', 'nachname', 'email', 'klassen', 'lerngruppen')
				: null,
            // Share whether the authenticated user is an administrator.
            'auth.administrator' => $user ? $user->is_administrator : false,
            'auth.lastLogin' => $lastLogin,
            // Share the school's name from configuration.
            'schoolName' => config('app.school_name'),
            // Share url from configuration.
            'appUrl' => config('app.url'),
            // Share various application settings.
            'settings' => [
				'general' => app(GeneralSettings::class),
				'filters' => app(FilterSettings::class),
				'matrix' => app(MatrixSettings::class),
				'gdpr' => app(GdprSettings::class),
			],
            // Share the application's version and npm configuration.
            'version' => config('wenom.version'),
            'npm' => config('wenom.npm'),
        ]);
    }
}
