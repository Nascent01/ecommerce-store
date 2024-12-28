<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\AttributeChoice\AttributeChoice;

class Attribute extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    public $timestamps = true;

    public $fillable = ['created_at, updated_at'];

    public function attributeChoices()
    {
        return $this->hasMany(AttributeChoice::class);
    }
}
