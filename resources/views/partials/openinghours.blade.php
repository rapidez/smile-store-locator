<strong>@lang('Opening hours')</strong><br>
@foreach(array_merge(
    array_slice(range(0, 6), today()->dayOfWeek),
    array_slice(range(0, 6), 0, today()->dayOfWeek)
) as $dayOfWeek)
    <div class="flex">
        <div class="w-2/3">
            {{ ucfirst(Carbon\Carbon::create(Carbon\Carbon::getDays()[$dayOfWeek])->dayName) }}
        </div>
        <div class="w-1/3">
            @if($time = $retailer->times->first(function ($time) use ($dayOfWeek) {
                return $time->attribute_code == 'opening_hours' && $time->day_of_week == $dayOfWeek;
            }))
                {{ Carbon\Carbon::parse($time['start_time'])->format('H:i') }} -
                {{ Carbon\Carbon::parse($time['end_time'])->format('H:i') }}
            @else
                @lang('Closed')
            @endif
        </div>
    </div>
@endforeach

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
                {{ Carbon\Carbon::parse($specialTime['start_time'])->format('H:i') }} -
                {{ Carbon\Carbon::parse($specialTime['end_time'])->format('H:i') }}
            </div>
        </div>
    @endforeach
@endif
