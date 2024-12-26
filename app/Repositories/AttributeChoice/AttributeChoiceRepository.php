<?php

namespace App\Repositories\AttributeChoice;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttributeChoice\AttributeChoice;
use App\Models\AttributeChoice\AttributeChoiceTranslation;

class AttributeChoiceRepository implements EloquentRepositoryInterface
{
    /**
     * Find attribute choice by ID.
     *
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model
    {
        return AttributeChoice::find($id);
    }

    public function findByName(string $name): ?Model
    {
        return AttributeChoiceTranslation::where('name', $name)->first();
    }
}
