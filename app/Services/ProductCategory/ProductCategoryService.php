<?php

namespace App\Services\ProductCategory;

use App\Models\ProductCategory\ProductCategory;
use App\Models\ProductCategory\ProductCategoryTranslation;
use App\Services\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryService implements BaseServiceInterface
{
    /**
     * Create a new product category.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return ProductCategory::create($attributes);
    }

    /**
     * Create a new product category translation.
     *
     * @param array $attributes
     * @return Model
     */
    public function createTranslation(array $attributes): Model
    {
        return ProductCategoryTranslation::create($attributes);
    }

    public function insert(array $attributes): bool
    {
        return ProductCategory::insert($attributes);
    }

    public function insertTranslation(array $attributes): bool
    {
        return ProductCategoryTranslation::insert($attributes);
    }
}
