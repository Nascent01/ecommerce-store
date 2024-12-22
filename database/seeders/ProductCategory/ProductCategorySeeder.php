<?php

namespace Database\Seeders\ProductCategory;

use App\Constants\ProductCategory\ProductCategoryConstant;
use App\Services\ProductCategory\ProductCategoryService;
use Illuminate\Database\Seeder;

class ProductCategoryPhoneSeeder extends Seeder
{
    protected $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryRepository)
    {
        $this->productCategoryService = $productCategoryRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = config('app.locales');

     $productCategory = $this->productCategoryService->create([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($locales as $locale) {
            $this->productCategoryService->createTranslation([
                'product_category_id' => $productCategory->id,
                'locale' => $locale,
                'name' => ProductCategoryConstant::TYPE_PHONE_CATEGORY_ARRAY[$locale],
            ]);
        }
    }
}
