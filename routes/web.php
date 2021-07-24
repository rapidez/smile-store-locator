<?php

use Illuminate\Support\Facades\Route;
use Rapidez\Core\RapidezFacade as Rapidez;
use Rapidez\SmileStoreLocator\Models\Retailer;

$baseUrl = Rapidez::config('store_locator/seo/base_url', 'stores');

Route::get($baseUrl, function () {
    config(['frontend.retailers' => Retailer::with('times')->get()]);
    return view('smilestorelocator::overview');
})->name('smilestorelocator.overview');

Route::get($baseUrl.'/{seller}', function ($seller) {
    $retailer = Retailer::having('url_key', $seller)->first();
    return view('smilestorelocator::detail', compact('retailer'));
})->name('smilestorelocator.detail');
