<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;

class ProcessNewImages extends Command
{
    protected $signature = 'nora:process-new-images';
    protected $description = 'Process new product images from attachment folder';

    public function handle(): int
    {
        $attachPath = base_path('attachement');
        $storagePath = public_path('images/nora/products');

        if (!is_dir($attachPath)) {
            $this->error("Attachment folder not found: {$attachPath}");
            return 1;
        }

        $files = glob($attachPath . '/*.jpeg');
        $files = array_merge($files, glob($attachPath . '/*.jpg'));
        $files = array_merge($files, glob($attachPath . '/*.png'));

        $this->info("Found " . count($files) . " images to process.");

        // Group images by timestamp prefix (same product = same time window)
        $groups = [];
        foreach ($files as $file) {
            $basename = basename($file);
            // Extract timestamp: "WhatsApp Image 2026-09-02 at 1.47.04 PM (1).jpeg"
            // Group by time prefix: "2026-09-02 at 1.47"
            if (preg_match('/(\d{4}-\d{2}-\d{2}) at (\d+\.\d+\.\d+)/', $basename, $m)) {
                // Group by time prefix (minute level)
                $timeParts = explode('.', $m[2]);
                $groupKey = $m[1] . ' at ' . $timeParts[0] . '.' . $timeParts[1];
            } elseif (preg_match('/^(\d+)\.jpeg$/', $basename, $m)) {
                $groupKey = 'standalone-' . $m[1];
            } else {
                $groupKey = 'other';
            }

            $groups[$groupKey][] = $file;
        }

        $this->info("Grouped into " . count($groups) . " product groups:");
        foreach ($groups as $key => $group) {
            $this->line("  - {$key}: " . count($group) . " images");
        }

        // Category mapping
        $categories = Category::all()->pluck('id', 'slug')->toArray();
        $defaultCategory = $categories['mutafarriqat'] ?? $categories[array_key_first($categories)] ?? null;

        $counter = 17; // Start after existing products
        foreach ($groups as $groupKey => $groupFiles) {
            $counter++;
            $slug = 'new-product-' . $counter;
            $name = 'قطعة رقم ' . $counter;

            // Determine category based on group
            $catId = $defaultCategory;

            $this->line("Creating product #{$counter}: {$name}");

            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => 'قطعة جديدة أضيفت من العميل. يُرجى تحديث التفاصيل من لوحة التحكم.',
                'short_description' => 'قطعة جديدة - يُرجى تحديث الوصف',
                'price' => 0,
                'stock_quantity' => 1,
                'in_stock' => true,
                'is_active' => false, // Inactive until details added
                'is_one_of_a_kind' => true,
                'origin_type' => 'other',
                'return_policy' => 'لا يُسمح بالإرجاع — البيع نهائي',
            ]);

            if ($catId) {
                $product->categories()->attach($catId);
            }

            // Copy and add images
            foreach ($groupFiles as $idx => $filePath) {
                $newFilename = "new-{$counter}-" . ($idx + 1) . '.jpeg';
                $destPath = $storagePath . '/' . $newFilename;
                copy($filePath, $destPath);

                $relativePath = 'images/nora/products/' . $newFilename;

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $relativePath,
                    'alt_text' => $name . ' - صورة ' . ($idx + 1),
                    'sort_order' => $idx,
                    'is_primary' => $idx === 0,
                ]);
            }

            $this->info("  ✅ Created with " . count($groupFiles) . " images");
        }

        $this->newLine();
        $this->info("✅ Done! Created " . ($counter - 17) . " new products.");
        $this->info("Products are INACTIVE by default. Update them from admin panel at /nora-backoffice-2024");

        return 0;
    }
}
