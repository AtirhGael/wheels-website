<?php
/**
 * Complete Products Seed - Elite BBS Rims
 * Run this to populate database with all products from original site
 */

require_once __DIR__ . '/config.php';
require_once INCLUDES_PATH . '/db.php';

echo "<h1>Seeding Complete Products Database...</h1>";

$products = [
    // BBS Wheels
    [
        'name' => 'BBS Super RS - Silver',
        'slug' => 'bbs-super-rs-silver',
        'short_description' => 'Classic 3-piece forged wheel - Silver Finish',
        'description' => 'The BBS Super RS is a legendary 3-piece forged wheel, crafted with the same technology used in motorsport. Features iconic Y-spoke design with exceptional strength-to-weight ratio.',
        'price' => 450.00,
        'sale_price' => null,
        'sku' => 'BBS-SRS-SIL',
        'stock' => 20,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '18x8.5',
        'finish' => 'Silver',
        'featured' => true,
        'images' => json_encode(['assets/images/products/bbs-super-rs.jpg'])
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
        'description' => 'The BBS LM (Lichtmetall) is an iconic motorsport-derived wheel. Features intricate mesh design with exceptional rigidity.',
        'price' => 550.00,
        'sale_price' => null,
        'sku' => 'BBS-LM-GOLD',
        'stock' => 12,
        'category' => 'Wheels',
        'brand' => 'BBS',
        'size' => '19x9.5',
        'finish' => 'Gold',
        'featured' => true,
        'images' => json_encode(['assets/images/products/bbs-lm.jpg'])
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
        'description' => 'The BBS Super Racing is designed for serious track use. 3-piece construction allows for precise fitment adjustments.',
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
    ],
    // Ultra Wheels
    [
        'name' => '123U SCORPION - Gloss Black',
        'slug' => '123u-scorpion',
        'short_description' => 'Ultra Wheels 123U SCORPION GLOSS BLACK WITH DIAMOND CUT FACE',
        'description' => 'A259196 Ultra Wheels 123U SCORPION GLOSS BLACK WITH DIAMOND CUT FACE AND CLEAR COAT 20×9 8X170 18',
        'price' => 300.86,
        'sale_price' => null,
        'sku' => 'A259196',
        'stock' => 10,
        'category' => 'Wheels',
        'brand' => 'Ultra Wheels',
        'size' => '20x9',
        'finish' => 'Gloss Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/123u-scorpion.jpg'])
    ],
    // SSR Wheels
    [
        'name' => 'SSR Vienna Courage - Chrome',
        'slug' => 'ssr-vienna-courage-chrome',
        'short_description' => 'SSR Vienna Courage Wheels 18x8.5 9.5 ET18-22 Chrome',
        'description' => 'SSR Vienna Courage wheels in stunning chrome finish. Perfect for luxury and performance vehicles.',
        'price' => 450.00,
        'sale_price' => null,
        'sku' => 'SSR-VC-CHR',
        'stock' => 8,
        'category' => 'Wheels',
        'brand' => 'SSR',
        'size' => '18x8.5',
        'finish' => 'Chrome',
        'featured' => false,
        'images' => json_encode(['assets/images/products/ssr-vienna.jpg'])
    ],
    [
        'name' => 'SSR Professor SP1',
        'slug' => 'ssr-professor-sp1',
        'short_description' => 'SSR Professor SP1 Wheel 18x8.5 9.5 ET26 Hi Disk',
        'description' => 'The legendary SSR Professor SP1 - a timeless classic in the Japanese tuning community.',
        'price' => 520.00,
        'sale_price' => 480.00,
        'sku' => 'SSR-SP1',
        'stock' => 6,
        'category' => 'Wheels',
        'brand' => 'SSR',
        'size' => '18x8.5',
        'finish' => 'Silver',
        'featured' => true,
        'images' => json_encode(['assets/images/products/ssr-professor.jpg'])
    ],
    [
        'name' => 'SSR XRX - 4x114.3',
        'slug' => 'ssr-xrx-4x114',
        'short_description' => 'SSR XRX Wheels Set 4x114.3',
        'description' => 'SSR XRX flow-formed wheels, perfect for Japanese imports with 4x114.3 bolt pattern.',
        'price' => 380.00,
        'sale_price' => null,
        'sku' => 'SSR-XRX-114',
        'stock' => 12,
        'category' => 'Wheels',
        'brand' => 'SSR',
        'size' => '17x7',
        'finish' => 'Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/ssr-xrx.jpg'])
    ],
    [
        'name' => 'SSR Koenig - Raw Silver',
        'slug' => 'ssr-koenig',
        'short_description' => 'SSR Koenig Wheels 18x9.5 10.5 ET24 Raw Silver',
        'description' => 'SSR Koenig wheels in beautiful raw silver finish. Multi-piece construction for ultimate customizability.',
        'price' => 580.00,
        'sale_price' => null,
        'sku' => 'SSR-KOENIG',
        'stock' => 5,
        'category' => 'Wheels',
        'brand' => 'SSR',
        'size' => '18x9.5',
        'finish' => 'Raw Silver',
        'featured' => false,
        'images' => json_encode(['assets/images/products/ssr-koenig.jpg'])
    ],
    // Work Wheels
    [
        'name' => 'Work VSMX',
        'slug' => 'work-vsmx',
        'short_description' => 'Work VSMX Wheels 18x8.5 ET18 18x9.5 ET22 A-Disk',
        'description' => 'Work VSMX multi-piece wheels. The ultimate choice for show and track.',
        'price' => 650.00,
        'sale_price' => 599.00,
        'sku' => 'WORK-VSMX',
        'stock' => 4,
        'category' => 'Wheels',
        'brand' => 'Work',
        'size' => '18x8.5/9.5',
        'finish' => 'Black',
        'featured' => true,
        'images' => json_encode(['assets/images/products/work-vsmx.jpg'])
    ],
    [
        'name' => 'Work Ewing Wheels Set',
        'slug' => 'work-ewing',
        'short_description' => 'Work Ewing Wheels Set',
        'description' => 'Work Ewing - classic mesh design with modern engineering. Sold as a set of 4.',
        'price' => 1200.00,
        'sale_price' => null,
        'sku' => 'WORK-EWING',
        'stock' => 3,
        'category' => 'Wheels',
        'brand' => 'Work',
        'size' => '18x9.5',
        'finish' => 'Silver',
        'featured' => false,
        'images' => json_encode(['assets/images/products/work-ewing.jpg'])
    ],
    [
        'name' => 'Work Carving Head 40',
        'slug' => 'work-carving-head-40',
        'short_description' => 'Work Carving Head 40 Wheels 17x8.5',
        'description' => 'Work Carving Head 40 - lightweight flow-formed design for performance.',
        'price' => 420.00,
        'sale_price' => null,
        'sku' => 'WORK-CH40',
        'stock' => 10,
        'category' => 'Wheels',
        'brand' => 'Work',
        'size' => '17x8.5',
        'finish' => 'Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/work-carving.jpg'])
    ],
    // Advan Wheels
    [
        'name' => 'Advan Sport V105',
        'slug' => 'advan-sport-v105',
        'short_description' => 'Advan Sport V105 High Performance Tire',
        'description' => 'Advan Sport V105 - Ultra high performance summer tire. Sold individually.',
        'price' => 280.00,
        'sale_price' => 249.00,
        'sku' => 'ADVAN-V105',
        'stock' => 50,
        'category' => 'Tires',
        'brand' => 'Advan',
        'size' => '225/45R17',
        'finish' => null,
        'featured' => false,
        'images' => json_encode(['assets/images/products/advan-v105.jpg'])
    ],
    [
        'name' => 'Advan Sport V107',
        'slug' => 'advan-sport-v107',
        'short_description' => 'Advan Sport V107 Performance Tire',
        'description' => 'Advan Sport V107 - Next generation ultra high performance tire.',
        'price' => 320.00,
        'sale_price' => null,
        'sku' => 'ADVAN-V107',
        'stock' => 40,
        'category' => 'Tires',
        'brand' => 'Advan',
        'size' => '235/40R18',
        'finish' => null,
        'featured' => true,
        'images' => json_encode(['assets/images/products/advan-v107.jpg'])
    ],
    [
        'name' => 'Advan Neova AD09',
        'slug' => 'advan-neova-ad09',
        'short_description' => 'Advan Neova AD09 Extreme Performance Tire',
        'description' => 'Advan Neova AD09 - Ultimate grip for track and aggressive street use.',
        'price' => 350.00,
        'sale_price' => null,
        'sku' => 'ADVAN-AD09',
        'stock' => 30,
        'category' => 'Tires',
        'brand' => 'Advan',
        'size' => '265/35R18',
        'finish' => null,
        'featured' => false,
        'images' => json_encode(['assets/images/products/advan-ad09.jpg'])
    ],
    // Drive Wheels
    [
        'name' => 'Drive 36D-II Double Wheels',
        'slug' => 'drive-36d-ii',
        'short_description' => 'Drive 36D-II Double Wheels - Front and Rear Set',
        'description' => 'Drive 36D-II complete set with front and rear wheels. Perfect for drift and track.',
        'price' => 850.00,
        'sale_price' => 780.00,
        'sku' => 'DRIVE-36D',
        'stock' => 5,
        'category' => 'Wheels',
        'brand' => 'Drive',
        'size' => '18x9.5',
        'finish' => 'Black',
        'featured' => true,
        'images' => json_encode(['assets/images/products/drive-36d.jpg'])
    ],
    [
        'name' => 'Drive G45 CS',
        'slug' => 'drive-g45-cs',
        'short_description' => 'Drive G45 CS Ceramic Series',
        'description' => 'Drive G45 CS (Ceramic Series) - Premium multi-piece wheels with ceramic finish.',
        'price' => 680.00,
        'sale_price' => null,
        'sku' => 'DRIVE-G45',
        'stock' => 6,
        'category' => 'Wheels',
        'brand' => 'Drive',
        'size' => '19x10',
        'finish' => 'Ceramic',
        'featured' => false,
        'images' => json_encode(['assets/images/products/drive-g45.jpg'])
    ],
    [
        'name' => 'Drive Helix 68D SS',
        'slug' => 'drive-helix-68d-ss',
        'short_description' => 'Drive Helix 68D SS - Surface Silver',
        'description' => 'Drive Helix 68D in stunning surface silver finish. Multi-spoke design.',
        'price' => 550.00,
        'sale_price' => null,
        'sku' => 'DRIVE-68D',
        'stock' => 8,
        'category' => 'Wheels',
        'brand' => 'Drive',
        'size' => '18x9',
        'finish' => 'Silver',
        'featured' => false,
        'images' => json_encode(['assets/images/products/drive-helix.jpg'])
    ],
    [
        'name' => 'Drive 40V 3K Black',
        'slug' => 'drive-40v-3k-black',
        'short_description' => 'Drive 40V 3K Black Partial Wheelset',
        'description' => 'Drive 40V 3K in black finish. Sold as partial set for rear fitment.',
        'price' => 420.00,
        'sale_price' => null,
        'sku' => 'DRIVE-40V-BLK',
        'stock' => 4,
        'category' => 'Wheels',
        'brand' => 'Drive',
        'size' => '18x10',
        'finish' => 'Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/drive-40v.jpg'])
    ],
    // Rays / Volkl
    [
        'name' => 'Volk Racing TE37 SL',
        'slug' => 'volk-te37-sl',
        'short_description' => 'Volk Racing TE37 SL Saga Wheel 19x9.5 ET42 A-Disk',
        'description' => 'The legendary TE37 SL (Super Light) - the benchmark for forged wheels.',
        'price' => 750.00,
        'sale_price' => null,
        'sku' => 'VOLK-TE37',
        'stock' => 6,
        'category' => 'Wheels',
        'brand' => 'Volk Racing',
        'size' => '19x9.5',
        'finish' => 'Black',
        'featured' => true,
        'images' => json_encode(['assets/images/products/volk-te37.jpg'])
    ],
    // Weds / Kranze
    [
        'name' => 'Weds Kranze Cerberus',
        'slug' => 'weds-kranze-cerberus',
        'short_description' => 'Weds Kranze Cerberus Wheels 18x9.5 ET12-16 Ceramic Silver',
        'description' => 'Weds Kranze Cerberus - aggressive multi-spoke design in ceramic silver.',
        'price' => 620.00,
        'sale_price' => 580.00,
        'sku' => 'KRANZE-CERB',
        'stock' => 4,
        'category' => 'Wheels',
        'brand' => 'Weds Kranze',
        'size' => '18x9.5',
        'finish' => 'Ceramic Silver',
        'featured' => false,
        'images' => json_encode(['assets/images/products/kranze-cerberus.jpg'])
    ],
    [
        'name' => 'Weds Kranze Vishunu',
        'slug' => 'weds-kranze-vishunu',
        'short_description' => 'Weds Kranze Vishunu Wheels 18x9.5 ET28-32 Raw Chrome',
        'description' => 'Weds Kranze Vishunu in raw chrome finish. Bold and aggressive design.',
        'price' => 680.00,
        'sale_price' => null,
        'sku' => 'KRANZE-VISH',
        'stock' => 3,
        'category' => 'Wheels',
        'brand' => 'Weds Kranze',
        'size' => '18x9.5',
        'finish' => 'Raw Chrome',
        'featured' => false,
        'images' => json_encode(['assets/images/products/kranze-vishunu.jpg'])
    ],
    // Leon Hardiritt
    [
        'name' => 'Leon Hardiritt Waffe',
        'slug' => 'leon-hardiritt-waffe',
        'short_description' => 'Leon Hardiritt Waffe Wheels 18x9.5 ET32 Hi-Disk Chrome',
        'description' => 'Leon Hardiritt Waffe - classic German engineering meets custom styling.',
        'price' => 850.00,
        'sale_price' => null,
        'sku' => 'LEON-WAFF',
        'stock' => 2,
        'category' => 'Wheels',
        'brand' => 'Leon Hardiritt',
        'size' => '18x9.5',
        'finish' => 'Chrome',
        'featured' => false,
        'images' => json_encode(['assets/images/products/leon-waffe.jpg'])
    ],
    // Enkei
    [
        'name' => 'Enkei RPF1',
        'slug' => 'enkei-rpf1',
        'short_description' => 'Enkei RPF1 - Classic Single Piece',
        'description' => 'Enkei RPF1 - the legendary affordable performance wheel. Lightweight and strong.',
        'price' => 220.00,
        'sale_price' => 199.00,
        'sku' => 'ENKEI-RPF1',
        'stock' => 25,
        'category' => 'Wheels',
        'brand' => 'Enkei',
        'size' => '17x8',
        'finish' => 'Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/enkei-rpf1.jpg'])
    ],
    // Misc Products
    [
        'name' => 'TT Rim Brake Bundle',
        'slug' => 'tt-rim-brake-bundle',
        'short_description' => 'TT Rim with Brake Bundle Package',
        'description' => 'Complete TT rim package including brake components. Perfect for track setup.',
        'price' => 450.00,
        'sale_price' => null,
        'sku' => 'TT-BUNDLE',
        'stock' => 10,
        'category' => 'Packages',
        'brand' => 'Various',
        'size' => '18x9',
        'finish' => 'Black',
        'featured' => true,
        'images' => json_encode(['assets/images/products/tt-rim.jpg'])
    ],
    [
        'name' => 'Velo TT Rim',
        'slug' => 'velo-tt-rim',
        'short_description' => 'Velo TT Rim - Lightweight Track Rim',
        'description' => 'Velo TT rim - designed for time attack and track use. Extremely lightweight.',
        'price' => 380.00,
        'sale_price' => null,
        'sku' => 'VELO-TT',
        'stock' => 8,
        'category' => 'Wheels',
        'brand' => 'Velo',
        'size' => '17x9',
        'finish' => 'Silver',
        'featured' => false,
        'images' => json_encode(['assets/images/products/velo-tt.jpg'])
    ],
    [
        'name' => 'Drive 65D Disc Brake',
        'slug' => 'drive-65d-disc-brake',
        'short_description' => 'Drive 65D Disc Brake Set',
        'description' => 'Drive 65D performance disc brake kit. Compatible with various wheel setups.',
        'price' => 280.00,
        'sale_price' => null,
        'sku' => 'DRIVE-65D',
        'stock' => 15,
        'category' => 'Brakes',
        'brand' => 'Drive',
        'size' => null,
        'finish' => null,
        'featured' => false,
        'images' => json_encode(['assets/images/products/drive-brake.jpg'])
    ],
    // Raceline
    [
        'name' => 'Raceline 131B Evo - Satin Black',
        'slug' => 'raceline-131b-evo',
        'short_description' => 'Raceline 131B Evo Satin Black Wheels',
        'description' => 'Raceline 131B Evo in satin black finish. Modern multi-spoke design.',
        'price' => 260.00,
        'sale_price' => 230.00,
        'sku' => 'RACELINE-131B',
        'stock' => 18,
        'category' => 'Wheels',
        'brand' => 'Raceline',
        'size' => '18x8',
        'finish' => 'Satin Black',
        'featured' => false,
        'images' => json_encode(['assets/images/products/raceline-131b.jpg'])
    ],
    [
        'name' => 'Raceline 141S Mystique',
        'slug' => 'raceline-141s-mystique',
        'short_description' => 'Raceline 141S Mystique Silver Wheels',
        'description' => 'Raceline 141S Mystique in classic silver. Timeless design.',
        'price' => 240.00,
        'sale_price' => null,
        'sku' => 'RACELINE-141S',
        'stock' => 20,
        'category' => 'Wheels',
        'brand' => 'Raceline',
        'size' => '17x7.5',
        'finish' => 'Silver',
        'featured' => false,
        'images' => json_encode(['assets/images/products/raceline-141s.jpg'])
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
            echo "<p style='color: green;'>✓ Added: " . $p['name'] . " - $" . number_format($p['price'], 2) . "</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Error adding " . $p['name'] . ": " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: gray;'>- Already exists: " . $p['name'] . "</p>";
    }
}

echo "<h2>Completed! Added $count products.</h2>";
echo "<p>Categories in database: Wheels, Tires, Packages, Brakes</p>";
echo "<p><a href='" . site_url('') . "' style='color: #008cb2;'>Go to Homepage</a> | <a href='" . site_url('shop') . "' style='color: #008cb2;'>View Shop</a></p>";