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
                    {{ $retailer->values->contact_phone }}<br>
                    <br>
                    @if($closingTime = $retailer->closing_time)
                        <span class="text-green-600">@lang('Open')</span>,
                        <span class="text-gray-600">@lang('closing at') {{ $closingTime }}</span>
                    @else
                        <span class="text-red-600">@lang('Closed')</span>
                    @endif
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
                    @include('smilestorelocator::partials.openinghours')
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
