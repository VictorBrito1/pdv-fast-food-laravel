<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

/**
 * ProductController
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['products' => $this->productService->getAll()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $data = $request->only(['code', 'name']);

        return response()->json(['products' => $this->productService->search($data)]);
    }
}
