#!/usr/bin/env python3
import json

print("Loading pre-fetched product pages...")

all_products = []
for i in range(1, 10):
    with open(f'/opt/lampp/htdocs/elitebbs/pg_{i}.json', 'r') as f:
        data = json.load(f)
        all_products.extend(data)
        print(f"  Loaded page {i}: {len(data)} products")

print(f"\nTotal products: {len(all_products)}")

for prod in all_products[:5]:
    pid = prod['id']
    content = prod.get('content', {}).get('rendered', '')
    print(f"\n--- Product {pid} ---")
    print(content[:500])
