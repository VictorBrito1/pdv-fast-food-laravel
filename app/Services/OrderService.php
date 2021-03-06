<?php

namespace App\Services;

use App\Enums\OrderStatusType;
use App\Enums\PaymentTypeType;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Validator;

/**
 * OrderService
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class OrderService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

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

        return $this->loadProductsInOrder($order);
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

        return $this->loadProductsInOrder($order);
    }

    /**
     * @param string $status
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getOrders($status = '')
    {
        $statusString = OrderStatusType::getValuesAsStringWithComma();

        Validator::make(['status' => $status], [
            'status' => "string|in:{$statusString}"
        ])->validate();

        return $this->orderRepository->getOrders($status);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($id)
    {
        $order = $this->orderRepository->find($id);

        return $this->loadProductsInOrder($order);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function finishOrder($id, $data)
    {
        $order = $this->orderRepository->find($id);

        $paymentTypesString = PaymentTypeType::getValuesAsStringWithComma();
        $orderPrice = $order->price;
        $data['order_price'] = $orderPrice;

        Validator::make($data, [
            'client_name' => 'string|required',
            'payment_type' => "string|required|in:{$paymentTypesString}",
            'total_paid' => 'numeric|required|gte:order_price',
            'note' => 'string',
        ])->validate();

        $total_paid = $data['total_paid'];
        $payment_type = $data['payment_type'];
        $change = 0;

        //Calculates the change if the payment was in cash and greater than the order amount.
        if ($payment_type === PaymentTypeType::CASH && $total_paid > $orderPrice) {
            $change = $total_paid - $orderPrice;
        }

        $order->status = OrderStatusType::SENT_TO_THE_KITCHEN;
        $order->payment_type = $payment_type;
        $order->client_name = $data['client_name'];
        $order->total_paid = $total_paid;
        $order->change = $change;
        $order->note = $data['note'] ?? null;
        $order->save();

        return $this->loadProductsInOrder($order);
    }

    /**
     * @param $id
     * @param null $status
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeStatus($id, $status = null)
    {
        $order = $this->orderRepository->find($id);
        $statusString = OrderStatusType::getValuesAsStringWithComma();

        Validator::make(['status' => $status], [
            'status' => "required|string|in:$statusString",
        ])->validate();

        $order->status = $status;
        $order->save();

        return $this->loadProductsInOrder($order);
    }

    /**
     * @param $order
     * @return mixed
     */
    private function loadProductsInOrder($order)
    {
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
