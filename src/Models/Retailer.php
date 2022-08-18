<?php

namespace Rapidez\SmileStoreLocator\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rapidez\Core\Models\Model;

class Retailer extends Model
{
    protected $table = 'smile_retailer_address';

    protected $primaryKey = 'address_id';

    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'facilities' => 'object',
    ];

    protected $values;

    protected $fillable = [
        'retailer_id',
        'created_at',
        'updated_at',
        'street',
        'postcode',
        'city',
        'region',
        'region_id',
        'country_id',
        'latitude',
        'longitude',
        'street_view',
        'facilities',
        'url_key',
        'phone',
        'times'
    ];

    protected static function booted()
    {
        static::addGlobalScope('with-url-key', function (Builder $builder) {
            $urlKeyAttributeId = Cache::rememberForever('smile.url-key-attribute', fn () => DB::table('eav_attribute')
                ->select('attribute_id')
                ->join('eav_entity_type', 'eav_entity_type.entity_type_id', '=', 'eav_attribute.entity_type_id')
                ->where('attribute_code', 'url_key')
                ->where('entity_type_code', 'smile_seller')
                ->first()->attribute_id);

            $builder
                ->select('smile_retailer_address.*')
                ->selectSub(
                    DB::table('smile_seller_entity')
                        ->select('value')
                        ->join('smile_seller_entity_varchar', function ($join) use ($urlKeyAttributeId) {
                            $join->on('smile_seller_entity_varchar.entity_id', '=', 'smile_seller_entity.entity_id')
                                ->where('attribute_id', $urlKeyAttributeId)
                                ->whereIn('store_id', [0, config('rapidez.store')]);
                        })
                        ->whereColumn('smile_seller_entity.entity_id', 'smile_retailer_address.retailer_id')
                        ->orderByDesc('store_id')
                        ->limit(1),
                    'url_key'
                );
        });

        static::addGlobalScope('with-phone', function (Builder $builder) {
            $phoneAttributeId = Cache::rememberForever('smile.phone-attribute', fn () => DB::table('eav_attribute')
                ->select('attribute_id')
                ->join('eav_entity_type', 'eav_entity_type.entity_type_id', '=', 'eav_attribute.entity_type_id')
                ->where('attribute_code', 'contact_phone')
                ->where('entity_type_code', 'smile_seller')
                ->first()->attribute_id);

            $builder
                ->selectSub(
                    DB::table('smile_seller_entity')
                        ->select('value')
                        ->join('smile_seller_entity_varchar', function ($join) use ($phoneAttributeId) {
                            $join->on('smile_seller_entity_varchar.entity_id', '=', 'smile_seller_entity.entity_id')
                                ->where('attribute_id', $phoneAttributeId)
                                ->whereIn('store_id', [0, config('rapidez.store')]);
                        })
                        ->whereColumn('smile_seller_entity.entity_id', 'smile_retailer_address.retailer_id')
                        ->orderByDesc('store_id')
                        ->limit(1),
                    'phone'
                );
        });

        static::addGlobalScope('only-active', function (Builder $builder) {
            $activeAttributeId = Cache::rememberForever('smile.active-attribute', fn () => DB::table('eav_attribute')
                ->select('attribute_id')
                ->join('eav_entity_type', 'eav_entity_type.entity_type_id', '=', 'eav_attribute.entity_type_id')
                ->where('attribute_code', 'is_active')
                ->where('entity_type_code', 'smile_seller')
                ->first()->attribute_id);

            $builder
                ->where(
                    DB::table('smile_seller_entity')
                        ->select('value')
                        ->join('smile_seller_entity_int', function ($join) use ($activeAttributeId) {
                            $join->on('smile_seller_entity_int.entity_id', '=', 'smile_seller_entity.entity_id')
                                ->where('attribute_id', $activeAttributeId)
                                ->whereIn('store_id', [0, config('rapidez.store')]);
                        })
                        ->whereColumn('smile_seller_entity.entity_id', 'smile_retailer_address.retailer_id')
                        ->orderByDesc('store_id')
                        ->limit(1),
                    1
                );
        });
    }

    public function times()
    {
        return $this->hasMany(Times::class, 'retailer_id', 'retailer_id');
    }

    public function getValuesAttribute()
    {
        if ($this->values) {
            return $this->values;
        }

        foreach (['int', 'text', 'varchar', 'datetime', 'decimal'] as $type) {
            $queries[] = DB::table('smile_seller_entity')
                ->select(['attribute_code', 'value'])
                ->join('smile_seller_entity_'.$type, 'smile_seller_entity.entity_id', '=', 'smile_seller_entity_'.$type.'.entity_id')
                ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'smile_seller_entity_'.$type.'.attribute_id')
                ->where('smile_seller_entity.entity_id', $this->retailer_id)
                ->whereIn('store_id', [0, config('rapidez.store')])
                ->orderBy('store_id');
        }

        $finalQuery = array_shift($queries);
        foreach ($queries as $query) {
            $finalQuery->unionAll($query);
        }

        foreach ($finalQuery->get() as $item) {
            $values[$item->attribute_code] = $item->value;
        }

        return $this->values = (object) $values;
    }

    public function getOpeningTimeAttribute()
    {
        if ($specialOpeningHour = $this->times->first(function ($time) {
            return $time->attribute_code == 'special_opening_hours' && $time->date->toDateString() == today()->toDateString();
        })) {
            $date = Carbon::parse($specialOpeningHour->start_time)->setDateFrom(today());

            return $date->isFuture() ? $date : false;
        }

        if ($openingHour = $this->times->first(function ($time) {
            return $time->attribute_code == 'opening_hours' && $time->day_of_week == today()->dayOfWeek;
        })) {
            $date = Carbon::parse($openingHour->start_time)->setDateFrom(today());

            return $date->isFuture() ? $date : false;
        }

        return false;
    }

    public function getClosingTimeAttribute()
    {
        if ($specialOpeningHour = $this->times->first(function ($time) {
            return $time->attribute_code == 'special_opening_hours' && $time->date->toDateString() == today()->toDateString();
        })) {
            $date = Carbon::parse($specialOpeningHour->end_time)->setDateFrom(today());

            return $date->isFuture() ? $date : false;
        }

        if ($openingHour = $this->times->first(function ($time) {
            return $time->attribute_code == 'opening_hours' && $time->day_of_week == today()->dayOfWeek;
        })) {
            $date = Carbon::parse($openingHour->end_time)->setDateFrom(today());

            return $date->isFuture() ? $date : false;
        }

        return false;
    }

    public function getUpcomingOpeningTimeAttribute()
    {
        // When the retailer has an opening_time, then it will be opened later this day
        if ($this->opening_time) {
            return $this->opening_time->format('H:i');
        }

        $dayNumber = Carbon::now()->dayOfWeek;
        $dayIterator = 1;
        $upcomingDay = false;

        while (!$upcomingDay) {
            $upcomingDay = $this->times->filter(fn ($time) => $time->attribute_code === 'special_opening_hours' && Carbon::now()->addDays($dayIterator)->isSameDay(Carbon::createFromFormat('Y-m-d', $time->date)))->first();
            $dayIterator += 1;

            // When the upcoming day's start- and end_time are the same, then the retailer is closed the upcoming day, so set the upcomingDay false
            $upcomingDay = $upcomingDay && $upcomingDay->start_time == $upcomingDay->end_time ? false : $upcomingDay;

            // If there aren't special opening hours, get the default opening hours
            if (!$upcomingDay) {
                $dayNumber = $dayNumber + 1 !== 7 ? $dayNumber + 1 : 0;
                $upcomingDay = $this->times->filter(fn ($time) => $time->attribute_code === 'opening_hours' && (int) $time->day_of_week == $dayNumber);
            }
        }

        // If the retailer no longer opens today, show what day it will open again. Otherwise, show the time the store opens today
        return $dayNumber !== Carbon::now()->dayOfWeek ? strtolower(config('frontend.day_names')[$dayNumber]) : $upcomingDay->start_time;
    }
}
