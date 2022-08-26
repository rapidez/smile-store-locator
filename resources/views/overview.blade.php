@extends('rapidez::layouts.app')

@section('title', __('Shop Search'))
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto mb-5 px-3 sm:px-0">
        <h1 class="font-bold text-2xl mb-5">
            @lang('Shop Search')
        </h1>

        <gmap v-cloak v-slot="{ selectLocation, currentLocation, selectedLocation, visibleLocations, zoomToPlace, retailers }">
            <div class="md:flex md:space-x-5">
                <div class="md:w-1/3">
                    <gmap-autocomplete v-on:place_changed="zoomToPlace" :select-first-on-enter="true" class="block mb-1">
                        <template v-slot:input="slotProps">
                            <x-rapidez::input
                                name="search"
                                :label="false"
                                ref="input"
                                class="py-3"
                                v-on:listeners="slotProps.listeners"
                                v-on:attrs="slotProps.attrs"
                                :placeholder="__('Zipcode or City')"
                                value="{{ request()->location }}"
                                autofocus
                            />
                        </template>
                    </gmap-autocomplete>

                    <a href="#" v-on:click.prevent="currentLocation" class="flex items-center text-primary mb-5 hover:underline">
                        <x-heroicon-s-location-marker class="h-4 w-4 mr-1"/>
                        @lang('Use my location')
                    </a>

                    <a
                        href="#"
                        v-for="retailer in retailers"
                        class="block border border-l-4 rounded p-2 mb-2 bg-gray-50 hover:bg-white"
                        :class="{
                            'border-primary': (selectedLocation && selectedLocation.address_id == retailer.address_id) || (visibleLocations ?? []).includes(retailer.address_id)
                        }"
                        v-on:click.prevent="selectLocation(retailer.address_id)"
                    >
                        <div>@{{ retailer.street }} - @{{ retailer.city }}</div>
                        <div class="text-sm">
                            <div v-if="!retailer.opening_time && retailer.closing_time">
                                <span class="text-green-600">@lang('Open')</span>,
                                <span class="text-gray-600">@lang('closing at') @{{ (new Date(retailer.closing_time)).toLocaleTimeString() }}</span>
                            </div>
                            <div v-else>
                                <span class="text-red-600">@lang('Closed')</span>,
                                <span class="text-gray-600">
                                    <template v-if="retailer.upcoming_opening_time.includes(':')">
                                        @lang('open again on') @{{ retailer.upcoming_opening_time }}
                                    </template>
                                    <template v-else>
                                        @lang('open at') @{{ retailer.upcoming_opening_time }}
                                    </template>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="md:w-2/3 relative">
                    <div v-if="selectedLocation" class="absolute bg-white p-3 rounded shadow z-10 top-2 left-2">
                        <div>@{{ selectedLocation.street }} - @{{ selectedLocation.city }}</div>
                        <div class="text-sm mb-5">
                            <div v-if="!selectedLocation.opening_time && selectedLocation.closing_time">
                                <span class="text-green-600">@lang('Open')</span>,
                                <span class="text-gray-600">@lang('closing at') @{{ (new Date(selectedLocation.closing_time)).toLocaleTimeString() }}</span>
                            </div>
                            <div v-else>
                                <span class="text-red-600">@lang('Closed')</span>,
                                <span class="text-gray-600">
                                    <template v-if="retailer.upcoming_opening_time.includes(':')">
                                        @lang('open again on') @{{ retailer.upcoming_opening_time }}
                                    </template>
                                    <template v-else>
                                        @lang('open at') @{{ retailer.upcoming_opening_time }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <a
                            :href="'{{ Rapidez::config('store_locator/seo/base_url', 'stores') }}/' + selectedLocation.url_key"
                            class="flex items-center font-bold underline"
                        >
                            @lang('Show store')
                            <x-heroicon-s-chevron-right class="h-4 w-4"/>
                        </a>
                    </div>
                    <gmap-map
                        ref="map"
                        :zoom="8"
                        :center="{ lat: 0, lng: 0 }"
                        :options="{ mapTypeControl: false, streetViewControl: false }"
                        class="w-full h-[500px]"
                    >
                        <gmap-marker
                            v-for="retailer in config.retailers"
                            :key="retailer.address_id"
                            :position="{ lat: retailer.latitude, lng: retailer.longitude }"
                            :icon="{ url: config.maps.icon, scaledSize: { width: 50, height: 59 } }"
                            v-on:click="selectLocation(retailer.address_id)"
                        />
                    </gmap-map>
                    <div v-if="selectedLocation" class="sm:flex mt-5">
                        <div class="sm:w-1/3 mb-3">
                            <strong class="block mb-3">@{{ selectedLocation.city }}</strong>
                            @{{ selectedLocation.street }}<br>
                            @{{ selectedLocation.postcode }}
                        </div>
                        <div class="sm:w-1/3 mb-3">
                            <div v-if="selectedLocation.facilities">
                                <strong class="block mb-3">@lang('Facilities')</strong>
                                <div v-for="facility in selectedLocation.facilities" class="flex items-center">
                                    <x-heroicon-s-check class="h-4 w-4 mr-1 text-primary"/> @{{ facility.facility }}
                                </div>
                            </div>
                        </div>
                        <div class="sm:w-1/3 mb-3">
                            <strong class="block mb-3">@lang('Opening hours')</strong>
                            <div class="flex" v-for="dayOfWeek in config.days_in_week_sorted">
                                <div class="w-1/2">
                                    @{{ config.day_names[dayOfWeek] }}
                                </div>
                                <div class="w-1/2">
                                    <div v-if="time = selectedLocation.times.find((time) => time.attribute_code == 'opening_hours' && time.day_of_week == dayOfWeek)">
                                        @{{ time.start_time.substring(11, 16) }} -
                                        @{{ time.end_time.substring(11, 16) }}
                                    </div>
                                    <div v-else>
                                        @lang('Closed')
                                    </div>
                                </div>
                            </div>

                            <div v-if="(specialHours = selectedLocation.times.filter((time) => time.attribute_code == 'special_opening_hours')).length">
                                <strong class="block my-3">@lang('Special opening hours')</strong>
                                <div class="flex" v-for="time in specialHours">
                                    <div class="w-1/2">
                                        @{{ time.date.substring(8, 10) }}-@{{ time.date.substring(5, 7) }}
                                        @{{ time.description }}
                                    </div>
                                    <div class="w-1/2">
                                        @{{ time.start_time.substring(11, 16) }} -
                                        @{{ time.end_time.substring(11, 16) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </gmap>
    </div>
@endsection
