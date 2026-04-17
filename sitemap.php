<?php
require_once __DIR__ . '/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

$prod_domain = 'https://www.elitebbswheelsus.shop';

$static_pages = [
    ['loc' => $prod_domain . '/',                 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['loc' => $prod_domain . '/shop',             'priority' => '0.9', 'changefreq' => 'daily'],
    ['loc' => $prod_domain . '/about',            'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $prod_domain . '/contact',          'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $prod_domain . '/faq',              'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $prod_domain . '/refund_returns',   'priority' => '0.5', 'changefreq' => 'yearly'],
    ['loc' => $prod_domain . '/terms-conditions', 'priority' => '0.4', 'changefreq' => 'yearly'],
];

$products = get_all_products();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($static_pages as $p): ?>
  <url>
    <loc><?php echo htmlspecialchars($p['loc']); ?></loc>
    <changefreq><?php echo $p['changefreq']; ?></changefreq>
    <priority><?php echo $p['priority']; ?></priority>
  </url>
<?php endforeach; ?>
<?php foreach ($products as $product): ?>
  <url>
    <loc><?php echo htmlspecialchars($prod_domain . '/product/' . $product['slug']); ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    <?php if (!empty($product['created_at'])): ?>
    <lastmod><?php echo date('Y-m-d', strtotime($product['created_at'])); ?></lastmod>
    <?php endif; ?>
  </url>
<?php endforeach; ?>
</urlset>
