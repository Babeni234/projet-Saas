<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasCustomUuid
{
    /**
     * Boot the trait.
     */
    protected static function bootHasCustomUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
