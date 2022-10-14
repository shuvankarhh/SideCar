<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Xero\UserStorageProvider;
use App\Models\ProjectApiSystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OauthCredentialManager::class, function(Application $app) {
            // $app->make(config('xero.credential_store'))
            return new UserStorageProvider(
                \Auth::user(), // Storage Mechanism 
                $app->make('session.store'), // Used for storing/retrieving oauth 2 "state" for redirects
                $app->make(\Webfox\Xero\Oauth2Provider::class) // Used for getting redirect url and refreshing token
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
