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
        Schema::table('categories', function (Blueprint $table) {
            // Menambahkan kolom 'parent_id' setelah kolom 'id'
            // Kolom ini bisa null dan terhubung ke tabel 'categories' itu sendiri
            $table->foreignId('parent_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['parent_id']);
            // Hapus kolom 'parent_id'
            $table->dropColumn('parent_id');
        });
    }
};