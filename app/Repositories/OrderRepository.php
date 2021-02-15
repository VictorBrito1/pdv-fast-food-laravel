<?php

namespace App\Repositories;

use App\Models\Order;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $order = Order::find($id);

        if (!$order) {
            throw new NotFoundHttpException(null, null, 0, ['errors' => ['id' => ['Order not found.']]]);
        }

        return $order;
    }
}
