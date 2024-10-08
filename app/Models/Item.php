<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = "items";
    protected $fillable = [
        'item_code',
        'item_name',
        'item_description',
        'item_quantity',
        'unit_price',
        'supplier_code',
        'image',

    ];
}
