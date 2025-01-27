<?php

namespace App\Es\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    const DEFAULT_CACHE_TTL = 3600;
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
