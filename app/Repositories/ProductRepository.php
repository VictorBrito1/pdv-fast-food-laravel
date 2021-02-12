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
     * @param $data | $id or $name
     * @return mixed
     */
    public function findByIdOrName($data)
    {
        $query = Product::query();

        if (isset($data['code'])) {
            $query->orWhere('id', '=', $data['code']);
        }

        if (isset($data['name'])) {
            $query->orWhere('name', 'like', "%{$data['name']}%");
        }

        return $query->get();
    }
}
