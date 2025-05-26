<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'category',
        'manufacturer',
        'quantity',
        'price',
        'expiry_date',
    ];
}
