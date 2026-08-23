<?php

namespace Database\Seeders;

use App\Models\ImpactStat;
use App\Models\Initiative;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

/**
 * Seeds the exact same starting content the Next.js frontend's mock data
 * uses (frontend/src/lib/content/home.mock.ts), so the admin panel and the
 * live site match on day one instead of starting from an empty CMS.
 * Run with: php artisan db:seed --class=HomePageSeeder
 */
class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::current()->update([
            'org_name' => 'Ichhe Puran',
            'tagline' => 'Nurturing nature, restoring ecosystems, and empowering communities through transparent philanthropy.',
            'phone' => '+91 98300 12345',
            'email' => 'info@ichhepuran.org',
            'address' => '12/A Green Avenue, Salt Lake City, Sector 5, Kolkata, West Bengal - 700091',
            'donate_href' => '/get-involved#donate',
            'social_links' => [
                ['label' => 'Facebook', 'href' => '#'],
                ['label' => 'Instagram', 'href' => '#'],
                ['label' => 'YouTube', 'href' => '#'],
            ],
        ]);

        $stats = [
            ['label' => 'Trees Planted', 'value' => 14000, 'suffix' => '+', 'icon' => 'TreePine', 'order' => 1],
            ['label' => 'Saplings Distributed', 'value' => 5000, 'suffix' => '+', 'icon' => 'Sprout', 'order' => 2],
            ['label' => 'Metric Ton CO2 / year', 'value' => 420, 'suffix' => '', 'icon' => 'Cloud', 'order' => 3],
            ['label' => 'Million Kg Oxygen / yr', 'value' => 3, 'suffix' => '', 'icon' => 'Wind', 'order' => 4],
            ['label' => 'Million Litres Water Restored', 'value' => 10, 'suffix' => '', 'icon' => 'Droplets', 'order' => 5],
            ['label' => 'Total Beneficiaries Impacted', 'value' => 12000, 'suffix' => '+', 'icon' => 'Users', 'order' => 6],
        ];
        foreach ($stats as $s) {
            ImpactStat::updateOrCreate(['label' => $s['label']], $s);
        }

        $pillars = [
            [
                'title' => 'Tree Plantation', 'category' => 'environment', 'slug' => 'tree-plantation',
                'summary' => 'Restoring biodiversity by planting native species across degraded forest lands and urban centers.',
                'icon' => 'TreePine', 'order' => 1, 'featured_on_home' => true,
            ],
            [
                'title' => 'Water Restoration', 'category' => 'water', 'slug' => 'water-restoration',
                'summary' => 'Desilting ponds, harvesting rainwater, and ensuring sustainable clean water access for remote villages.',
                'icon' => 'Droplets', 'order' => 2, 'featured_on_home' => true,
            ],
            [
                'title' => 'Holistic Education', 'category' => 'community', 'slug' => 'holistic-education',
                'summary' => 'Providing modern curriculum, digital literacy, and life skills training to bridge the urban-rural divide.',
                'icon' => 'BookOpen', 'order' => 3, 'featured_on_home' => true,
            ],
        ];
        foreach ($pillars as $p) {
            Initiative::updateOrCreate(['slug' => $p['slug']], $p);
        }

        $testimonials = [
            [
                'name' => 'Saraswati Devi', 'role' => 'Community Leader, West Bengal', 'order' => 1,
                'quote' => 'The water restoration project changed everything for our village. We no longer walk 5 miles for water, and our children spend that time in the new school Ichhe Puran built.',
            ],
            [
                'name' => 'Rahul Mondal', 'role' => 'Scholarship Recipient', 'order' => 2,
                'quote' => 'I want to be an environmental engineer. The scholarship and the digital classes helped me dream beyond my small village. Now I know I can make a difference.',
            ],
        ];
        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}
