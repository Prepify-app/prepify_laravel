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
        Schema::table('users', function (Blueprint $table) {
            $table->string('place_of_work')->nullable()->after('image_path');
            $table->enum('level', ['junior', 'middle', 'senior'])->nullable()->after('place_of_work');
            $table->string('country')->nullable()->after('level');
            $table->string('stack')->nullable()->after('country');
            $table->integer('age')->nullable()->after('stack');
            $table->text('about')->nullable()->after('age');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('place_of_work');
            $table->dropColumn('level');
            $table->dropColumn('country');
            $table->dropColumn('stack');
            $table->dropColumn('age');
            $table->dropColumn('about');
        });
    }
};
