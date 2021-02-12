<?php

namespace App\Services;

use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

/**
 * OrderService
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class OrderService
{
    /**
     * @param $productId
     * @param $amount
     * @param null $orderId
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addProduct($productId, $amount, $orderId = null)
    {
        $order = $this->validateAndGetUpdatedOrder($productId, $amount, $orderId);

        $product = Product::find($productId);
        $order->products()->attach($product, ['amount' => $amount]);

        return $order;
    }

    /**
     * @param $productId
     * @param $amount
     * @param $orderId
     * @param bool $add
     * @return Order
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateAndGetUpdatedOrder($productId, $amount, $orderId = null, $add = true)
    {
        Validator::make([
            'productId' => $productId,
            'amount' => $amount,
        ], [
            'productId' => 'exists:products,id',
            'amount' => 'integer|min:1',
        ])->validate();

        if ($orderId) {
            Validator::make(['orderId' => $orderId], [
                'orderId' => 'exists:orders,id',
            ])->validate();

            $order = Order::find($orderId);
        } else {
            $order = new Order();
        }

        $product = Product::find($productId);
        $orderPrice = $this->getNewPrice($order, $product, $amount, $add);

        $order->price = $orderPrice;
        $order->status = OrderStatusType::OPEN;
        $order->save();

        return $order;
    }

    /**
     * @param $order
     * @param $product
     * @param $amount
     * @param bool $add
     * @return float|int
     */
    private function getNewPrice($order, $product, $amount, $add)
    {
        $price = $product->price * $amount;
        $orderPrice = $order->price;

        if ($add) {
            $orderPrice += $price;
        } elseif ($orderPrice > 0) {
            $orderPrice -= $price;
        }

        return $orderPrice > 0 ? $orderPrice : 0;
    }
}
