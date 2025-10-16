<?php

// database/migrations/XXXX_XX_XX_XXXXXX_add_profile_fields_to_users_table.php

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
        Schema::table('users', function (Blueprint $table) {
            // Kolom yang menyebabkan error: birth_date
            $table->date('birth_date')->nullable()->after('email');
            
            // Kolom gender
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date'); 
            
            // Kolom phone
            $table->string('phone', 20)->nullable()->after('gender');
            
            // Kolom untuk path foto profil (penting untuk upload)
            $table->string('profile_photo_path')->nullable()->after('phone'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'gender', 'phone', 'profile_photo_path']);
        });
    }
};