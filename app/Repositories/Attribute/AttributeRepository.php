<?php

namespace App\Repositories\Attribute;

use App\Models\Attribute\Attribute;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AttributeRepository implements EloquentRepositoryInterface
{
    /**
     * Find attribute by ID.
     *
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model
    {
        return Attribute::find($id);
    }
}
