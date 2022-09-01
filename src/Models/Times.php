<?php

namespace Rapidez\SmileStoreLocator\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Schema;
use Rapidez\Core\Models\Model;

class Times extends Model
{
    protected $table = 'smile_retailer_time_slots';

    protected $primaryKey = false;

    protected $casts = [
        'date'       => 'date:Y-m-d',
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    protected $fillable = [];

    protected $hidden = [
        'display_from_date',
        'display_to_date',
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
                })->where(function ($query) {
                    $query->whereNull('date')
                          ->orWhere('date', '>=', Carbon::now()->toDateString());
                });
            }

            $builder->orderBy('date');
        });
    }

    public function startTime(): Attribute
    {
        return Attribute::make(
            get: [$this, 'addDateToTime']
        );
    }

    public function endTime(): Attribute
    {
        return Attribute::make(
            get: [$this, 'addDateToTime']
        );
    }

    protected function addDateToTime($value)
    {
        $value = Carbon::parse($value);
        if ($value->year !== 1970) {
            return $value;
        }

        return $value->setDateFrom($this->next_date);
    }

    public function getNextDateAttribute()
    {
        if ($this->date) {
            return $this->date;
        }

        $date = Carbon::today();
        if ($date->dayOfWeek === $this->day_of_week) {
            return $date;
        }

        return $date->next($this->day_of_week);
    }
}
