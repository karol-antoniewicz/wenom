<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
		if ($this->app->environment('production') || $this->app->environment('staging')) {
			URL::forceScheme('https');
		}

		JsonResource::withoutWrapping();
    }
}
