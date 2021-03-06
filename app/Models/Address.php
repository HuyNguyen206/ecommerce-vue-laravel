<?php

namespace App\Models;

use App\Models\Traits\UpdateDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected static $unguarded = true;
    use HasFactory, UpdateDefault;
    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


}
