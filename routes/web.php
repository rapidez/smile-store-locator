<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Rapidez\Core\RapidezFacade as Rapidez;
use Rapidez\SmileStoreLocator\Models\Retailer;

$baseUrl = Rapidez::config('store_locator/seo/base_url', 'stores');

Route::get($baseUrl, function () {
    config(['frontend.retailers' => Retailer::with('times')->get()->append('closing_time')]);
    config(['frontend.day_names' => collect(Carbon::getDays())->map(function ($day, $index) {
        return ucfirst(Carbon::create(Carbon::getDays()[$index])->dayName);
    })]);
    config(['frontend.days_in_week_sorted' => array_merge(
        array_slice(range(0, 6), today()->dayOfWeek),
        array_slice(range(0, 6), 0, today()->dayOfWeek)
    )]);

    return view('smilestorelocator::overview');
})->name('smilestorelocator.overview');

Route::get($baseUrl.'/{seller}', function ($seller) {
    $retailer = Retailer::having('url_key', $seller)->firstOrFail();

    return view('smilestorelocator::detail', compact('retailer'));
})->name('smilestorelocator.detail');
