<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Validator;

/**
 * ProductService
 *
 * @author Victor Brito <victorbritosoares1@gmail.com>
 */
class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return Product[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Product::all();
    }

    /**
     * Search products by id or name
     *
     * @param $text
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search($text)
    {
        Validator::make(['text' => $text], [
            'text' => 'required',
        ])->validate();

        return $this->productRepository->findByIdOrName($text);
    }
}
