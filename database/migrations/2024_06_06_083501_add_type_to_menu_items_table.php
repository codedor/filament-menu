<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        });

        // Migrate existing data to new structure
        $menuItems = DB::table('menu_items')->get();

        foreach ($menuItems as $menuItem) {
            $data = [];

            // Get link value (should be locale-independent)
            $data['link'] = json_decode($menuItem->link, true);

            // Get locale data from existing columns
            $labels = json_decode($menuItem->label, true) ?: [];
            $translatedLinks = json_decode($menuItem->translated_link, true) ?: [];
            $onlineStatuses = json_decode($menuItem->online, true) ?: [];

            // Build locale-specific data
            $locales = array_unique(array_merge(
                array_keys($labels),
                array_keys($translatedLinks),
                array_keys($onlineStatuses)
            ));

            foreach ($locales as $locale) {
                $data[$locale] = [
                    'label' => $labels[$locale] ?? null,
                    'online' => $onlineStatuses[$locale] ?? true,
                    'translated_link' => $translatedLinks[$locale] ?? null,
                ];
            }

            // Update the menu item with new data structure
            DB::table('menu_items')
                ->where('id', $menuItem->id)
                ->update([
                    'type' => 'link-picker',
                    'data' => json_encode($data),
                ]);
        }

        // Now drop the old columns
        Schema::table('menu_items', function (Blueprint $table) {
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
