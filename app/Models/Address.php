<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected static $unguarded = true;
    use HasFactory;
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

    protected static function booted()
    {
     static::creating(function (Address $address){
         if ((bool) $address->is_default) {
             $address->user->addresses()->where('is_default', 1)->update([
                 'is_default' => 0
             ]);
         }
     });
    }

}
