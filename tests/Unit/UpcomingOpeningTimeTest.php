<?php

namespace Rapidez\SmileStoreLocator\Tests\Unit;

use Carbon\Carbon;
use Rapidez\SmileStoreLocator\Models\Retailer;
use Rapidez\SmileStoreLocator\Models\Times;
use Rapidez\SmileStoreLocator\Tests\TestCase;

class UpcomingOpeningTimeTest extends TestCase
{
    public function testRetailerOpeningLaterToday()
    {
        $retailer = Retailer::make()
            ->setRelation(
                'times',
                Times::hydrate([
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 '.Carbon::now()->addHour(2)->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                ])
            );

        $this->assertEquals(Carbon::now()->addHour(2)->format('H:i'), $retailer->upcoming_opening_time);
    }

    public function testRetailerAlreadyOpenShowsNextOpeningDay()
    {
        $retailer = Retailer::make()
            ->setRelation(
                'times',
                Times::hydrate([
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 '.Carbon::now()->subHour()->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->addDay()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 '.Carbon::now()->subHour()->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                ])
            );

        $this->assertEquals(today()->addDay()->dayName, $retailer->upcoming_opening_time);
    }

    public function testRetailerSpecialTimeOpeningLaterToday()
    {
        $retailer = Retailer::make()
            ->setRelation(
                'times',
                Times::hydrate([
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 '.Carbon::now()->addHour(2)->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                    [
                        'attribute_code'    => 'special_opening_hours',
                        'day_of_week'       => null,
                        'date'              => Carbon::now()->format('Y-m-d'),
                        'start_time'        => '1970-01-01 '.Carbon::now()->addHour(2)->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                        'display_to_date'   => Carbon::now()->addDays(2)->format('Y-m-d'),
                    ],
                ])
            );

        $this->assertEquals(Carbon::now()->addHour(2)->format('H:i'), $retailer->upcoming_opening_time);
    }

    public function testRetailerSpecialTimeOpeningWithRegularOpeningTimeEarlier()
    {
        $retailer = Retailer::make()
            ->setRelation(
                'times',
                Times::hydrate([
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 '.Carbon::now()->addHour(1)->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                    [
                        'attribute_code'    => 'special_opening_hours',
                        'day_of_week'       => null,
                        'date'              => Carbon::now()->format('Y-m-d'),
                        'start_time'        => '1970-01-01 '.Carbon::now()->addHour(2)->format('H:i:s'),
                        'end_time'          => '1970-01-01 '.Carbon::now()->addHours(4)->format('H:i:s'),
                        'description'       => null,
                        'display_from_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                        'display_to_date'   => Carbon::now()->addDays(2)->format('Y-m-d'),
                    ],
                ])
            );

        $this->assertEquals(Carbon::now()->addHour(2)->format('H:i'), $retailer->upcoming_opening_time);
    }

    public function testRetailerClosedAndOpeningNextDay()
    {
        $retailer = Retailer::make()
            ->setRelation(
                'times',
                Times::hydrate([
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 00:00:00',
                        'end_time'          => '1970-01-01 00:00:00',
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                    [
                        'attribute_code'    => 'opening_hours',
                        'day_of_week'       => today()->addDay()->dayOfWeek,
                        'date'              => null,
                        'start_time'        => '1970-01-01 09:00:00',
                        'end_time'          => '1970-01-01 18:00:00',
                        'description'       => null,
                        'display_from_date' => null,
                        'display_to_date'   => null,
                    ],
                ])
            );

        $this->assertEquals(today()->addDay()->dayName, $retailer->upcoming_opening_time);
    }
}
