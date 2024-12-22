<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

interface BaseServiceInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param array $attributes
     * @return Model
     */
    public function createTranslation(array $attributes): Model;

    /**
     * Insert multiple records at once.
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes): bool;

    /**
     * Insert multiple translation records at once.
     *
     * @param array $attributes
     * @return bool
     */
    public function insertTranslation(array $attributes): bool;
}
