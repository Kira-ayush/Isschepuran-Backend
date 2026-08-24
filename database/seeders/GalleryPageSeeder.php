<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use App\Models\GalleryHero;
use App\Models\GalleryItem;
use App\Models\SectionHeading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Seeds the Gallery page. Unlike every other page, the original live site
 * had NO gallery content at all ("confirmed empty — nav and footer only"
 * per docs/raw-site-content.md) — this is a from-scratch build, not a
 * migration.
 *
 * The 6 real images seeded here come from the client asset pack at
 * ~/Downloads/assets/01_Home_Before_After_Gallery/ — genuine field
 * photography, already composited as single before/after collage images
 * (not raw pairs needing an interactive slider). Per an explicit user
 * decision, these are scoped to the Gallery page ONLY, not also added to
 * Home's unbuilt before/after slider or retrofitted into the Impact page
 * (despite an identical duplicate set sitting in a
 * "12_Impact_Visible_Transformation" folder in the same pack).
 *
 * Captions below are drafted from direct visual inspection of each image
 * (not from any source doc — none exists) — confirm exact
 * project/location/date specifics with the client before treating them as
 * final. "General" has zero rows — same "structurally ready, not
 * fabricated" pattern used for CsrPartner on the Impact page.
 *
 * `category` is a `GalleryCategory` master (FK), same admin-managed
 * pattern as Initiative's `Category` — see GalleryCategoryResource. The 3
 * starter categories are seeded here (this used to be a fixed 3-value
 * string column; converted per explicit user request to mirror
 * Initiative's category master exactly).
 *
 * `is_featured` is an explicit admin choice (only one row may be true at
 * a time — enforced in GalleryItem::booted(), not here), replacing an
 * earlier "whichever item has the lowest order" implicit convention. The
 * first before/after image is seeded as the default featured item so the
 * banner isn't simply absent on a fresh seed.
 *
 * This is the FIRST seeder in this project to attach a real uploaded file
 * via Spatie Media Library's addMedia() rather than a manual admin-panel
 * upload — no earlier precedent exists. Two things make this safe to
 * re-run:
 * - Guarded with is_file($path): if a teammate doesn't have this local
 *   asset pack, the row's text fields still seed, the image is skipped
 *   with a warning logged, and db:seed does NOT crash.
 * - Idempotent: skips addMedia() entirely if the record already has media
 *   in the 'image' collection, so re-running this seeder doesn't create
 *   duplicate attachments.
 * ->preservingOriginal() is used because the source file lives outside
 * the repo/storage (in the user's Downloads folder) and must not be
 * deleted by Spatie's default move-then-delete behavior.
 *
 * Run with: php artisan db:seed --class=GalleryPageSeeder
 */
class GalleryPageSeeder extends Seeder
{
    private const ASSET_DIR = 'C:\\Users\\Ayush\\Downloads\\assets\\01_Home_Before_After_Gallery\\';

    public function run(): void
    {
        GalleryHero::current()->update([
            'headline' => 'Moments From the Field',
            'subheading' => 'A visual record of restoration projects, community events, and everyday work across our operating villages — before, during, and after.',
        ]);

        $categories = [
            ['name' => 'Before & After', 'slug' => 'before_after', 'color' => 'forest', 'order' => 1],
            ['name' => 'Events', 'slug' => 'event', 'color' => 'mustard', 'order' => 2],
            ['name' => 'General', 'slug' => 'general', 'color' => 'sage', 'order' => 3],
        ];
        foreach ($categories as $c) {
            GalleryCategory::updateOrCreate(['slug' => $c['slug']], $c);
        }
        $categoryIds = GalleryCategory::pluck('id', 'slug');

        $items = [
            [
                'file' => 'before_after_1.webp',
                'category_id' => $categoryIds['before_after'],
                'caption' => 'A reforestation plot before planting — bare, dry earth — and the same site months later, with staked saplings knee-high.',
                'order' => 1,
                'is_featured' => true,
            ],
            [
                'file' => 'before_after_2.webp',
                'category_id' => $categoryIds['before_after'],
                'caption' => 'Freshly tilled planting rows growing into a dense line of young trees at the same site.',
                'order' => 2,
            ],
            [
                'file' => 'before_after_3.webp',
                'category_id' => $categoryIds['event'],
                'caption' => 'Amrit Kund pond restoration: a pond fully choked with water hyacinth, cleared and reopened with new steps and marigold-decorated railings. Implemented by Ichhe Puran under the CSR initiative of Tega Industries Limited.',
                'order' => 3,
            ],
            [
                'file' => 'before_after_4.webp',
                'category_id' => $categoryIds['event'],
                'caption' => 'The Amrit Kund handover event — the hyacinth-covered pond cleared and reopened, with the community gathered for the inauguration.',
                'order' => 4,
            ],
            [
                'file' => 'before_after_5.webp',
                'category_id' => $categoryIds['before_after'],
                'caption' => 'A mango sapling planted near a field grows into a mature, fruiting tree — the same farmer standing beside it years later.',
                'order' => 5,
            ],
            [
                'file' => 'before_after_6.webp',
                'category_id' => $categoryIds['before_after'],
                'caption' => 'Women desilting a garbage-choked village pond, and the same pond afterward — cleared, with the removed debris bagged at the bank.',
                'order' => 6,
            ],
        ];

        foreach ($items as $i) {
            $item = GalleryItem::updateOrCreate(
                ['order' => $i['order']],
                [
                    'category_id' => $i['category_id'],
                    'caption' => $i['caption'],
                    'is_featured' => $i['is_featured'] ?? false,
                ]
            );

            if ($item->getMedia('image')->isNotEmpty()) {
                continue;
            }

            $path = self::ASSET_DIR . $i['file'];

            if (! is_file($path)) {
                Log::warning("GalleryPageSeeder: asset file not found, skipping image attachment: {$path}");
                continue;
            }

            $item->addMedia($path)->preservingOriginal()->toMediaCollection('image');
        }

        SectionHeading::forKey('gallery-items', 'Moments from the field', 'Our Gallery')
            ->update(['eyebrow' => 'Moments from the field', 'heading' => 'Our Gallery']);
    }
}
