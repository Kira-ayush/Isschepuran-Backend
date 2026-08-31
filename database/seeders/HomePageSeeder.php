<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CtaBand;
use App\Models\HeroCarouselSetting;
use App\Models\HeroSlide;
use App\Models\ImpactStat;
use App\Models\Initiative;
use App\Models\SectionHeading;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Seeds the exact same starting content the Next.js frontend's mock data
 * uses (frontend/src/lib/content/home.mock.ts), so the admin panel and the
 * live site match on day one instead of starting from an empty CMS.
 * Run with: php artisan db:seed --class=HomePageSeeder
 *
 * Note: Home's SDG Alignment section (renders on both Home and Impact) is
 * seeded by ImpactPageSeeder, not here — run that seeder too, or Home's
 * SDG section will be empty even though this seeder ran successfully.
 */
class HomePageSeeder extends Seeder
{
    // Local-machine-only asset pack path (see GalleryPageSeeder's docblock
    // for the established convention this follows — is_file()-guarded, so
    // a teammate without this folder still gets the text fields seeded).
    private const HERO_ASSET_DIR = 'C:\\Users\\Ayush\\Downloads\\assets\\09_Initiatives_Environment_Conservation\\';

    public function run(): void
    {
        $this->call(CategorySeeder::class);

        HeroCarouselSetting::current()->update([
            'indicator_style' => 'dot',
            'gradient_overlay' => true,
        ]);

        // Slide 1 is the original, real Hero content, unchanged — its
        // background photo isn't attached here; it was carried over from
        // the old singleton `heroes` row by the
        // migrate_hero_to_hero_slides migration's media-repoint step, so a
        // fresh environment that never had that row simply seeds this
        // slide without an image until an admin uploads one.
        //
        // Slides 3, 4, and 6 below are deliberately seeded WITHOUT an
        // image_file: their only available source photos
        // (initiatives_urban_miyawaki.jpg, initiatives_agroforestry.jpg,
        // initiatives_water_restoration.jpg) turned out to have website
        // mockup chrome (logos/nav bars/captions) baked directly into the
        // image — the same "screenshot with site chrome" problem this
        // project has rejected before (see the note on About's hero photo
        // and 2 of Home's 3 pillar photos) — so they're left imageless
        // (same graceful fallback treatment as an unset Hero image) rather
        // than shipped with a visual clash against the carousel's own
        // logo/text overlay. Only genuinely clean photos
        // (tree_plantation, environment_intro) are attached below.
        $heroSlides = [
            [
                'order' => 1,
                'eyebrow' => 'Reforestation · Water · Education',
                'headline' => "Restoring Earth,\nEmpowering Communities",
                'subheading' => 'A philanthropic endeavor dedicated to reforestation, water conservation, and quality education for underserved children across eastern India.',
                'primary_cta_label' => 'Donate Now', 'primary_cta_href' => '/get-involved#donate',
                'secondary_cta_label' => 'Our Mission', 'secondary_cta_href' => '/about',
                'background_alt' => 'Volunteers planting saplings in a restored coastal forest at sunrise.',
                'image_file' => null,
            ],
            [
                'order' => 2,
                'image_file' => 'initiatives_tree_plantation.jpg',
                'eyebrow' => 'Tree Plantation · Community Action',
                'headline' => "One Sapling,\nOne Thousand Tomorrows",
                'subheading' => 'From degraded hillsides to barren roadsides, our plantation drives put native saplings in the ground alongside the volunteers, farmers, and schoolchildren who will watch them grow.',
                'primary_cta_label' => 'Donate Now', 'primary_cta_href' => '/get-involved#donate',
                'secondary_cta_label' => 'See Our Initiatives', 'secondary_cta_href' => '/initiatives',
                'background_alt' => 'Volunteers and children lined up to plant tree saplings during a community plantation drive.',
            ],
            [
                'order' => 3,
                'image_file' => null,
                'eyebrow' => 'Urban Miyawaki Forests',
                'headline' => "Dense Forests,\nGrown in the Heart of the City",
                'subheading' => 'Using the Miyawaki method, we transform small urban plots into thick, fast-growing native forests — cooling neighborhoods, cleaning the air, and bringing biodiversity back to the concrete landscape.',
                'primary_cta_label' => 'Donate Now', 'primary_cta_href' => '/get-involved#donate',
                'secondary_cta_label' => 'Explore Impact', 'secondary_cta_href' => '/impact',
                'background_alt' => 'A young, densely planted Miyawaki-method urban forest with saplings growing close together.',
            ],
            [
                'order' => 4,
                'image_file' => null,
                'eyebrow' => 'Agroforestry · Farmer Livelihoods',
                'headline' => "Trees That\nFeed Families, Too",
                'subheading' => 'We work alongside smallholder farmers to integrate fruit and timber trees into their fields — restoring soil health while opening a second, sustainable source of income for rural households.',
                'primary_cta_label' => 'Donate Now', 'primary_cta_href' => '/get-involved#donate',
                'secondary_cta_label' => 'Our Mission', 'secondary_cta_href' => '/about',
                'background_alt' => 'A farmer tending to young fruit trees planted alongside crop rows in an agroforestry field.',
            ],
            [
                'order' => 5,
                'image_file' => null,
                'eyebrow' => 'Water Conservation',
                'headline' => "Clearing Ponds,\nRestoring Life",
                'subheading' => 'Choked, silted village ponds are desilted, deepened, and reopened — bringing back clean water for drinking, irrigation, and daily life to communities that once walked miles to fetch it.',
                'primary_cta_label' => 'Donate Now', 'primary_cta_href' => '/get-involved#donate',
                'secondary_cta_label' => 'See Our Initiatives', 'secondary_cta_href' => '/initiatives',
                'background_alt' => 'A restored village pond with clear water, cleared of silt and water hyacinth.',
            ],
            [
                'order' => 6,
                'image_file' => 'initiatives_environment_intro.jpg',
                'eyebrow' => 'Our Mission',
                'headline' => "A Greener Planet\nStarts With Us",
                'subheading' => "Reforestation, clean water, and quality education aren't separate goals — they're one connected mission to heal the land and lift the communities who depend on it. Join us.",
                'primary_cta_label' => 'Donate Now', 'primary_cta_href' => '/get-involved#donate',
                'secondary_cta_label' => 'Get Involved', 'secondary_cta_href' => '/get-involved',
                'background_alt' => "A wide view of a restored green landscape representing Ichhe Puran's environmental mission.",
            ],
        ];

        foreach ($heroSlides as $s) {
            $imageFile = $s['image_file'] ?? null;
            unset($s['image_file']);

            $slide = HeroSlide::updateOrCreate(['order' => $s['order']], $s);

            if (! $imageFile || $slide->getMedia('background')->isNotEmpty()) {
                continue;
            }

            $path = self::HERO_ASSET_DIR . $imageFile;

            if (! is_file($path)) {
                Log::warning("HomePageSeeder: hero slide asset not found, skipping image attachment: {$path}");
                continue;
            }

            $slide->addMedia($path)->preservingOriginal()->toMediaCollection('background');
        }

        CtaBand::current()->update([
            'heading' => 'Join the Movement for a Greener Planet',
            'subheading' => 'Your contribution directly funds the planting of saplings, the education of children, and the restoration of our precious ecosystems. Every wish matters.',
            'primary_cta_label' => 'Donate Now',
            'primary_cta_href' => '/get-involved#donate',
            'secondary_cta_label' => 'Volunteer',
            'secondary_cta_href' => '/get-involved#volunteer',
        ]);

        SectionHeading::forKey('pillars', 'What we do', 'Our Core Pillars')
            ->update(['eyebrow' => 'What we do', 'heading' => 'Our Core Pillars']);

        SectionHeading::forKey('testimonials', 'Voices of impact', 'Real stories from the communities we serve')
            ->update(['eyebrow' => 'Voices of impact', 'heading' => 'Real stories from the communities we serve']);

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

        $categoryIds = Category::pluck('id', 'slug');

        $pillars = [
            [
                'title' => 'Tree Plantation', 'category_id' => $categoryIds['environment'], 'slug' => 'tree-plantation',
                'summary' => 'Restoring biodiversity by planting native species across degraded forest lands and urban centers.',
                'image_alt' => 'Volunteers planting young saplings in a degraded forest clearing.',
                'icon' => 'TreePine', 'order' => 1, 'featured_on_home' => true,
            ],
            [
                'title' => 'Water Restoration', 'category_id' => $categoryIds['water'], 'slug' => 'water-restoration',
                'summary' => 'Desilting ponds, harvesting rainwater, and ensuring sustainable clean water access for remote villages.',
                'image_alt' => 'A restored village pond used for clean water access.',
                'icon' => 'Droplets', 'order' => 2, 'featured_on_home' => true,
            ],
            [
                'title' => 'Holistic Education', 'category_id' => $categoryIds['community'], 'slug' => 'holistic-education',
                'summary' => 'Providing modern curriculum, digital literacy, and life skills training to bridge the urban-rural divide.',
                'image_alt' => 'Children in a rural classroom during a digital literacy session.',
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
                'photo_alt' => 'Portrait of Saraswati Devi, Community Leader in West Bengal.',
            ],
            [
                'name' => 'Rahul Mondal', 'role' => 'Scholarship Recipient', 'order' => 2,
                'quote' => 'I want to be an environmental engineer. The scholarship and the digital classes helped me dream beyond my small village. Now I know I can make a difference.',
                'photo_alt' => 'Portrait of Rahul Mondal, scholarship recipient.',
            ],
        ];
        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}
