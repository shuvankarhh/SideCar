<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Project;
use App\Xero\OauthTwoProvider;
use Illuminate\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         /*
         * Singleton as this is how the package talks to Xero,
         * there's no reason for this to need to change
         */
        $this->app->singleton(OauthTwoProvider::class, function (Application $app) {
            return new OauthTwoProvider([
                'clientId'                => config('xero.oauth.client_id'),
                'clientSecret'            => config('xero.oauth.client_secret'),
                'redirectUri'             => config('xero.oauth.redirect_full_url')
                    ? config('xero.oauth.redirect_uri')
                    : route(config('xero.oauth.redirect_uri')),
                'urlAuthorize'            => config('xero.oauth.url_authorize'),
                'urlAccessToken'          => config('xero.oauth.url_access_token'),
                'urlResourceOwnerDetails' => config('xero.oauth.url_resource_owner_details'),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
