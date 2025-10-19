<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel users (user yang memiliki wishlist)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key ke tabel products (produk yang dimasukkan ke wishlist)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // Menjamin setiap pengguna hanya dapat menambahkan produk yang sama sekali
            $table->unique(['user_id', 'product_id']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};