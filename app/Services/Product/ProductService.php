<?php

namespace App\Services\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductTranslation;
use App\Services\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;

class ProductService implements BaseServiceInterface
{
    /**
     * Create a new product category.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return Product::create($attributes);
    }

    /**
     * Create a new product category translation.
     *
     * @param array $attributes
     * @return Model
     */
    public function createTranslation(array $attributes): Model
    {
        return ProductTranslation::create($attributes);
    }

    public function insert(array $attributes): bool
    {
        return Product::insert($attributes);
    }

    public function insertTranslation(array $attributes): bool
    {
        return ProductTranslation::insert($attributes);
    }
}
