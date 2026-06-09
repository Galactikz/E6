<?php

namespace Database\Seeders;

use App\Models\ProviderCategory;
use Illuminate\Database\Seeder;

class ProviderCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Salle de réception', 'slug' => 'salle-de-reception', 'icon' => 'building'],
            ['name' => 'DJ', 'slug' => 'dj', 'icon' => 'music'],
            ['name' => 'Photographe', 'slug' => 'photographe', 'icon' => 'camera'],
            ['name' => 'Vidéaste', 'slug' => 'vidéaste', 'icon' => 'video'],
            ['name' => 'Traiteur', 'slug' => 'traiteur', 'icon' => 'utensils'],
            ['name' => 'Fleuriste', 'slug' => 'fleuriste', 'icon' => 'flower'],
            ['name' => 'Wedding Planner', 'slug' => 'wedding-planner', 'icon' => 'clipboard'],
            ['name' => 'Décorateur', 'slug' => 'decorateur', 'icon' => 'sparkles'],
            ['name' => 'Location voiture', 'slug' => 'location-voiture', 'icon' => 'car'],
        ];

        foreach ($categories as $i => $category) {
            ProviderCategory::updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['sort_order' => $i + 1])
            );
        }
    }
}
