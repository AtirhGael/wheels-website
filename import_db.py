#!/usr/bin/env python3
import json
import re
import html
import pymysql

CATEGORY_MAP = {
    478: "ADVAN NEOVA", 475: "BBS Wheels", 481: "BRAKE Wheel Set",
    473: "KONIG Wheels", 479: "MICHELIN TIRES", 480: "RACELINE Wheels",
    476: "SSR Wheels", 474: "VENETTE Wheels", 477: "WEDS ALBINO Wheels", 15: "All Products"
}

def escape_sql(s):
    if s is None:
        return "NULL"
    s = str(s)
    s = s.replace("\\", "\\\\")
    s = s.replace("'", "\\'")
    s = s.replace('"', '\\"')
    return f"'{s}'"

def clean_text(text):
    if not text: return ""
    text = re.sub(r'<[^>]+>', '', text)
    text = re.sub(r'\s+', ' ', text)
    return html.unescape(text).strip()

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

def gen_desc(name, brand, size, finish):
    desc = f"Upgrade your vehicle with these quality {name}. "
    desc += "Engineered for superior performance and styling. Sold as a complete set of 4 wheels. "
    if size: desc += f"Diameter: {size} inches. "
    if finish: desc += f"Finish: {finish}. "
    desc += "JWL/VIA certified. Free shipping over $499. Questions? Our team is here to help."
    return desc

print("Loading pre-fetched product pages...")

all_products = []
for i in range(1, 10):
    with open(f'/opt/lampp/htdocs/elitebbs/pg_{i}.json', 'r') as f:
        data = json.load(f)
        all_products.extend(data)
        print(f"  Loaded page {i}: {len(data)} products")

print(f"\nTotal products: {len(all_products)}")

connection = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    database='elitebbs_db',
    charset='utf8mb4'
)

cursor = connection.cursor()

insert_sql = """
INSERT IGNORE INTO products 
(name, slug, short_description, description, price, sale_price, sku, stock, category, brand, size, finish, images, featured, status) 
VALUES ({}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {})
"""

count = 0
errors = 0

for i, prod in enumerate(all_products):
    try:
        pid = prod['id']
        name = clean_text(prod['title']['rendered'])
        slug = prod['slug']
        cats = prod.get('product_cat', [])
        cat = CATEGORY_MAP.get(cats[0]) if cats else 'Wheels'
        
        brand = extract_brand(name)
        size = extract_size(name)
        finish = extract_finish(name)
        sku = f"ELITE{pid}"
        
        short = f"Premium {brand} {size} inch wheels for performance." if size else f"Premium {brand} wheels."
        long = gen_desc(name, brand, size, finish)
        
        values = (
            escape_sql(name), escape_sql(slug), escape_sql(short), escape_sql(long),
            '0', '0', escape_sql(sku), '10', escape_sql(cat), escape_sql(brand),
            escape_sql(size), escape_sql(finish), "'[]'", '0', "'active'"
        )
        
        sql = insert_sql.format(*values)
        cursor.execute(sql)
        count += 1
        
        if (i + 1) % 50 == 0:
            connection.commit()
            print(f"  Inserted {i+1} products...")
            
    except Exception as e:
        errors += 1
        if errors <= 3:
            print(f"  Error with {name[:30]}: {e}")

connection.commit()
cursor.close()
connection.close()

print(f"\nDone! {count} products inserted, {errors} errors")