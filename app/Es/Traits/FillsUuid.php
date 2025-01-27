<?php

namespace App\Es\Traits;

use Ramsey\Uuid\Uuid;

trait FillsUuid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Uuid::uuid4();
        });
    }
}
