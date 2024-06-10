<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\QueryException;
use Rapidez\Core\Facades\Rapidez;
use Rapidez\SmileStoreLocator\Models\Retailer;

try {
    $url = Rapidez::config('store_locator/seo/base_url', 'stores');
} catch (QueryException $e) {
    $url = 'stores';
}

Route::prefix()->group(function () {
    Route::get('', function () {
        config(['frontend.retailers' => Retailer::with('times')->get()->append(['opening_time', 'closing_time', 'upcoming_opening'])]);
        config(['frontend.day_names' => collect(Carbon::getDays())->map(function ($day, $index) {
            return ucfirst(Carbon::create(Carbon::getDays()[$index])->dayName);
        })]);
        config(['frontend.days_in_week_sorted' => array_merge(
            array_slice(range(0, 6), today()->dayOfWeek),
            array_slice(range(0, 6), 0, today()->dayOfWeek)
        )]);

        return view('smilestorelocator::overview');
    })->name('smilestorelocator.overview');

    Route::get('/{seller}', function ($seller) {
        $retailer = Retailer::having('url_key', $seller)->firstOrFail();

        return view('smilestorelocator::detail', compact('retailer'));
    })->name('smilestorelocator.detail');
});
