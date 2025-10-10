<?php

// database/migrations/..._add_title_and_message_to_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Jika user_id sudah ditambahkan, ini akan diletakkan setelah user_id
            $table->string('title')->nullable()->after('user_id'); 
            $table->text('message')->nullable()->after('title');
            
            // Catatan: Pastikan kolom user_id, title, dan message sudah ada
            // sebelum menjalankan migration read_at
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['message', 'title']);
        });
    }
};