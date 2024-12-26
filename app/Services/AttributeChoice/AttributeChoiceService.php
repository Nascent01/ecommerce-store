<?php

namespace App\Services\AttributeChoice;

use App\Models\AttributeChoice\AttributeChoice;
use App\Models\AttributeChoice\AttributeChoiceTranslation;
use App\Services\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;

class AttributeChoiceService implements BaseServiceInterface
{

    public function create(array $attributes): Model
    {
        return AttributeChoice::create($attributes);
    }

    public function createTranslation(array $attributes): Model
    {
        return AttributeChoiceTranslation::create($attributes);
    }

    public function insert(array $attributes): bool
    {
        return AttributeChoice::insert($attributes);
    }

    public function insertTranslation(array $attributes): bool
    {
        return AttributeChoiceTranslation::insert($attributes);
    }
}
