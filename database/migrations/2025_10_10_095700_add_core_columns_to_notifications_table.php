<?php

// database/migrations/..._add_core_columns_to_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel notifications sudah ada sebelum menjalankan ini. 
        // Jika belum ada, Anda harus buat migration CREATE TABLE dulu.
        Schema::table('notifications', function (Blueprint $table) {
            
            // Tambahkan kolom yang dibutuhkan: user_id, title, message
            
            // user_id (jika belum ada)
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }

            // title
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->nullable()->after('user_id'); 
            }
            
            // message (Kolom yang dicari oleh migration read_at)
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Hapus kolom
            if (Schema::hasColumn('notifications', 'user_id')) {
                $table->dropConstrainedForeignId('user_id'); 
            }
            if (Schema::hasColumn('notifications', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('notifications', 'message')) {
                $table->dropColumn('message');
            }
        });
    }
};