# Architecture Plan: Elite BBS Rims Migration

## Project Overview

**Goal:** Migrate elitebbswheelsus.shop from WordPress/WooCommerce to custom PHP + MySQL (XAMPP)

**Constraints:**
- Keep the same visual design
- Email order notifications only (no payment processing)
- No user accounts (guest checkout)

---

## Architecture

```
XAMPP (Apache + MySQL + PHP)
├── Document Root: C:\xampp\htdocs\elitebbs\
│   ├── index.php              (Homepage)
│   ├── shop/                 (Product listing)
│   ├── product/              (Product detail pages)
│   ├── cart/                (Shopping cart)
│   ├── checkout/            (Order submission)
│   ├── about/               (About page)
│   ├── contact/             (Contact page)
│   ├── faq/                 (FAQ page)
│   ├── refund_returns/       (Refund policy)
│   ├── terms-conditions/    (Terms page)
│   ├── testemonials/        (Reviews page - typo in original)
│   ├── includes/             (Shared PHP files)
│   │   ├── db.php           (Database connection)
│   │   ├── header.php        (Shared header/nav)
│   │   ├── footer.php        (Shared footer)
│   │   ├── functions.php     (Helper functions)
│   │   └── send_order.php   (Email order logic)
│   ├── assets/              (CSS, JS, images)
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── config.php           (Config settings)
│
└── MySQL Database: elitebbs_db
    ├── products (id, name, price, description, images, fitment_data)
    ├── orders (id, customer_name, email, phone, address, vehicle_info, items_json, total, status, created_at)
    └── order_items (id, order_id, product_id, qty, price)
```

---

## Database Schema

```sql
-- Database: elitebbs_db

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    sku VARCHAR(100) UNIQUE,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    brand VARCHAR(100),
    size VARCHAR(50),
    finish VARCHAR(50),
    fitment_data JSON,
    images JSON,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active','draft') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(30),
    shipping_address TEXT,
    vehicle_make VARCHAR(50),
    vehicle_model VARCHAR(50),
    vehicle_year VARCHAR(10),
    notes TEXT,
    items_json JSON NOT NULL,
    subtotal DECIMAL(10,2),
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Implementation Steps

### Phase 1: Setup & Database (Day 1)
1. Create XAMPP virtual host configuration
2. Create MySQL database and tables
3. Create config.php with database credentials
4. Create db.php connection class

### Phase 2: Core Template System (Day 2)
1. Convert shared header to PHP includes
2. Convert shared footer to PHP includes
3. Create functions.php with helpers
4. Copy/organize CSS + JS assets

### Phase 3: Product Management (Day 3-4)
1. Create product listing page (shop/)
2. Create product detail template
3. Import products data from static HTML pages
4. Build vehicle fitment search (reuse existing JS)

### Phase 4: Cart & Checkout (Day 5-6)
1. Build cart page with PHP session
2. Create checkout form (name, email, phone, address, vehicle info)
3. Implement order submit → save to MySQL
4. Implement send_order.php → email notification to you

### Phase 5: Static Pages (Day 7)
1. Migrate: About, Contact, FAQ, Refund Policy, Terms, Testimonials
2. Fix any hardcoded links (.html → /)

### Phase 6: Testing & Launch (Day 8)
1. Test cart flow
2. Test checkout → email notification
3. Verify all pages render correctly
4. Update .htaccess for clean URLs

---

## Key Files to Create

| File | Purpose |
|------|---------|
| `config.php` | DB credentials, site config |
| `includes/db.php` | MySQL connection |
| `includes/header.php` | Navigation + logo |
| `includes/footer.php` | Footer content |
| `includes/functions.php` | Product lookups, cart helpers |
| `includes/send_order.php` | PHPMailer email logic |
| `shop/index.php` | Product grid |
| `product/index.php` | Single product view |
| `cart/index.php` | Shopping cart |
| `checkout/index.php` | Order form |

---

## Technical Notes

1. **Session-based cart** - Store cart in `$_SESSION['cart']`
2. **Email** - Use PHP built-in `mail()` or PHPMailer (via composer)
3. **Images** - Copy from `wp-content/uploads/` to `assets/images/`
4. **Clean URLs** - Use .htaccess with `mod_rewrite`

---

## Files Created

### Core Files
- `config.php` - Database credentials and site configuration
- `database.sql` - MySQL schema for products and orders tables
- `seed.php` - Script to populate sample product data

### PHP Pages
- `index.php` - Homepage with vehicle fitment search
- `shop/index.php` - Product listing with search/filter
- `product/index.php` - Single product detail page
- `cart/index.php` - Shopping cart page
- `checkout/index.php` - Order form with email notification
- `about/index.php` - About page
- `contact/index.php` - Contact form with email
- `faq/index.php` - FAQ page
- `refund_returns/index.php` - Refund policy page
- `terms-conditions/index.php` - Terms and conditions
- `testemonials/index.php` - Customer reviews

### Includes
- `includes/db.php` - Database connection class with helper functions
- `includes/header.php` - Shared navigation header
- `includes/footer.php` - Shared footer

### Assets
- `assets/css/style.css` - Main stylesheet
- `assets/js/main.js` - Cart and vehicle search JavaScript
- `.htaccess` - URL rewriting rules

---

## How to Deploy

### 1. XAMPP Setup
```
1. Open XAMPP Control Panel
2. Start Apache and MySQL
3. Go to http://localhost/phpmyadmin
4. Import database.sql
```

### 2. Deploy Files
```
Copy all files to: C:\xampp\htdocs\elitebbs\
```

### 3. Access Site
```
http://localhost/elitebbs/
```

### 4. Seed Products
```
Visit: http://localhost/elitebbs/seed.php
```

---

## Status: ✅ Phase 1-3 Complete

### Phase 2 Enhancements
- `includes/functions.php` - Helper functions for products, cart, orders
- `includes/product_card.php` - Reusable product card component
- Enhanced `style.css` - Full design matching original theme
- `index.php` - Homepage with hero, fitment search, featured products
- `shop/index.php` - Full shop page with sidebar filtering
- `generate_images.php` - Script to create placeholders
- `DEPLOY.md` - Complete deployment guide

### Phase 3 Product Management
- `seed_full.php` - Comprehensive product seed with 30+ products
- Products extracted from original WordPress site
- Includes: BBS, SSR, Work, Advan, Drive, Rays/Volk, Weds Kranze, Raceline, Enkei, Leon Hardiritt
- Categories: Wheels, Tires, Packages, Brakes

### Phase 4 Cart & Checkout
- `includes/cart_functions.php` - Full cart management (add, remove, update, clear)
- `includes/cart_ajax.php` - AJAX endpoints for cart operations
- `assets/js/main.js` - Updated cart JavaScript with AJAX support
- `product/index.php` - Product detail with add-to-cart functionality
- `cart/index.php` - Full shopping cart with quantity controls
- `checkout/index.php` - Complete checkout form with validation
- `checkout/success/index.php` - Order confirmation page

### Phase 5 Static Pages
- `about/index.php` - About page with hero header and content
- `contact/index.php` - Contact form with email handling
- `faq/index.php` - FAQ page with 10 common questions
- `refund_returns/index.php` - Refund policy page
- `terms-conditions/index.php` - Terms and conditions
- `testemonials/index.php` - Customer reviews page

### Phase 6 Code Review & Fixes
- Fixed SQL parameter binding in functions.php
- Added global sanitize() function
- Verified database schema
- Verified all includes are correct
- Added README.md

## Status: ✅ All Phases Complete - Code Verified