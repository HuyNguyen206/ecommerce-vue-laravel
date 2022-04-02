<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait UpdateDefault
{
    protected static function booted()
    {
        static::creating(function (Model $model){
            if ((bool) $model->is_default) {
                $model->newQuery()->where('user_id', $model->user_id)
                    ->where('is_default', 1)->update([
                        'is_default' => 0
                    ]);
            }
        });
    }
}
