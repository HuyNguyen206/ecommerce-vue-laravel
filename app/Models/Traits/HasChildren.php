<?php
namespace App\Models\Traits;
use Illuminate\Database\Eloquent\Builder;

trait HasChildren
{
    public function scopeParent(Builder $builder)
    {
        $builder->whereNull('parent_id');
    }
}
