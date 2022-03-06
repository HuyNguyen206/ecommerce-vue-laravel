<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait isOrderable
{
    public function scopeOrdered(Builder $builder, string $direction = 'asc')
    {
        $builder->orderBy('order', $direction);
    }
}
