@extends('rapidez::layouts.app')

@section('title', $retailer->values->meta_title)
@section('description', $retailer->values->meta_description)

@section('content')
    <div class="container mx-auto mb-5 px-3 sm:px-0">
        <h1 class="font-bold text-2xl mb-5">
            {{ $retailer->seller_code }}
        </h1>

        <div class="sm:flex sm:space-x-5">
            <div class="sm:w-1/3">
                <x-rapidez::button class="flex items-center mb-5" variant="outline" :href="route('smilestorelocator.overview')">
                    <x-heroicon-s-chevron-left class="h-4 w-4"/> @lang('All stores')
                </x-rapidez::button>

                <div class="border rounded bg-gray-50 p-5 mb-5">
                    <strong>{{ $retailer->city }}</strong><br>
                    {{ $retailer->street }}<br>
                    {{ $retailer->postcode }}<br>
                    {{ $retailer->values->contact_phone }}
                </div>

                @if($retailer->facilities)
                    <div class="border rounded bg-gray-50 p-5 mb-5">
                        @foreach($retailer->facilities as $facility)
                            <div class="flex items-center">
                                <x-heroicon-s-check class="h-4 w-4 mr-1 text-primary"/> {{ $facility->facility }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="border rounded bg-gray-50 p-5 mb-5">
                    <strong>@lang('Opening hours')</strong><br>
                    <?php
                    $times = $retailer
                        ->times
                        ->filter(fn ($time) => $time->attribute_code == 'opening_hours')
                        ->keyBy('day_of_week')
                        ->toArray();
                    while(key($times) != Carbon\Carbon::now()->dayOfWeek) next($times);
                    ?>
                    @for($i = 0; $i < 7; $i++)
                        @php $time = current($times) @endphp
                        <div class="flex">
                            <div class="w-2/3">
                                {{ ucfirst(Carbon\Carbon::create(Carbon\Carbon::getDays()[$time['day_of_week']])->dayName) }}
                            </div>
                            <div class="w-1/3">
                                {{ Carbon\Carbon::parse($time['start_time'])->format('H:i') }} -
                                {{ Carbon\Carbon::parse($time['end_time'])->format('H:i') }}
                            </div>
                        </div>
                        <?php current($times)['day_of_week'] == 6 ? reset($times) : next($times) ?>
                    @endfor

                    @if(($specialTimes = $retailer
                        ->times
                        ->filter(fn ($time) => $time->attribute_code == 'special_opening_hours'))->isNotEmpty())
                        <strong class="block mt-3">@lang('Special opening hours')</strong>
                        @foreach($specialTimes as $specialTime)
                            <div class="flex">
                                <div class="w-2/3">
                                    {{ $specialTime->date->format('d-m') }}
                                    {{ $specialTime->description }}
                                </div>
                                <div class="w-1/3">
                                    {{ Carbon\Carbon::parse($time['start_time'])->format('H:i') }} -
                                    {{ Carbon\Carbon::parse($time['end_time'])->format('H:i') }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="sm:w-2/3">
                @if($retailer->street_view)
                    <iframe src="{{ $retailer->street_view }}" frameborder="0" width="100%" height="450"></iframe>
                @endif
                @if($retailer->values->description)
                    <div class="prose prose-green max-w-none mt-5">
                        {!! $retailer->values->description !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
