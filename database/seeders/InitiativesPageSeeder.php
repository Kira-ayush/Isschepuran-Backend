<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Initiative;
use App\Models\InitiativesHero;
use Illuminate\Database\Seeder;

/**
 * Expands the Initiative content type from Home's 3 featured pillars to
 * the full Initiatives page listing, using real content migrated from
 * docs/raw-site-content.md. Run with:
 * php artisan db:seed --class=InitiativesPageSeeder
 */
class InitiativesPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $categoryIds = Category::pluck('id', 'slug');

        InitiativesHero::current()->update([
            'headline' => 'Nurturing Nature, Empowering Lives',
            'subheading' => 'At Ichhe Puran, we translate philanthropic vision into tangible environmental and social impact through carefully structured initiatives.',
        ]);

        // Fill in the fuller Initiatives-page body text for the 2 initiatives
        // that already exist as Home's featured pillars.
        Initiative::where('slug', 'tree-plantation')->update([
            'body' => 'Restoring ecological balance by planting native species across degraded landscapes. Our systematic approach ensures a 95% survival rate through community-led maintenance.',
        ]);

        Initiative::where('slug', 'water-restoration')->update([
            'body' => 'Water is the lifeblood of our planet. Through Project Amrit Kund, we focus on the scientific restoration of traditional water bodies, desilting, and groundwater recharge. So far: 50+ Lakes Restored, 10M+ Liters Recharged.',
        ]);

        $initiatives = [
            [
                'title' => 'Urban Miyawaki', 'category_id' => $categoryIds['environment'], 'slug' => 'urban-miyawaki',
                'summary' => 'Creating dense, native urban forests that grow 10x faster and are 30x more dense than traditional plantations.',
                'body' => 'Creating dense, native urban forests that grow 10x faster and are 30x more dense than traditional plantations. Perfect for revitalizing city micro-climates.',
                'image_alt' => 'A dense Miyawaki-method urban forest plot in a city.',
                'icon' => 'TreePine', 'order' => 4, 'featured_on_home' => false,
            ],
            [
                'title' => 'Agroforestry', 'category_id' => $categoryIds['environment'], 'slug' => 'agroforestry',
                'summary' => 'Integrating trees into farming systems to boost biodiversity and improve soil health.',
                'body' => 'Integrating trees into farming systems to boost biodiversity, improve soil health, and provide sustainable alternative incomes for rural farmers. This initiative creates a resilient ecosystem where agriculture and nature thrive together.',
                'image_alt' => 'Farmland with rows of trees interplanted among crops.',
                'icon' => 'Sprout', 'order' => 5, 'featured_on_home' => false,
            ],
            [
                'title' => 'Child Education', 'category_id' => $categoryIds['community'], 'slug' => 'child-education',
                'summary' => 'Equipping the next generation with knowledge and ecological consciousness.',
                'body' => null,
                'icon' => 'BookOpen', 'order' => 6, 'featured_on_home' => false,
            ],
            [
                'title' => 'Health Camps', 'category_id' => $categoryIds['community'], 'slug' => 'health-camps',
                'summary' => 'Bringing specialized medical care and preventive health awareness to rural doorsteps.',
                'body' => null,
                'icon' => 'HeartPulse', 'order' => 7, 'featured_on_home' => false,
            ],
            [
                'title' => 'Disaster Relief', 'category_id' => $categoryIds['community'], 'slug' => 'disaster-relief',
                'summary' => 'Rapid response and long-term rehabilitation support for communities facing crises.',
                'body' => null,
                'icon' => 'LifeBuoy', 'order' => 8, 'featured_on_home' => false,
            ],
            [
                'title' => 'Farmer Growth', 'category_id' => $categoryIds['community'], 'slug' => 'farmer-growth',
                'summary' => 'Empowering farmers with modern sustainable techniques and market linkages.',
                'body' => null,
                'icon' => 'Wheat', 'order' => 9, 'featured_on_home' => false,
            ],
        ];
        foreach ($initiatives as $i) {
            Initiative::updateOrCreate(['slug' => $i['slug']], $i);
        }
    }
}
