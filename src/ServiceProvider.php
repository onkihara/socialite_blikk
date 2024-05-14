<?php

namespace Onkihara\SocialiteBlikk;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as Baseprovider;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class ServiceProvider extends Baseprovider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // config -> merge to services.php
        
        $this->mergeConfigFrom(
            __DIR__.'/../config/services.php', 'services'
        );

        // blikk socialite driver

        $socialite = $this->app->make(SocialiteFactory::class);
        $socialite->extend('blikk', function () use ($socialite) {
            $config = config('services.blikk');
            return $socialite->buildProvider(Provider::class, $config);
        });

    }
}
