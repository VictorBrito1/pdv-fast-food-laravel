<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

/**
 * OrderController
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * List of orders with or without status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $status = $request->get('status', '');

        return response()->json($this->orderService->getOrders($status));
    }

    /**
     * Shows order details
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($id)
    {
        return response()->json($this->orderService->show($id));
    }

    public function changeStatus(Request $request)
    {

    }

    public function finish(Request $request)
    {

    }

    /**
     * Add a product to a new order
     *
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addProductWithoutOrder(Request $request, $productId)
    {
        $amount = $request->get('amount', 1);

        return response()->json($this->orderService->addProduct($productId, $amount));
    }

    /**
     * Add a product to an existing order
     *
     * @param Request $request
     * @param $orderId
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addProductWithOrder(Request $request, $orderId, $productId)
    {
        $amount = $request->get('amount', 1);

        return response()->json($this->orderService->addProduct($productId, $amount, $orderId));
    }

    /**
     * Removes a product from an order
     *
     * @param Request $request
     * @param $orderId
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function removeProduct(Request $request, $orderId, $productId)
    {
        $amount = $request->get('amount', 1);

        return response()->json($this->orderService->removeProduct($productId, $orderId, $amount));
    }
}
