<?php
/**
 * Import Products from Scraped HTML Pages
 * Reads product/[slug]/index.html files and populates the database.
 * Run once: http://localhost/elitebbs/import_from_html.php
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_PATH . '/db.php';

set_time_limit(300);

echo "<h1>Importing Products from Scraped HTML...</h1>";
echo "<pre>";

// Clear existing products
db_query("DELETE FROM products");
db_query("ALTER TABLE products AUTO_INCREMENT = 1");
echo "Cleared existing products.\n\n";

$productDir = __DIR__ . '/product';
$dirs = glob($productDir . '/*/index.html');

$imported = 0;
$skipped  = 0;

foreach ($dirs as $htmlFile) {
    if (filesize($htmlFile) < 1000) {
        $skipped++;
        continue; // empty stub
    }

    $slug = basename(dirname($htmlFile));
    $html = file_get_contents($htmlFile);

    // --- Product Name ---
    $name = '';
    if (preg_match('/entry-title[^>]*>\s*([^<]+)\s*<\/h1>/i', $html, $m)) {
        $name = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
    if (!$name) {
        // Fallback: convert slug to title
        $name = ucwords(str_replace(['-', '_'], ' ', $slug));
    }

    // --- Price ---
    // Prices use &#36; entity: <bdi><span ...>&#36;</span>219.00</bdi>
    // Extract numeric value after the currency symbol span closing tag
    $price = 0.00;
    $sale_price = null;

    // Grab the product's own price-wrapper block (first occurrence in the summary, not related products)
    if (preg_match('/class="price-wrapper"[^>]*>.*?product-short-description/si', $html, $priceSection)) {
        $block = $priceSection[0];
        // All prices: after </span> inside <bdi>
        preg_match_all('/<bdi[^>]*>.*?<\/span>([\d,]+\.?\d*)<\/bdi>/si', $block, $allprices);
        if (!empty($allprices[1])) {
            $prices = array_map(fn($p) => (float) str_replace(',', '', $p), $allprices[1]);
            $prices = array_filter($prices, fn($p) => $p > 0);
            if (!empty($prices)) {
                // Check del/ins pattern (sale)
                if (preg_match('/<del[^>]*>.*?<\/span>([\d,]+\.?\d*)<\/bdi>/si', $block, $dm) &&
                    preg_match('/<ins[^>]*>.*?<\/span>([\d,]+\.?\d*)<\/bdi>/si', $block, $im)) {
                    $price      = (float) str_replace(',', '', $dm[1]);
                    $sale_price = (float) str_replace(',', '', $im[1]);
                } else {
                    $price = min($prices);
                }
            }
        }
    }
    if ($price <= 0) $price = 0.01; // placeholder if price not found

    // --- Images (data-large_image) ---
    $images = [];
    preg_match_all('/data-large_image="(https?:\/\/[^"]+\.(jpg|jpeg|png|webp))"/i', $html, $imgMatches);
    if (!empty($imgMatches[1])) {
        $images = array_unique($imgMatches[1]);
        $images = array_values(array_slice($images, 0, 5)); // max 5 images
    }
    // Fallback: try srcset with external URLs
    if (empty($images)) {
        preg_match_all('/srcset="[^"]*?(https:\/\/elitebbswheelsus\.shop\/wp-content\/uploads\/[^\s"]+\.(?:jpg|jpeg|png|webp))\s+1000w/i', $html, $srcMatches);
        if (!empty($srcMatches[1])) {
            $images = array_unique($srcMatches[1]);
            $images = array_values(array_slice($images, 0, 5));
        }
    }

    // --- Short Description ---
    $short_desc = '';
    if (preg_match('/<div class="product-short-description"[^>]*>\s*<p>(.*?)<\/p>/si', $html, $sdm)) {
        $short_desc = mb_substr(trim(strip_tags(html_entity_decode($sdm[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 0, 490);
    }

    // --- Description (tab content) ---
    $description = $short_desc; // use short desc as fallback
    if (preg_match('/id="tab-description"[^>]*>.*?<p>(.*?)<\/p>/si', $html, $descm)) {
        $description = trim(strip_tags(html_entity_decode($descm[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    // --- Category ---
    $category = 'Wheels';
    if (preg_match('/product-category\/([^\/]+)\//i', $html, $catm)) {
        $category = ucwords(str_replace(['-', '_'], ' ', $catm[1]));
    }

    // --- Brand / Size from short desc or name ---
    $brand = '';
    $size  = '';
    // Try to extract size like 17x8, 18x9.5, etc.
    if (preg_match('/(\d{2})[xX×](\d+\.?\d*)/i', $name . ' ' . $short_desc, $sizem)) {
        $size = $sizem[0];
    }

    // Determine if featured (first 20 imports are featured)
    $featured = ($imported < 20) ? 1 : 0;

    // Insert into DB
    $sql = "INSERT INTO products
            (name, slug, short_description, description, price, sale_price, sku, stock,
             category, brand, size, finish, featured, images, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', ?, ?, NOW())";

    db_query($sql, [
        $name,
        $slug,
        $short_desc,
        $description,
        $price,
        $sale_price,
        strtoupper(substr(preg_replace('/[^a-z0-9]/i', '', $slug), 0, 16)) . '-' . $imported,
        10, // default stock
        $category,
        $brand,
        $size,
        $featured,
        json_encode($images)
    ]);

    $imported++;
    echo "✓ [{$imported}] {$name} (slug: {$slug}) — \${$price}" . ($sale_price ? " (sale: \${$sale_price})" : '') . " — " . count($images) . " image(s)\n";
}

echo "\n</pre>";
echo "<h2>Done! Imported {$imported} products, skipped {$skipped} empty files.</h2>";
echo '<p><a href="' . SITE_URL . '/">View Homepage</a> | <a href="' . SITE_URL . '/shop">View Shop</a></p>';
