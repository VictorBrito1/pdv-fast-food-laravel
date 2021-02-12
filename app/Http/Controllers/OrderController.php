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

    public function index(Request $request)
    {

    }

    public function show(Request $request)
    {

    }

    public function changeStatus(Request $request)
    {

    }

    public function finish(Request $request)
    {

    }

    /**
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
}
