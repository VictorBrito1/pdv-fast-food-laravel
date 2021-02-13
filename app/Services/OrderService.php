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
     * @return Order
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addProduct($productId, $amount, $orderId = null)
    {
        $order = $this->validateAndGetUpdatedOrder($productId, $amount, $orderId);

        $product = Product::find($productId);
        $existingProduct = $order->products()->find($product);

        if ($existingProduct) { //If already have the product in the order, just increase the quantity
            $amount = $existingProduct->pivot->amount + $amount;
            $order->products()->updateExistingPivot($product, ['amount' => $amount]);
        } else {
            $order->products()->attach($product, ['amount' => $amount]);
        }

        $order->products;

        return $order;
    }

    /**
     * @param $productId
     * @param $orderId
     * @param $amount
     * @return Order
     * @throws \Illuminate\Validation\ValidationException
     */
    public function removeProduct($productId, $orderId, $amount)
    {
        $order = $this->validateAndGetUpdatedOrder($productId, $amount, $orderId, false);

        $product = Product::find($productId);
        $existingProduct = $order->products()->find($product);

        if ($existingProduct) {
            $existingAmount = $existingProduct->pivot->amount;

            if ($existingAmount > $amount) { //If the quantity sent is greater than the current quantity, it only decreases
                $order->products()->updateExistingPivot($product, ['amount' => $existingAmount - $amount]);
            } else { //Remove the product from the order
                $order->products()->detach($product);
            }
        }

        $order->products;

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
        } else {
            $existingProduct = $order->products()->find($product);

            if ($existingProduct) {
                $currentAmount = $existingProduct->pivot->amount;

                //If the quantity sent is greater than the existing quantity, the price will be decreased from the current amount.
                $orderPrice = $amount > $currentAmount
                    ? $orderPrice - ($product->price * $currentAmount)
                    : $orderPrice - $price;
            }
        }

        return $orderPrice > 0 ? $orderPrice : 0; //To not return a negative value
    }
}
