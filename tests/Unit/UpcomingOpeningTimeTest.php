<?php

namespace Rapidez\SmileStoreLocator\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Rapidez\SmileStoreLocator\Models\Retailer;
use Rapidez\SmileStoreLocator\Models\Times;
use Rapidez\SmileStoreLocator\Tests\TestCase;

class UpcomingOpeningTimeTest extends TestCase
{
    public function test()
    {
        config(['frontend.day_names' => collect(Carbon::getDays())->map(function ($day, $index) {
            return ucfirst(Carbon::create(Carbon::getDays()[$index])->dayName);
        })->toArray()]);

        $tests = [
            0 => Carbon::now()->addHour()->format('H:i'),
            1 => strtolower(config('frontend.day_names')[today()->addDay()->dayOfWeek]),
            2 => Carbon::now()->addHour()->format('H:i'),
            3 => strtolower(config('frontend.day_names')[today()->addDay()->dayOfWeek])
        ];

        $retailers = $this->generateTestRetailers();

        foreach ($tests as $key => $result) {
            $this->assertEquals($retailers[$key]->upcoming_opening_time, $result);
        }
    }

    public function generateTestRetailers()
    {
        $times = [
            // Opens later this day
            Times::hydrate([
                [
                    'attribute_code' => 'opening_hours',
                    'day_of_week' => today()->dayOfWeek,
                    'date' => null,
                    'start_time' => '1970-01-01 ' . Carbon::now()->addHour()->format('H:i:s'),
                    'end_time' => '1970-01-01 ' . Carbon::now()->addHours(4)->format('H:i:s'),
                    'description' => null,
                    'display_from_date' => null,
                    'display_to_date' => null
                ]
            ]),
            // Open now
            Times::hydrate([
                [
                    'attribute_code' => 'opening_hours',
                    'day_of_week' => today()->dayOfWeek,
                    'date' => null,
                    'start_time' => '1970-01-01 ' . Carbon::now()->subHour()->format('H:i:s'),
                    'end_time' => '1970-01-01 ' . Carbon::now()->addHours(4)->format('H:i:s'),
                    'description' => null,
                    'display_from_date' => null,
                    'display_to_date' => null
                ]
            ]),
            // Special opening time -> opens later this day
            Times::hydrate([
                [
                    'attribute_code' => 'opening_hours',
                    'day_of_week' => today()->dayOfWeek,
                    'date' => null,
                    'start_time' => '1970-01-01 ' . Carbon::now()->addHour()->format('H:i:s'),
                    'end_time' => '1970-01-01 ' . Carbon::now()->addHours(4)->format('H:i:s'),
                    'description' => null,
                    'display_from_date' => null,
                    'display_to_date' => null
                ],
                [
                    'attribute_code' => 'special_opening_hours',
                    'day_of_week' => null,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'start_time' => '1970-01-01 ' . Carbon::now()->addHour()->format('H:i:s'),
                    'end_time' => '1970-01-01 ' . Carbon::now()->addHours(4)->format('H:i:s'),
                    'description' => null,
                    'display_from_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'display_to_date' => Carbon::now()->addDays(2)->format('Y-m-d')
                ]
            ]),
            // Closed today and opens next day
            Times::hydrate([
                [
                    'attribute_code' => 'opening_hours',
                    'day_of_week' => today()->dayOfWeek,
                    'date' => null,
                    'start_time' => '1970-01-01 00:00:00',
                    'end_time' => '1970-01-01 00:00:00',
                    'description' => null,
                    'display_from_date' => null,
                    'display_to_date' => null
                ],
                [
                    'attribute_code' => 'opening_hours',
                    'day_of_week' => today()->addDay()->dayOfWeek,
                    'date' => null,
                    'start_time' => '1970-01-01 09:00:00',
                    'end_time' => '1970-01-01 18:00:00',
                    'description' => null,
                    'display_from_date' => null,
                    'display_to_date' => null
                ]
            ])
        ];

        Retailer::unguard();
        $retailers = [];
        for($id = 1; $id <= 4; $id++) {
            $retailers[] = Retailer::make([
                'retailer_id' => $id
            ]);
        }

        foreach($retailers as $key => $retailer) {
            $retailers[$key]->setRelation('times', $times[$key]);
        }

        return $retailers;
    }
}
