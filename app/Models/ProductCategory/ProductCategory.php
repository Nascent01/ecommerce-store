<?php

namespace App\Models\ProductCategory;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ProductCategory extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];
}
