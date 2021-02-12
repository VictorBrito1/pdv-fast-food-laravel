<?php

namespace App\Repositories;

use App\Models\Product;

/**
 * ProductRepository
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class ProductRepository
{
    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function searchByField($field, $value)
    {
        return Product::where($field, 'like', "%$value%")->get();
    }
}
