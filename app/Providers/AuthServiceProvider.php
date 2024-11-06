<?php

namespace App\Providers;

use App\Models\Leistung;
use App\Models\Schueler;
use App\Policies\LeistungFehlstundenPolicy;
use App\Policies\SchuelerFehlstundenPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Define the policies for the specified models
     *
     * @var string[]
     */
    protected $policies = [
        Leistung::class => LeistungFehlstundenPolicy::class,
        Schueler::class => SchuelerFehlstundenPolicy::class,
    ];
}
