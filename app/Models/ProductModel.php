<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    public $table = 'product';
    protected $fillable = [
        'product_name', 'product_price', 'product_description', 'file_path' 
    ];
}
