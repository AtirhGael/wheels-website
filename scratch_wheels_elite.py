#!/usr/bin/env python3
import requests
import os
import re
import json
import html
import time
from urllib.parse import urlparse

session = requests.Session()
session.headers.update({'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})

API_BASE = "https://elitebbswheelsus.shop/wp-json/wp/v2"
ASSETS_DIR = "/opt/lampp/htdocs/elitebbs/img/assets"

CATEGORY_MAP = {
    478: "ADVAN NEOVA",
    475: "BBS Wheels",
    481: "BRAKE Wheel Set",
    473: "KONIG Wheels",
    479: "MICHELIN TIRES",
    480: "RACELINE Wheels",
    476: "SSR Wheels",
    474: "VENETTE Wheels",
    477: "WEDS ALBINO Wheels",
    15: "All Products"
}

def clean_text(text):
    if not text:
        return ""
    text = re.sub(r'<[^>]+>', '', text)
    text = re.sub(r'\s+', ' ', text)
    text = html.unescape(text)
    return text.strip().replace("'", "''").replace("\\", "\\\\")

def download_image(url, product_id, idx):
    try:
        resp = session.get(url, timeout=15)
        if resp.status_code == 200:
            path = urlparse(url).path
            ext = os.path.splitext(path)[1] or '.jpg'
            if not ext.startswith('.') or ext == '.php':
                ext = '.jpg'
            filepath = os.path.join(ASSETS_DIR, f"elite_{product_id}_{idx}{ext}")
            with open(filepath, 'wb') as f:
                f.write(resp.content)
            return filepath.replace("/opt/lampp/htdocs/elitebbs", "")
    except:
        pass
    return None

def extract_jsonld(html_content):
    try:
        match = re.search(r'<script type="application/ld\+json"[^>]*>(.+?)</script>', html_content, re.DOTALL)
        if match:
            data = json.loads(match.group(1))
            for item in data.get('@graph', []):
                if item.get('@type') == 'Product':
                    offers = item.get('offers', [{}])
                    price = ''
                    if offers:
                        price_spec = offers[0].get('priceSpecification', [{}])
                        if price_spec:
                            price = price_spec[0].get('price', '')
                    return {
                        'description': item.get('description', ''),
                        'price': price
                    }
    except:
        pass
    return {'description': '', 'price': ''}

def extract_brand(name):
    name_lower = name.lower()
    if 'bbs' in name_lower:
        return 'BBS'
    elif 'konig' in name_lower:
        return 'Konig'
    elif 'raceline' in name_lower:
        return 'Raceline'
    elif 'ssr' in name_lower:
        return 'SSR'
    elif 'weds' in name_lower:
        return 'Weds'
    elif 'advan' in name_lower:
        return 'Advan'
    elif 'michelin' in name_lower:
        return 'Michelin'
    elif 'venette' in name_lower:
        return 'Venette'
    elif 'work' in name_lower:
        return 'Work Wheels'
    elif 'blitz' in name_lower:
        return 'Blitz'
    elif 'volk' in name_lower:
        return 'Volk Racing'
    elif 'leon' in name_lower:
        return 'Leon Hardiritt'
    elif 'riverside' in name_lower:
        return 'RiverSide'
    elif 'carving' in name_lower:
        return 'Carving'
    elif 'nismo' in name_lower:
        return 'Nismo'
    return 'Aftermarket'

def extract_size(name):
    match = re.search(r'(\d{2})["\']?[\"″]?\s*[xX×]', name)
    if match and 14 <= int(match.group(1)) <= 24:
        return match.group(1)
    match = re.search(r'(\d{2})["\']?\s*[\"″]?$', name)
    if match and 14 <= int(match.group(1)) <= 24:
        return match.group(1)
    match = re.search(r'(\d{2})inches?', name, re.IGNORECASE)
    if match:
        return match.group(1)
    return ''

def extract_finish(name):
    name_lower = name.lower()
    if 'black' in name_lower:
        return 'Black'
    elif 'chrome' in name_lower:
        return 'Chrome'
    elif 'silver' in name_lower or 'machine' in name_lower:
        return 'Silver'
    elif 'bronze' in name_lower:
        return 'Bronze'
    elif 'gold' in name_lower:
        return 'Gold'
    elif 'polish' in name_lower:
        return 'Polished'
    elif 'graphite' in name_lower:
        return 'Graphite'
    elif 'raw' in name_lower:
        return 'Raw'
    return ''

def generate_description(name, brand, size, finish, price):
    price_str = f"${price}" if price else "competitive"
    
    desc = f"Upgrade your vehicle with these quality {name}. "
    desc += "Engineered for superior performance and styling, "
    desc += "these wheels deliver the perfect combination of strength, lightweight design, "
    desc += "and aggressive looks that transform your ride. "
    desc += "Sold as a complete set of 4 wheels for full installation. "
    
    if size:
        desc += f"Diameter: {size} inches. "
    if finish:
        desc += f"Finish: {finish}. "
    
    desc += " Designed for enthusiasts who demand the best in appearance and handling. "
    desc += "Compatible with most vehicles using standard bolt patterns. "
    desc += "Direct fitment - no modifications needed for most applications. "
    desc += "JWL/VIA certified for quality assurance. "
    desc += "Includes manufacturer warranty for peace of mind. "
    desc += "Free shipping on orders over $499. "
    desc += "Need help choosing? Our expert team is here to guide you."
    
    return desc

print("=" * 60)
print("Scraping elitebbswheelsus.shop (FAST MODE - URLs only)")
print("=" * 60)

print("\nFetching product list...")
resp = session.get(f"{API_BASE}/product?per_page=100")
total = int(resp.headers.get('X-WP-Total', 0))
total_pages = int(resp.headers.get('X-WP-TotalPages', 1))
print(f"Found {total} products across {total_pages} pages")

all_products = []
for page in range(1, total_pages + 1):
    print(f"  Fetching page {page}/{total_pages}...")
    resp = session.get(f"{API_BASE}/product?per_page=100&page={page}")
    if resp.status_code == 200:
        all_products.extend(resp.json())
    time.sleep(0.2)

print(f"Loaded {len(all_products)} products")

print("\nProcessing products...")
products_data = []
img_count = 0

for i, prod in enumerate(all_products):
    product_id = prod['id']
    name = prod['title']['rendered']
    slug = prod['slug']
    link = prod['link']
    product_cat_ids = prod.get('product_cat', [])
    category = CATEGORY_MAP.get(product_cat_ids[0]) if product_cat_ids else 'Wheels'
    
    if (i+1) % 50 == 0:
        print(f"  Processed {i+1}/{len(all_products)}...")
    
    page_resp = session.get(link)
    jsonld = extract_jsonld(page_resp.text)
    
    price = jsonld.get('price', '')
    
    media_resp = session.get(f"{API_BASE}/media?parent={product_id}")
    local_images = []
    if media_resp.status_code == 200:
        media_data = media_resp.json()
        for j, img in enumerate(media_data[:5]):
            src = img.get('source_url', '')
            if src:
                saved = download_image(src, product_id, j+1)
                if saved:
                    local_images.append(saved)
                    img_count += 1
    
    brand = extract_brand(name)
    size = extract_size(name)
    finish = extract_finish(name)
    
    sku = f"ELITE{product_id}"
    
    short_desc = f"Premium {brand} {size} inch {finish} wheels for performance." if size or finish else f"Premium {brand} wheels for performance."
    long_desc = generate_description(name, brand, size, finish, price)
    
    products_data.append({
        'name': clean_text(name),
        'slug': slug,
        'short_description': short_desc,
        'description': long_desc,
        'price': float(price) if price else 0,
        'sale_price': float(price) if price else 0,
        'sku': sku,
        'stock': 10,
        'category': category,
        'brand': brand,
        'size': size,
        'finish': finish,
        'images': local_images,
        'featured': i < 10,
        'status': 'active'
    })

with open('/opt/lampp/htdocs/elitebbs/products.json', 'w') as f:
    json.dump(products_data, f, indent=2)

sql_lines = []
sql_lines.append("-- ============================================================")
sql_lines.append("-- Elite BBS Rims — Seed File (Scraped from elitebbswheelsus.shop)")
sql_lines.append(f"-- Total Products: {len(products_data)}")
sql_lines.append("-- ============================================================")
sql_lines.append("")
sql_lines.append("SET NAMES utf8mb4;")
sql_lines.append("SET FOREIGN_KEY_CHECKS = 0;")
sql_lines.append("")
sql_lines.append("-- ------------------------------------------------------------")
sql_lines.append(f"-- PRODUCTS ({len(products_data)} items)")
sql_lines.append("-- ------------------------------------------------------------")
sql_lines.append("")
sql_lines.append("INSERT IGNORE INTO products")
sql_lines.append("    (name, slug, short_description, description, price, sale_price, sku, stock, category, brand, size, finish, images, featured, status)")
sql_lines.append("VALUES")

values = []
batch_size = 50
for i in range(0, len(products_data), batch_size):
    batch = products_data[i:i+batch_size]
    batch_values = []
    for p in batch:
        img_json = json.dumps(p['images']).replace("'", "\\'")
        desc = p['description'].replace("'", "\\'")
        short = p['short_description'].replace("'", "\\'")
        batch_values.append(
            f"  ('{p['name']}', '{p['slug']}', '{short}', '{desc}', {p['price']},{p['sale_price']}, '{p['sku']}', {p['stock']}, '{p['category']}', '{p['brand']}', '{p['size']}', '{p['finish']}', '{img_json}', {str(p['featured']).lower()}, '{p['status']}')"
        )
    values.append(",\n".join(batch_values))
    if i + batch_size < len(products_data):
        values[-1] += ","
    else:
        values[-1] += ";"

sql_lines.extend(values)

sql_lines.append("")
sql_lines.append("SET FOREIGN_KEY_CHECKS = 1;")

with open('/opt/lampp/htdocs/elitebbs/seed.sql', 'w') as f:
    f.write("\n".join(sql_lines))

print(f"\n{'=' * 60}")
print(f"Done! Scraped {len(products_data)} products")
print(f"Images downloaded: {img_count}")
print(f"Images saved to: {ASSETS_DIR}")
print(f"Seed file: /opt/lampp/htdocs/elitebbs/seed.sql")
print(f"{'=' * 60}")