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
     * @param $text
     * @return mixed
     */
    public function findByIdOrName($text)
    {
        return Product::where('id', '=', (int)$text)
            ->orWhere('name', 'like', "%$text%")->get();
    }
}
