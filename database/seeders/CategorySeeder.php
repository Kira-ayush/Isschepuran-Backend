<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Ensures the base Initiative categories exist. Idempotent (updateOrCreate) —
 * safe to call from any other seeder that needs a category_id, regardless of
 * run order, including a fresh install where nothing exists yet. Called by
 * HomePageSeeder and InitiativesPageSeeder before they reference categories.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Environment', 'slug' => 'environment', 'color' => 'forest', 'order' => 1],
            ['name' => 'Water', 'slug' => 'water', 'color' => 'sage', 'order' => 2],
            ['name' => 'Community', 'slug' => 'community', 'color' => 'mustard', 'order' => 3],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], $c);
        }
    }
}
