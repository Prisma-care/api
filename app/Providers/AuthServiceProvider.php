<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Patient::class => \App\Policies\PatientPolicy::class,
        \App\Album::class => \App\Policies\AlbumPolicy::class,
        \App\Story::class => \App\Policies\StoryPolicy::class,
        \App\Heritage::class => \App\Policies\HeritagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
