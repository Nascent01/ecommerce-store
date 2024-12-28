<?php

namespace App\Models\AttributeChoice;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute\Attribute;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class AttributeChoice extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    public $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
