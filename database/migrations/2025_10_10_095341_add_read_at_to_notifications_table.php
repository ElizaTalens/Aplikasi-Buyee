<?php

// database/migrations/..._add_read_at_to_notifications_table.php

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
        Schema::table('notifications', function (Blueprint $table) {
            // Tambahkan kolom 'read_at' sebagai timestamp yang dapat bernilai null
            $table->timestamp('read_at')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Hapus kolom 'read_at'
            $table->dropColumn('read_at');
        });
    }
};