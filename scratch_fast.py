#!/usr/bin/env python3
import requests
import re
import json
import html
import time

session = requests.Session()
session.headers.update({'User-Agent': 'Mozilla/5.0'})

API_BASE = "https://elitebbswheelsus.shop/wp-json/wp/v2"

CATEGORY_MAP = {
    478: "ADVAN NEOVA", 475: "BBS Wheels", 481: "BRAKE Wheel Set",
    473: "KONIG Wheels", 479: "MICHELIN TIRES", 480: "RACELINE Wheels",
    476: "SSR Wheels", 474: "VENETTE Wheels", 477: "WEDS ALBINO Wheels", 15: "All Products"
}

def clean_text(text):
    if not text: return ""
    text = re.sub(r'<[^>]+>', '', text)
    text = re.sub(r'\s+', ' ', text)
    return html.unescape(text).strip().replace("'", "''")

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
                        ps = offers[0].get('priceSpecification', [{}])
                        if ps: price = ps[0].get('price', '')
                    return {'description': item.get('description', ''), 'price': price}
    except: pass
    return {'description': '', 'price': ''}

def extract_brand(name):
    n = name.lower()
    if 'bbs' in n: return 'BBS'
    if 'konig' in n: return 'Konig'
    if 'raceline' in n: return 'Raceline'
    if 'ssr' in n: return 'SSR'
    if 'weds' in n: return 'Weds'
    if 'advan' in n: return 'Advan'
    if 'michelin' in n: return 'Michelin'
    if 'work' in n: return 'Work Wheels'
    if 'blitz' in n: return 'Blitz'
    if 'volk' in n: return 'Volk Racing'
    if 'leon' in n: return 'Leon Hardiritt'
    return 'Aftermarket'

def extract_size(name):
    m = re.search(r'(\d{2})["\']?[\"″]?\s*[xX×]', name)
    if m and 14 <= int(m.group(1)) <= 24: return m.group(1)
    m = re.search(r'(\d{2})inches?', name, re.IGNORECASE)
    if m: return m.group(1)
    return ''

def extract_finish(name):
    n = name.lower()
    if 'black' in n: return 'Black'
    if 'chrome' in n: return 'Chrome'
    if 'silver' in n or 'machine' in n: return 'Silver'
    if 'bronze' in n: return 'Bronze'
    if 'gold' in n: return 'Gold'
    if 'polish' in n: return 'Polished'
    if 'graphite' in n: return 'Graphite'
    return ''

def gen_desc(name, brand, size, finish, price):
    desc = f"Upgrade your vehicle with these quality {name}. "
    desc += "Engineered for superior performance and styling. Sold as a complete set of 4 wheels. "
    if size: desc += f"Diameter: {size} inches. "
    if finish: desc += f"Finish: {finish}. "
    desc += "JWL/VIA certified. Free shipping over $499. Questions? Our team is here to help."
    return desc

print("Fetching products...")
resp = session.get(f"{API_BASE}/product?per_page=100")
total = int(resp.headers.get('X-WP-Total', 0))
total_pages = int(resp.headers.get('X-WP-TotalPages', 1))
print(f"Found {total} products across {total_pages} pages")

all_products = []
for page in range(1, total_pages + 1):
    print(f"  Page {page}/{total_pages}...")
    r = session.get(f"{API_BASE}/product?per_page=100&page={page}")
    if r.status_code == 200: all_products.extend(r.json())
    time.sleep(0.15)

print(f"Processing {len(all_products)} products...")

products_data = []
for i, prod in enumerate(all_products):
    pid = prod['id']
    name = prod['title']['rendered']
    slug = prod['slug']
    link = prod['link']
    cats = prod.get('product_cat', [])
    cat = CATEGORY_MAP.get(cats[0]) if cats else 'Wheels'
    
    if (i+1) % 100 == 0: print(f"  {i+1}/{len(all_products)}...")
    
    pr = session.get(link)
    jd = extract_jsonld(pr.text)
    price = jd.get('price', '')
    
    mr = session.get(f"{API_BASE}/media?parent={pid}")
    imgs = []
    if mr.status_code == 200:
        for j, img in enumerate(mr.json()[:5]):
            src = img.get('source_url', '')
            if src: imgs.append(src)
    
    brand = extract_brand(name)
    size = extract_size(name)
    finish = extract_finish(name)
    sku = f"ELITE{pid}"
    
    short = f"Premium {brand} {size} inch wheels for performance." if size else f"Premium {brand} wheels."
    long = gen_desc(name, brand, size, finish, price)
    
    products_data.append({
        'name': clean_text(name), 'slug': slug, 'short_description': short,
        'description': long, 'price': float(price) if price else 0,
        'sale_price': float(price) if price else 0, 'sku': sku, 'stock': 10,
        'category': cat, 'brand': brand, 'size': size, 'finish': finish,
        'images': imgs, 'featured': i < 10, 'status': 'active'
    })

print(f"\nWriting {len(products_data)} products to files...")

with open('/opt/lampp/htdocs/elitebbs/products.json', 'w') as f:
    json.dump(products_data, f, indent=2)

sql = ["-- Elite BBS Rims — Seed File", "SET NAMES utf8mb4;", "SET FOREIGN_KEY_CHECKS = 0;",
       "INSERT IGNORE INTO products (name, slug, short_description, description, price, sale_price, sku, stock, category, brand, size, finish, images, featured, status) VALUES"]

batch = 50
for i in range(0, len(products_data), batch):
    b = products_data[i:i+batch]
    vals = []
    for p in b:
        img = json.dumps(p['images']).replace("'", "\\'")
        d = p['description'].replace("'", "\\'")
        s = p['short_description'].replace("'", "\\'")
        vals.append(f"  ('{p['name']}', '{p['slug']}', '{s}', '{d}', {p['price']},{p['sale_price']}, '{p['sku']}', {p['stock']}, '{p['category']}', '{p['brand']}', '{p['size']}', '{p['finish']}', '{img}', {str(p['featured']).lower()}, '{p['status']}')")
    sql.append(",\n".join(vals) + ("" if i + batch < len(products_data) else ";"))

sql.append("SET FOREIGN_KEY_CHECKS = 1;")

with open('/opt/lampp/htdocs/elitebbs/seed.sql', 'w') as f:
    f.write("\n".join(sql))

print(f"\nDone! {len(products_data)} products")
print("Files: /opt/lampp/htdocs/elitebbs/seed.sql and products.json")