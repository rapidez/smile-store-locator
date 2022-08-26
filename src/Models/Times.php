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
        'date'       => 'date:Y-m-d',
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    protected $fillable = [];

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
                $builder->orderBy('date');
            }

            $builder->orderBy('date');
        });
    }

    public function getOpeningDateTimeAttribute()
    {
        if ($this->start_time->year !== 1970) {
            return $this->start_time;
        }

        return $this->start_time->setDateFrom($this->next_date);
    }

    public function getClosingDateTimeAttribute()
    {
        if ($this->end_time->year !== 1970) {
            return $this->end_time;
        }

        return $this->end_time->setDateFrom($this->next_date);
    }

    public function getNextDateAttribute()
    {
        if ($this->date) {
            return $this->date;
        }

        $date = Carbon::today();
        if($date->dayOfWeek === $this->day_of_week) {
            return $date;
        }

        return $date->next($this->day_of_week);
    }
}
