<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'category_id', 'price', 'stock', 'is_active', 'image', 'description', 'image_url', 'slug',];

    // Hapus baris ini jika kamu memang pakai konvensi default "products"
    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
