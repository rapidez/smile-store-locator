<?php

namespace Rapidez\SmileStoreLocator;

use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Rapidez\Core\Facades\Rapidez;

class SmileStoreLocatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/smile-store-locator.php', 'smile-store-locator');
    }

    public function boot()
    {
        if (config('smile-store-locator.routes')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'smilestorelocator');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/rapidez/smile-store-locator'),
        ], 'views');

        try {
            config(['frontend.maps' => [
                'key'       => Rapidez::config('smile_map/map/provider_google_api_key'),
                'libraries' => Rapidez::config('smile_map/map/provider_google_libraries'),
                'icon'      => config('rapidez.media_url').'/smile_map/marker/'.Rapidez::config('smile_map/map/provider_all_markerIcon'),
            ]]);
        } catch(QueryException $e) {
        }
    }
}
