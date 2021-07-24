<?php

namespace Rapidez\SmileStoreLocator\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rapidez\Core\Models\Model;

class Retailer extends Model
{
    protected $table = 'smile_retailer_address';

    protected $primaryKey = 'address_id';

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'facilities' => 'object',
    ];

    protected $values;

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

        return $this->values = (object)$values;
    }
}
