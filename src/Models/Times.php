<?php

namespace Rapidez\SmileStoreLocator\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Rapidez\Core\Models\Model;

class Times extends Model
{
    protected $table = 'smile_retailer_time_slots';

    protected $primaryKey = false;

    protected $casts = [
        'date' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('only-active-times', function (Builder $builder) {
            if (Schema::hasColumn('smile_retailer_time_slots', 'display_from_date')) {
                $builder->where(function ($query) {
                    $query->whereNull('display_from_date')
                          ->orWhere('display_from_date', '<=', Carbon::now()->toDateString());
                })->where(function ($query) {
                    $query->whereNull('display_to_date')
                          ->orWhere('display_to_date', '>=', Carbon::now()->toDateString());
                });
                $builder->orderBy('date');
            }

            $days = range(0, 6);
            $sortedDays = array_merge(
                array_slice($days, Carbon::now()->dayOfWeek),
                array_slice($days, 0, Carbon::now()->dayOfWeek)
            );

            $builder->orderByRaw('FIELD(day_of_week, '.implode(',', $sortedDays).')');
        });
    }
}
