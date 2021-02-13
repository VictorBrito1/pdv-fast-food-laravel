<?php

namespace App\Repositories;

use App\Models\Order;

/**
 * OrderRepository
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class OrderRepository
{
    /**
     * @param string $status
     * @return Order[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getOrders($status = '')
    {
        $query = Order::with('products');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }
}
