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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->after('working_title', function (Blueprint $table) {
                $table->string('type')->nullable();
                $table->json('data')->nullable();
            });

            $table->dropColumn(['link', 'label', 'translated_link', 'online']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('data');

            $table->json('link')->nullable();
            $table->json('label')->nullable();
            $table->json('translated_link')->nullable();
            $table->json('online')->nullable();
        });
    }
};
