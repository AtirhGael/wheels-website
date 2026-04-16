<?php
/**
 * Generate Placeholder Images
 * Run this once to create placeholder images in assets/images/
 */

$images_dir = __DIR__ . '/assets/images';

// Create placeholder PNG (1x1 transparent)
$png_data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
file_put_contents($images_dir . '/placeholder.png', $png_data);

// Create a simple favicon
file_put_contents($images_dir . '/favicon.png', $png_data);

echo "Placeholder images created.\n";
echo "Note: You should copy actual images from wp-content/uploads/ to assets/images/\n";
echo "\nImage folders to populate:\n";
echo "- assets/images/products/\n";
echo "- assets/images/bbs-hero.jpg (copy from wp-content)\n";
echo "- assets/images/about-wheel.jpg (copy from wp-content)\n";
echo "- assets/images/logo.png (copy from wp-content)\n";