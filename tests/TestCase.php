<?php

namespace Rapidez\SmileStoreLocator\Tests;

use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Rapidez\Core\RapidezServiceProvider;
use TorMorten\Eventy\EventServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Rapidez\SmileStoreLocator\SmileStoreLocatorServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        Carbon::setTestNow(Carbon::now()->setTime(12, 30));

        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            EventServiceProvider::class,
            RapidezServiceProvider::class
        ];
    }
}
