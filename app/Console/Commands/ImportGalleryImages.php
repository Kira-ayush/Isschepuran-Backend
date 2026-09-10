<?php

namespace App\Console\Commands;

use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * One-off bulk import of image files from a local directory into the
 * Gallery (one GalleryItem per file, image attached via Spatie Media
 * Library).
 *
 * Idempotent: skips any file already attached to a GalleryItem's 'image'
 * collection (matched on original file name), so re-running does not create
 * duplicates.
 *
 * Captions and alt text are left blank for editing in /admin afterwards.
 *
 * Usage:
 *   php artisan gallery:import "C:\Users\Ayush\Downloads\ip-images" --category=general
 */
class ImportGalleryImages extends Command
{
    protected $signature = 'gallery:import
        {dir : Absolute path to the folder of images}
        {--category=general : Slug of the GalleryCategory to file them under}
        {--unpublished : Import as hidden drafts instead of published}
        {--ext=jpg,jpeg : Comma-separated list of file extensions to include}';

    protected $description = 'Bulk-import a folder of images as Gallery items';

    public function handle(): int
    {
        $dir = rtrim($this->argument('dir'), '\\/');

        if (! is_dir($dir)) {
            $this->error("Not a directory: {$dir}");

            return self::FAILURE;
        }

        $category = GalleryCategory::where('slug', $this->option('category'))->first();

        if (! $category) {
            $this->error("No GalleryCategory with slug '{$this->option('category')}'. Existing: "
                . GalleryCategory::pluck('slug')->implode(', '));

            return self::FAILURE;
        }

        $exts = collect(explode(',', $this->option('ext')))
            ->map(fn ($e) => strtolower(trim($e)))
            ->filter()
            ->all();

        $files = collect(scandir($dir))
            ->reject(fn ($f) => in_array($f, ['.', '..']))
            ->filter(fn ($f) => is_file("{$dir}/{$f}"))
            ->filter(fn ($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $exts))
            ->sort()
            ->values();

        if ($files->isEmpty()) {
            $this->warn("No files matching [{$this->option('ext')}] in {$dir}");

            return self::SUCCESS;
        }

        $existing = Media::query()
            ->where('model_type', GalleryItem::class)
            ->where('collection_name', 'image')
            ->pluck('file_name')
            ->map(fn ($n) => strtolower($n))
            ->flip();

        $published = ! $this->option('unpublished');
        $order = (int) GalleryItem::max('order');

        $this->info("Importing into '{$category->name}' as " . ($published ? 'PUBLISHED' : 'unpublished') . '...');

        $bar = $this->output->createProgressBar($files->count());
        $imported = 0;
        $skipped = 0;

        foreach ($files as $file) {
            if ($existing->has(strtolower($file))) {
                $skipped++;
                $bar->advance();

                continue;
            }

            $item = GalleryItem::create([
                'category_id' => $category->id,
                'caption' => null,
                'image_alt' => null,
                'order' => ++$order,
                'is_published' => $published,
                'is_featured' => false,
            ]);

            $item->addMedia("{$dir}/{$file}")->preservingOriginal()->toMediaCollection('image');
            $imported++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Imported: {$imported}, skipped (already present): {$skipped}.");

        if ($imported > 0) {
            $this->comment('Captions and alt text are blank — add them in /admin → Gallery Items.');
        }

        return self::SUCCESS;
    }
}
