<?php

namespace Rapidez\SmileStoreLocator\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Rapidez\Core\Models\Model;

class Seller extends Model
{
    protected $table = 'smile_seller_entity';

    protected $primaryKey = 'entity_id';

    public function retailer(): HasOne
    {
        return $this->hasOne(Retailer::class, 'retailer_id');
    }

    public function times(): HasMany
    {
        return $this->hasMany(Times::class, 'retailer_id');
    }
}
