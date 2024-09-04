<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('level');

            $table->foreignId('user_level_id')
                ->nullable()
                ->after('place_of_work')
                ->constrained('user_levels')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('level', ['junior', 'middle', 'senior'])->nullable()->after('place_of_work');
            $table->dropConstrainedForeignId('user_level_id');
        });
    }
};
