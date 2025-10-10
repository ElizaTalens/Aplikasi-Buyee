<?php

// database/migrations/..._add_user_id_to_notifications_table.php

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
            // Tambahkan kolom user_id (Foreign Key) setelah kolom 'id'
            $table->foreignId('user_id')
                  ->nullable() // Izinkan NULL jika notifikasi tidak terkait user (opsional)
                  ->after('id') 
                  ->constrained('users') // Merujuk ke tabel 'users'
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Urutan penghapusan: Hapus Foreign Key Constraint, lalu hapus kolom
            $table->dropConstrainedForeignId('user_id'); // Menghapus Foreign Key dan Constraint
            // Jika dropConstrainedForeignId tidak tersedia, gunakan:
            // $table->dropForeign(['user_id']); 
            // $table->dropColumn('user_id');
        });
    }
};