<?php
/**
 * Generates product_seed.sql from scraped HTML product pages.
 * Run: php generate_seed.php  (from /opt/lampp/htdocs/elitebbs/)
 */

$product_dir = '/opt/lampp/htdocs/elitebbs/product/';
$output_file = '/opt/lampp/htdocs/elitebbs/product_seed.sql';

$dirs = glob($product_dir . '*', GLOB_ONLYDIR);
sort($dirs);

$skip_keywords = [
    'advan','agilis','29er','apex-race','drive-','extra-',
    'headset','kreuza','schwalbe','spare','swissstop',
    'tt-disc','tt-rim','upgrade','velo-','x-ride','index.php',
];

function skip_it($slug, $kws) {
    foreach ($kws as $kw) { if (strpos($slug,$kw)!==false) return true; }
    return false;
}

function brand_of($name, $slug) {
    $u = strtoupper($name.' '.$slug);
    if (strpos($u,'KONIG')!==false)     return 'Konig';
    if (strpos($u,'RACELINE')!==false)  return 'Raceline';
    if (strpos($u,'ULTRA')!==false)     return 'Ultra Wheels';
    if (strpos($u,'VOLK')!==false)      return 'Volk Racing';
    if (strpos($u,'WEDS')!==false || strpos($u,'WED-')!==false) return 'Weds';
    if (strpos($u,'WORK')!==false)      return 'Work Wheels';
    if (strpos($u,'SSR')!==false)       return 'SSR';
    if (strpos($u,'BLITZ')!==false)     return 'Blitz';
    if (strpos($u,'NISMO')!==false)     return 'Nismo';
    if (strpos($u,'BBS')!==false)       return 'BBS';
    if (strpos($u,'LEON')!==false)      return 'Leon Hardiritt';
    if (strpos($u,'RIVERSIDE')!==false) return 'Riverside';
    return 'Aftermarket';
}

// Strip non-ASCII characters that cause MySQL import failures
function clean($s) {
    $s = html_entity_decode($s, ENT_QUOTES|ENT_HTML5, 'UTF-8');
    $s = preg_replace('/[^\x20-\x7E]/', '', $s);
    return trim(preg_replace('/\s+/', ' ', $s));
}

$products = [];
$counter  = 0;

foreach ($dirs as $dir) {
    $slug = basename($dir);
    if (skip_it($slug, $skip_keywords)) continue;

    $f = $dir . '/index.html';
    if (!file_exists($f) || filesize($f) < 2000) continue;
    $html = file_get_contents($f);

    preg_match('/entry-title">\s*([^\n\t<]+)/s', $html, $m);
    $name = clean(trim($m[1] ?? ''));
    if (empty($name)) continue;

    // Price: <bdi><span class="...CurrencySymbol">&#36;</span>2,400.00</bdi>
    // Use .*? inside <bdi> to skip past the currency <span>
    $regular = null;
    $sale    = null;
    if (preg_match('/<del[^>]*>.*?<bdi>.*?<\/span>([\d,]+\.?\d*)<\/bdi>/s', $html, $pm))
        $regular = (float) str_replace(',', '', $pm[1]);
    if (preg_match('/<ins[^>]*>.*?<bdi>.*?<\/span>([\d,]+\.?\d*)<\/bdi>/s', $html, $pm))
        $sale = (float) str_replace(',', '', $pm[1]);
    if (!$regular) {
        preg_match_all('/<bdi>.*?<\/span>([\d,]+\.?\d*)<\/bdi>/s', $html, $ap);
        foreach ($ap[1] ?? [] as $pv) {
            $v = (float) str_replace(',', '', $pv);
            if ($v > 50) { $regular = $v; break; }
        }
    }
    if (!$regular) continue;

    preg_match_all('/data-large_image="(https:\/\/[^"]+)"/s', $html, $imgs);
    $images = array_slice(array_values(array_unique($imgs[1] ?? [])), 0, 4);

    preg_match('/class="product-short-description"[^>]*>(.*?)<\/(div|section)/s', $html, $dm);
    $desc = substr(clean(strip_tags($dm[1] ?? '')), 0, 490);

    preg_match('/product-category\/([^\/]+)\//', $html, $cm);
    $cat = strtoupper(str_replace('-', ' ', $cm[1] ?? 'ALL PRODUCTS'));

    $size = '';
    if (preg_match('/(\d{2})x(\d+)(?:-(\d))?/', $slug, $sz))
        $size = $sz[1].'x'.$sz[2].(isset($sz[3]) && $sz[3] ? '.'.$sz[3] : '');

    $finish = '';
    $nu = strtoupper($name);
    foreach (['CHROME','GLOSS BLACK','MATTE BLACK','SATIN BLACK','BRONZE','SILVER','RAW','POLISH','GRAPHITE','CERAMIC'] as $fi)
        if (strpos($nu, $fi) !== false) { $finish = ucwords(strtolower($fi)); break; }

    $counter++;
    $products[] = array(
        'name'     => $name,
        'slug'     => $slug,
        'desc'     => $desc,
        'price'    => $sale ?: $regular,
        'regular'  => $regular,
        'sale'     => $sale,
        'sku'      => strtoupper(substr(preg_replace('/[^a-z0-9]/i', '', $slug), 0, 14)).'-'.$counter,
        'stock'    => rand(5, 25),
        'category' => $cat,
        'brand'    => brand_of($name, $slug),
        'size'     => $size,
        'finish'   => $finish,
        'images'   => $images,
        'featured' => ($counter <= 12) ? 1 : 0,
    );
}

$n = count($products);

$sql  = "-- Elite BBS Rims - Product Seed\n";
$sql .= "-- $n products from scraped HTML  |  ".date('Y-m-d')."\n";
$sql .= "-- Run: mysql -u root -h 127.0.0.1 -P 3306 elitebbs_db < product_seed.sql\n\n";
$sql .= "USE elitebbs_db;\n";
$sql .= "SET NAMES utf8mb4;\n\n";
$sql .= "CREATE TABLE IF NOT EXISTS products (\n";
$sql .= "    id                INT PRIMARY KEY AUTO_INCREMENT,\n";
$sql .= "    name              VARCHAR(255)  NOT NULL,\n";
$sql .= "    slug              VARCHAR(255)  UNIQUE NOT NULL,\n";
$sql .= "    description       TEXT,\n";
$sql .= "    short_description VARCHAR(500),\n";
$sql .= "    price             DECIMAL(10,2) NOT NULL,\n";
$sql .= "    sale_price        DECIMAL(10,2) DEFAULT NULL,\n";
$sql .= "    sku               VARCHAR(100)  UNIQUE,\n";
$sql .= "    stock             INT           DEFAULT 10,\n";
$sql .= "    category          VARCHAR(100),\n";
$sql .= "    brand             VARCHAR(100),\n";
$sql .= "    size              VARCHAR(50),\n";
$sql .= "    finish            VARCHAR(50),\n";
$sql .= "    fitment_data      TEXT,\n";
$sql .= "    images            JSON,\n";
$sql .= "    featured          BOOLEAN       DEFAULT FALSE,\n";
$sql .= "    status            ENUM('active','draft') DEFAULT 'active',\n";
$sql .= "    created_at        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,\n";
$sql .= "    updated_at        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n";
$sql .= "    INDEX idx_status   (status),\n";
$sql .= "    INDEX idx_category (category),\n";
$sql .= "    INDEX idx_slug     (slug)\n";
$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
$sql .= "TRUNCATE TABLE products;\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";
$sql .= "INSERT INTO products\n    (name, slug, short_description, price, sale_price, sku, stock, category, brand, size, finish, images, featured, status)\nVALUES\n";

$rows = array();
foreach ($products as $p) {
    $ej = json_encode(array_values($p['images']));
    $sv = $p['sale'] ? number_format($p['sale'], 2, '.', '') : 'NULL';
    $rows[] = sprintf(
        "  ('%s','%s','%s',%s,%s,'%s',%d,'%s','%s','%s','%s','%s',%s,'active')",
        addslashes($p['name']), addslashes($p['slug']), addslashes($p['desc']),
        number_format($p['price'], 2, '.', ''), $sv, addslashes($p['sku']), $p['stock'],
        addslashes($p['category']), addslashes($p['brand']), addslashes($p['size']),
        addslashes($p['finish']), addslashes($ej), $p['featured'] ? 'TRUE' : 'FALSE'
    );
}
$sql .= implode(",\n", $rows) . ";\n";

file_put_contents($output_file, $sql);
echo "$n products written to product_seed.sql\n";
