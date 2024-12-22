<?php

namespace App\Constants\ProductCategory;

class ProductCategoryConstant {
    public const TYPE_PHONE_CATEGORY_BG = 'Телефони';
    public const TYPE_PHONE_CATEGORY_EN = 'Phones';

    public const TYPE_PHONE_CATEGORY_ARRAY = [
        'en' => self::TYPE_PHONE_CATEGORY_EN,
        'bg' => self::TYPE_PHONE_CATEGORY_BG, 
    ];
}