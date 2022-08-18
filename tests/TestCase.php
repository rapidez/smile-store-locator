<?php

namespace Rapidez\SmileStoreLocator\Tests;

use Illuminate\Contracts\Console\Kernel;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Rapidez\SmileStoreLocator\SmileStoreLocatorServiceProvider;
use Rapidez\Core\RapidezServiceProvider;
use TorMorten\Eventy\EventServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SmileStoreLocatorServiceProvider::class,
            EventServiceProvider::class,
            RapidezServiceProvider::class
        ];
    }
}
