<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Hapus baris ini jika kamu memang pakai konvensi default "products"
    protected $table = 'products';

    // Gabungan field dari kedua versi (silakan sesuaikan dengan kolom database-mu)
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'stock',
        'is_active',
        'image_url',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
