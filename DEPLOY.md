# Elite BBS Rims - Deployment Instructions

## Quick Start

### 1. Setup XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** modules

### 2. Create Database
1. Open **phpMyAdmin**: http://localhost/phpmyadmin
2. Create new database: `elitebbs_db`
3. Click "Import" and select `database.sql`
4. Click "Go" to import

### 3. Deploy Files
```
Copy the entire project folder to:
C:\xampp\htdocs\elitebbs\

(Or create a folder named 'elitebbs' in htdocs first, then copy files)
```

### 4. Update Config (if needed)
Edit `config.php` if your XAMPP MySQL has a password:
```php
define('DB_PASS', '');  // Add your password if set
```

### 5. Seed Products
Visit: http://localhost/elitebbs/seed.php

### 6. View Site
Go to: http://localhost/elitebbs/

---

## File Structure

```
C:\xampp\htdocs\elitebbs\
├── config.php          (DB settings)
├── database.sql        (SQL schema)
├── seed.php           (Add sample products)
├── index.php          (Homepage)
├── .htaccess          (URL rewriting)
├── shop/
│   └── index.php      (Product listing)
├── product/
│   └── index.php      (Product detail)
├── cart/
│   └── index.php      (Shopping cart)
├── checkout/
│   └── index.php      (Checkout form)
├── about/
│   └── index.php
├── contact/
│   └── index.php
├── faq/
│   └── index.php
├── refund_returns/
│   └── index.php
├── terms-conditions/
│   └── index.php
├── testemonials/
│   └── index.php
├── includes/
│   ├── db.php
│   ├── functions.php
│   ├── header.php
│   ├── footer.php
│   └── product_card.php
└── assets/
    ├── css/style.css
    ├── js/main.js
    └── images/
        ├── placeholder.png
        ├── logo.png (copy from wp-content)
        ├── bbs-hero.jpg (copy from wp-content)
        └── products/ (copy wheel images)
```

---

## Copying Images from WordPress

The original site has images in `wp-content/uploads/`. Copy these to `assets/images/`:

1. **Logo**: Copy `wp-content/uploads/2026/02/Screenshot-2026-02-03-at-00.53.55.png` → `assets/images/logo.png`

2. **Hero Background**: Copy `wp-content/uploads/2026/02/bbs.png` → `assets/images/bbs-hero.jpg`

3. **Product Images**: Copy from `wp-content/uploads/2026/02/` → `assets/images/products/`

4. **About Section**: Copy any relevant wheel image → `assets/images/about-wheel.jpg`

---

## Troubleshooting

### "Database connection failed"
- Check that MySQL is running
- Verify DB credentials in `config.php`
- Make sure database was created in phpMyAdmin

### "No products showing"
- Run `seed.php` to add sample products
- Or manually add products via phpMyAdmin

### "Page not found" errors
- Make sure `.htaccess` is in place
- Check Apache mod_rewrite is enabled

### Email not sending
- The checkout uses PHP `mail()` function
- For local testing, emails won't work without mail server
- Check the orders in phpMyAdmin instead

---

## Customization

### Change Site Name/URL
Edit `config.php`:
```php
define('SITE_NAME', 'Your Site Name');
define('SITE_URL', 'http://localhost/elitebbs');
```

### Add More Products
Add manually in phpMyAdmin or modify `seed.php`

### Change Email for Orders
Edit `config.php`:
```php
define('EMAIL_TO', 'your-email@example.com');
```

---

## Next Steps

1. Copy all images from `wp-content/uploads/` to `assets/images/`
2. Test the site at http://localhost/elitebbs/
3. Add real products via phpMyAdmin or update seed.php
4. Once working locally, deploy to your live server