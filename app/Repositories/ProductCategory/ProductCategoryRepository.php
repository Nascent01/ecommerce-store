<?php

namespace App\Repositories\ProductCategory;

use App\Models\ProductCategory\ProductCategory;
use App\Models\ProductCategory\ProductCategoryTranslation;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryRepository implements EloquentRepositoryInterface
{
    /**
     * Find a product category by ID.
     *
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model
    {
        return ProductCategory::find($id);
    }

    public function findByName(string $name): ?Model
    {
        return ProductCategory::whereHas('translations', function ($query) use ($name) {
            $query->where('name', $name);
        })->first();
    }
}
