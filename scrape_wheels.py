#!/usr/bin/env python3
import requests
import os
import re
import json
import html
from urllib.parse import urlparse

session = requests.Session()
session.headers.update({'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})

API_BASE = "https://carsaccessoriesstore.store/wp-json/wp/v2"
ASSETS_DIR = "/opt/lampp/htdocs/elitebbs/img/assets"

def clean_text(text):
    if not text:
        return ""
    text = re.sub(r'<[^>]+>', '', text)
    text = re.sub(r'\s+', ' ', text)
    text = html.unescape(text)
    return text.strip().replace("'", "''").replace("\\", "\\\\")

def extract_jsonld(html_content):
    try:
        match = re.search(r'<script type="application/ld\+json"[^>]*>(.+?)</script>', html_content, re.DOTALL)
        if match:
            data = json.loads(match.group(1))
            for item in data.get('@graph', []):
                if item.get('@type') == 'Product':
                    offers = item.get('offers', [{}])
                    if offers:
                        price_spec = offers[0].get('priceSpecification', [{}])
                        price = price_spec[0].get('price') if price_spec else ''
                    else:
                        price = ''
                    return {
                        'description': item.get('description', ''),
                        'price': price
                    }
    except:
        pass
    return {'description': '', 'price': ''}

def extract_brand(name):
    name_lower = name.lower()
    if 'rays' in name_lower:
        return 'Rays Engineering'
    elif 'toyota' in name_lower:
        return 'Toyota'
    elif 'bmw' in name_lower:
        return 'BMW'
    elif 'te37sl' in name_lower or 'te37' in name_lower:
        return 'Volk Racing'
    elif 'shelby' in name_lower:
        return 'Ford'
    elif 'forged' in name_lower:
        return 'Forged'
    return 'Aftermarket'

def extract_size(name):
    match = re.search(r'(\d{2})["\']?\s*["″]?RIMS?', name)
    if match and 14 <= int(match.group(1)) <= 24:
        return match.group(1)
    match = re.search(r'18["\']?[/"″]?', name)
    if match:
        return '18'
    match = re.search(r'20["\']?[/"″]?', name)
    if match:
        return '20'
    match = re.search(r'19["\']?[/"″]?', name)
    if match:
        return '19'
    match = re.search(r'(\d{2})["\']?\s*Forged', name)
    if match and 14 <= int(match.group(1)) <= 24:
        return match.group(1)
    return ''

def extract_finish(name):
    name_lower = name.lower()
    if 'black' in name_lower:
        return 'Black'
    elif 'blue' in name_lower:
        return 'Blue'
    elif 'grey' in name_lower or 'gray' in name_lower:
        return 'Grey'
    elif 'chrome' in name_lower:
        return 'Chrome'
    elif 'bronze' in name_lower:
        return 'Bronze'
    return ''

def generate_short_description(name, brand, size, finish):
    parts = [f"Premium {brand} wheels"]
    if size:
        parts.append(f"{size} inch")
    if finish:
        parts.append(finish)
    parts.append("for everyday driving and performance")
    return ", ".join(parts) + ". "

def generate_description(name, brand, size, finish, price):
    desc = f"Upgrade your vehicle with these high-quality {brand} ".lower() + f"{name}. "
    desc += "Engineered for strength and style, these wheels deliver exceptional performance and a sleek look that transforms your ride. "
    desc += f"Sold as a complete set of 4 wheels - everything you need for a full installation. "
    
    if size:
        desc += f"Size: {size} inches. "
    if finish:
        desc += f"Finish: {finish}. "
    
    desc += "Perfect for customers wanting to enhance their vehicle's appearance and handling. "
    desc += f"Compatible with most vehicles using standard bolt patterns. "
    desc += f"Direct fitment - no modifications required for most applications. "
    desc += f"Includes manufacturer warranty for peace of mind. "
    desc += f"Free shipping on orders over $499. "
    desc += f"Questions? Our expert team is here to help you choose the right wheels."
    
    return desc

print("Fetching products from Wheels category...")
resp = session.get(f"{API_BASE}/product?product_cat=248&per_page=100")
products_data = resp.json()

print(f"Found {len(products_data)} products")

products = []

for i, prod in enumerate(products_data):
    product_id = prod['id']
    name = prod['title']['rendered']
    slug = prod['slug']
    link = prod['link']
    
    print(f"Processing ({i+1}/{len(products_data)}): {name}")
    
    page_resp = session.get(link)
    jsonld = extract_jsonld(page_resp.text)
    
    price = jsonld.get('price', '')
    
    media_resp = session.get(f"{API_BASE}/media?parent={product_id}")
    local_images = []
    if media_resp.status_code == 200:
        media_data = media_resp.json()
        for j, img in enumerate(media_data[:5]):
            if img.get('source_url'):
                local_images.append(f"/img/assets/wheel_{product_id}_{j+1}.jpg")
    
    brand = extract_brand(name)
    size = extract_size(name)
    finish = extract_finish(name)
    
    sku = slug.replace('-', '').upper()[:20]
    
    short_desc = generate_short_description(name, brand, size, finish)
    long_desc = generate_description(name, brand, size, finish, price)
    
    products.append({
        'name': clean_text(name),
        'slug': slug,
        'short_description': short_desc,
        'description': long_desc,
        'price': price,
        'sale_price': price,
        'sku': sku,
        'stock': 10,
        'category': 'Wheels',
        'brand': brand,
        'size': size,
        'finish': finish,
        'images': local_images,
        'featured': False,
        'status': 'active'
    })

with open('/opt/lampp/htdocs/elitebbs/products.json', 'w') as f:
    json.dump(products, f, indent=2)

sql_lines = []
sql_lines.append("-- ============================================================")
sql_lines.append("-- Elite BBS Rims — Seed File (Scraped from carsaccessoriesstore.store)")
sql_lines.append("-- ============================================================")
sql_lines.append("")
sql_lines.append("SET NAMES utf8mb4;")
sql_lines.append("SET FOREIGN_KEY_CHECKS = 0;")
sql_lines.append("")
sql_lines.append("-- ------------------------------------------------------------")
sql_lines.append(f"-- PRODUCTS ({len(products)} items)")
sql_lines.append("-- ------------------------------------------------------------")
sql_lines.append("")
sql_lines.append("INSERT IGNORE INTO products")
sql_lines.append("    (name, slug, short_description, description, price, sale_price, sku, stock, category, brand, size, finish, images, featured, status)")
sql_lines.append("VALUES")

values = []
for p in products:
    img_json = json.dumps(p['images']).replace("'", "\\'")
    desc = p['description'].replace("'", "\\'")
    short = p['short_description'].replace("'", "\\'")
    values.append(
        f"  ('{p['name']}', '{p['slug']}', '{short}', '{desc}', {p['price']},{p['sale_price']}, '{p['sku']}', {p['stock']}, '{p['category']}', '{p['brand']}', '{p['size']}', '{p['finish']}', '{img_json}', {str(p['featured']).lower()}, '{p['status']}')"
    )

sql_lines.append(",\n".join(values) + ";")

sql_lines.append("")
sql_lines.append("SET FOREIGN_KEY_CHECKS = 1;")

with open('/opt/lampp/htdocs/elitebbs/seed.sql', 'w') as f:
    f.write("\n".join(sql_lines))

print(f"\nDone! Scraped {len(products)} products")
print("Check seed.sql and products.json")