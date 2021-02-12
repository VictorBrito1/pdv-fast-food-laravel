<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Order
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class Order extends Model
{
    protected $table = 'orders';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders_products', 'order_id', 'product_id')->withPivot('amount');
    }
}
