<?php

namespace App\Console\Commands\Products;

use Illuminate\Console\Command;
use App\Traits\CommandTrait;
use Illuminate\Support\Facades\Artisan;
use App\Services\Product\ProductService;
use Illuminate\Support\Str;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Constants\ProductCategory\ProductCategoryConstant;
use Illuminate\Support\Facades\DB;
use App\Constants\Attribute\AttributeConstant;
use App\Services\Attribute\AttributeService;
use App\Repositories\AttributeChoice\AttributeChoiceRepository;
use App\Services\AttributeChoice\AttributeChoiceService;
use App\Repositories\Attribute\AttributeRepository;
use App\Models\Product\Product;
use App\Models\Attribute\Attribute;

class ImportProducts extends Command
{
    use CommandTrait;

    protected $productService, $productCategoryRepository, $attributeService, $attributeChoiceRepository, $attributeChoiceService, $attributeRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products data from Json file';

    public function __construct(
        ProductService $productService,
        ProductCategoryRepository $productCategoryRepository,
        AttributeService $attributeService,
        AttributeChoiceRepository $attributeChoiceRepository,
        AttributeChoiceService $attributeChoiceService,
        AttributeRepository $attributeRepository
    ) {
        parent::__construct();
        $this->productService = $productService;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->attributeService = $attributeService;
        $this->attributeChoiceRepository = $attributeChoiceRepository;
        $this->attributeChoiceService = $attributeChoiceService;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptTimeStart = $this->displayCommandStart('Importing products data has started...');

        $this->truncateTables([
            'products',
            'product_translations',
            'product_categories',
            'product_category_translations',
            'product_product_category',
            'attributes',
            'attribute_translations',
            'attribute_choices',
            'attribute_choice_translations',
            'attribute_choice_product',
        ]);

        Artisan::call('db:seed', ['class' => 'Database\Seeders\ProductCategory\ProductCategorySeeder']);

        $productsPath = storage_path('app/private/Products.json');
        $products = json_decode(file_get_contents($productsPath), true);

        $locales = config('app.locales');

        $productCategory = $this->productCategoryRepository->findByName(ProductCategoryConstant::TYPE_PHONE_CATEGORY_BG);

        $attributeTranslations = [];

        foreach (AttributeConstant::ATTRIBUTES_ARRAY as $attribute) {
            $attributeNew = $this->attributeService->create([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($locales as $locale) {
                $attributeTranslations[] = [
                    'attribute_id' => $attributeNew->id,
                    'locale' => $locale,
                    'slug' => Str::slug($attribute),
                    'name' => $attribute,
                ];
            }
        }

        $this->attributeService->insertTranslation($attributeTranslations);

        $productsData = [];
        $productsTranslations = [];
        $existingAttributeChoices = [];

        $attributeIdsMappedByName = DB::table('attribute_translations')
            ->join('attributes', 'attributes.id', '=', 'attribute_translations.attribute_id')
            ->pluck('attributes.id', 'attribute_translations.name')
            ->toArray();

        foreach ($products as $product) {
            $productsData[] = [
                'sku' => $product['objectId'],
                'color_identifier' => str()->random(10),
                'size_identifier' => str()->random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!empty($product['Model'])) {
                $productModelWithoutSymbols = str_replace('_', '', $product['Model']);
                $productTitle = $product['Brand'] . ' ' . $productModelWithoutSymbols;

                foreach ($locales as $locale) {
                    $productsTranslations[] = [
                        'sku' => $product['objectId'],
                        'locale' => $locale,
                        'slug' => Str::slug($productTitle),
                        'name' => $productTitle,
                    ];
                }
            }

            foreach ($product as $attributeName => $attributeChoiceValue) {
                if (in_array($attributeName, AttributeConstant::ATTRIBUTES_ARRAY) && !empty($attributeChoiceValue)) {
                    if (!isset($existingAttributeChoices[$attributeName])) {
                        $existingAttributeChoices[$attributeName] = [];
                    }

                    if (isset(AttributeConstant::ATTRIBUTE_SEPERATOR_MAPPING[$attributeName])) {
                        $separator = AttributeConstant::ATTRIBUTE_SEPERATOR_MAPPING[$attributeName];
                        $choice = explode($separator, $attributeChoiceValue)[0];
                    } else if ($attributeName == AttributeConstant::TYPE_ATTRIBUTE_MODEL) {
                        $choice = str_replace('_', '', $attributeChoiceValue);
                    } else {
                        $choice = $attributeChoiceValue;
                    }

                    $choice = trim($choice);

                    if (!in_array($choice, $existingAttributeChoices[$attributeName])) {
                        $existingAttributeChoices[$attributeName][] = $choice;
                        $attributesChoicesInsertData[] = [
                            'attribute_id' => $attributeIdsMappedByName[$attributeName],
                            'machine_name' => $choice,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $productAttributeChoices[$attributeName][] =
                        [
                            'sku' => $product['objectId'],
                            'choice_value' => $choice,
                        ];
                }
            }
        }

        if (!empty($productsData) && count($productsData) > 0) {
            $this->productService->insert($productsData);
        }

        $productIdsMappedByOldIds = Product::select('id', 'sku')
            ->pluck('id', 'sku')
            ->toArray();

        foreach ($productsTranslations as &$productTranslation) {
            $productTranslation['product_id'] = $productIdsMappedByOldIds[$productTranslation['sku']];
            unset($productTranslation['sku']);
        }

        $productTranslationsChunks = array_chunk($productsTranslations, 500);

        foreach ($productTranslationsChunks as $productTranslationsChunk) {
            $this->productService->insertTranslation($productTranslationsChunk);
        }

        $productIds = Product::pluck('id')->toArray();

        $productCategoryData = array_map(function ($productId) use ($productCategory) {
            return [
                'product_id' => $productId,
                'product_category_id' => $productCategory->id,
            ];
        }, $productIds);

        DB::table('product_product_category')->insert($productCategoryData);

        $chunkAttributesChoicesInsertData = array_chunk($attributesChoicesInsertData, 1500);
        foreach ($chunkAttributesChoicesInsertData as $chunk) {
            $this->attributeChoiceService->insert($chunk);
        }

        $productAttributeChoiceData = [];
        $attributes = Attribute::with('translations')->get();

        $attributeChoicesGroupedByAttributeName = $attributes->mapWithKeys(function ($attribute) {
            return [
                $attribute->translations->first()->name =>
                $attribute->attributeChoices->pluck('id', 'machine_name')->toArray()
            ];
        });

        foreach ($productAttributeChoices as $attributeName => $productAttributeChoiceArray) {
            foreach ($productAttributeChoiceArray as $productChoice) {
                if (isset($attributeChoicesGroupedByAttributeName[$attributeName])) {
                    $productId = $productIdsMappedByOldIds[$productChoice['sku']];
                    $choiceId = $attributeChoicesGroupedByAttributeName[$attributeName][$productChoice['choice_value']] ?? null;

                    if ($choiceId) {
                        $productAttributeChoiceData[] = [
                            'product_id' => $productId,
                            'attribute_choice_id' => $choiceId,
                        ];
                    }
                }
            }
        }

        $chunkProductAttributeChoiceData = array_chunk($productAttributeChoiceData, 1500);
        foreach ($chunkProductAttributeChoiceData as $chunk) {
            DB::table('attribute_choice_product')->insert($chunk);
        }

        $this->displayExecutionTime($scriptTimeStart);
    }
}
