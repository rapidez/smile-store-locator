<?php

namespace Rapidez\SmileStoreLocator;

use Illuminate\Support\ServiceProvider;
use Rapidez\Core\RapidezFacade as Rapidez;

class SmileStoreLocatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'smilestorelocator');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/rapidez/smile-store-locator'),
        ], 'views');

        config(['frontend.maps' => [
            'key' => Rapidez::config('smile_map/map/provider_google_api_key'),
            'libraries' => Rapidez::config('smile_map/map/provider_google_libraries'),
            'icon' => config('rapidez.media_url').'/smile_map/marker/'.Rapidez::config('smile_map/map/provider_all_markerIcon'),
        ]]);
    }
}
