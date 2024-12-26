<?php

namespace App\Services\Attribute;

use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeTranslation;
use App\Services\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;

class AttributeService implements BaseServiceInterface
{

    public function create(array $attributes): Model
    {
       return Attribute::create($attributes);
    }

    public function createTranslation(array $attributes): Model
    {
       return AttributeTranslation::create($attributes);
    }

    public function insert(array $attributes): bool
    {
       return Attribute::insert($attributes);
    }

    public function insertTranslation(array $attributes): bool
    {
        return AttributeTranslation::insert($attributes);
    }
}
