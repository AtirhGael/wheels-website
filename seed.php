<?php
/**
 * Seed Products - Run this once to populate database
 * Access: http://localhost/elitebbs/seed.php
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_PATH . '/db.php';

echo "<h1>Seeding Products...</h1>";

$products = [
    [
        'name' => 'BBS Super RS - Silver',
        'slug' => 'bbs-super-rs-silver',
        'short_description' => 'Classic 3-piece forged wheel - Silver Finish',
        'description' => 'The BBS Super RS is a legendary 3-piece forged wheel, crafted with the same technology used in motorsport. Features iconic Y-spoke design with exceptional strength-to-weight ratio. Perfect for track and street applications.',
        'price' => 450.00,
        'sale_price' => null,
        'sku' => 'BBS-SRS-SIL',
        'stock' => 20,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '18x8.5',
        'finish' => 'Silver',
        'featured' => true,
        'images' => json_encode(['assets/images/products/bbs-super-rs-1.jpg'])
    ],
    [
        'name' => 'BBS Super RS - Black',
        'slug' => 'bbs-super-rs-black',
        'short_description' => 'Classic 3-piece forged wheel - Matte Black',
        'description' => 'The BBS Super RS in stunning matte black finish. Same legendary quality and construction as the original, with a stealthy modern look.',
        'price' => 475.00,
        'sale_price' => 425.00,
        'sku' => 'BBS-SRS-BLK',
        'stock' => 15,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '18x8.5',
        'finish' => 'Matte Black',
        'featured' => true,
        'images' => json_encode(['assets/images/products/bbs-super-rs-black.jpg'])
    ],
    [
        'name' => 'BBS LM - Gold',
        'slug' => 'bbs-lm-gold',
        'short_description' => 'Legendary multi-piece forged wheel - Gold',
        'description' => 'The BBS LM (Lichtmetall) is an iconic motorsport-derived wheel. Features intricate mesh design with exceptional rigidity. Available in stunning gold finish.',
        'price' => 550.00,
        'sale_price' => null,
        'sku' => 'BBS-LM-GOLD',
        'stock' => 12,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '19x9.5',
        'finish' => 'Gold',
        'featured' => true,
        'images' => json_encode(['assets/images/products/bbs-lm-gold.jpg'])
    ],
    [
        'name' => 'BBS FI-R - Gloss Black',
        'slug' => 'bbs-fi-r-gloss-black',
        'short_description' => 'Flow-formed sport wheel - Gloss Black',
        'description' => 'The BBS FI-R features innovative flow-forming technology for lightweight strength. Multi-spoke design with aggressive fitment capability.',
        'price' => 380.00,
        'sale_price' => null,
        'sku' => 'BBS-FIR-BLK',
        'stock' => 25,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '18x9',
        'finish' => 'Gloss Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/bbs-fi-r.jpg'])
    ],
    [
        'name' => 'BBS SR - Gunmetal',
        'slug' => 'bbs-sr-gunmetal',
        'short_description' => 'Sport rim flow-formed - Gunmetal',
        'description' => 'The BBS SR combines flow-forming technology with sophisticated design. Lightweight and strong, perfect for modern performance vehicles.',
        'price' => 320.00,
        'sale_price' => 290.00,
        'sku' => 'BBS-SR-GM',
        'stock' => 30,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '17x8',
        'finish' => 'Gunmetal',
        'featured' => false,
        'images' => json_encode(['assets/images/products/bbs-sr.jpg'])
    ],
    [
        'name' => 'BBS Super Racing - Diamond Black',
        'slug' => 'bbs-super-racing-diamond-black',
        'short_description' => 'Track-ready multi-piece wheel',
        'description' => 'The BBS Super Racing is designed for serious track use. 3-piece construction allows for precise fitment adjustments. Diamond black finish with machined lips.',
        'price' => 650.00,
        'sale_price' => null,
        'sku' => 'BBS-SR-DB',
        'stock' => 8,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '18x10',
        'finish' => 'Diamond Black',
        'featured' => true,
        'images' => json_encode(['assets/images/products/bbs-super-racing.jpg'])
    ]
];

$count = 0;
foreach ($products as $p) {
    // Check if product exists
    $exists = db_get_row("SELECT id FROM products WHERE slug = :slug", [':slug' => $p['slug']]);
    
    if (!$exists) {
        $sql = "INSERT INTO products (
            name, slug, short_description, description, price, sale_price, sku, stock,
            category, brand, size, finish, images, featured, status
        ) VALUES (
            :name, :slug, :short_description, :description, :price, :sale_price, :sku, :stock,
            :category, :brand, :size, :finish, :images, :featured, 'active'
        )";
        
        try {
            $stmt = get_db()->prepare($sql);
            $stmt->execute([
                ':name' => $p['name'],
                ':slug' => $p['slug'],
                ':short_description' => $p['short_description'],
                ':description' => $p['description'],
                ':price' => $p['price'],
                ':sale_price' => $p['sale_price'],
                ':sku' => $p['sku'],
                ':stock' => $p['stock'],
                ':category' => $p['category'],
                ':brand' => $p['brand'],
                ':size' => $p['size'],
                ':finish' => $p['finish'],
                ':images' => $p['images'],
                ':featured' => $p['featured']
            ]);
            $count++;
            echo "<p>✓ Added: " . $p['name'] . "</p>";
        } catch (PDOException $e) {
            echo "<p>✗ Error adding " . $p['name'] . ": " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>- Already exists: " . $p['name'] . "</p>";
    }
}

echo "<h2>Completed! Added $count products.</h2>";
echo "<p><a href='" . site_url('') . "'>Go to Homepage</a></p>";