<?php

namespace App\Models;

use App\Models\Traits\UpdateDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{

    use HasFactory, UpdateDefault;
    protected static $unguarded = true;
    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
