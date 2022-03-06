<?php

namespace App\Models;

use App\Models\Traits\HasChildren;
use App\Models\Traits\isOrderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory, HasChildren, isOrderable;
    protected $guarded = [];

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
