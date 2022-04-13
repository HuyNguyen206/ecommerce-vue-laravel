<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected static $unguarded = true;
    use HasFactory;

    public function order()
    {
       return $this->belongsTo(Order::class);
    }
}
