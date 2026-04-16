# Elite BBS Rims - PHP Website

A custom PHP + MySQL e-commerce website for selling BBS wheels and related products.

## Setup Instructions

### 1. XAMPP Setup
1. Start **Apache** and **MySQL** in XAMPP Control Panel

### 2. Database Setup
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create a new database named `elitebbs_db`
3. Click **Import** and select `database.sql`
4. Click **Go** to import

### 3. Deploy Files
```
Copy ALL files to: C:\xampp\htdocs\elitebbs\
```

### 4. Seed Products (Optional)
- Visit: http://localhost/elitebbs/seed_full.php
- This adds 30+ sample products to the database

### 5. Configure (If needed)
Edit `config.php` if your MySQL has a password:
```php
define('DB_PASS', 'your_password');
```

### 6. Access Site
- Main site: http://localhost/elitebbs/
- Shop: http://localhost/elitebbs/shop
- Cart: http://localhost/elitebbs/cart
- Checkout: http://localhost/elitebbs/checkout
- Admin: http://localhost/elitebbs/admin/
- Admin login: admin / admin123

## File Structure

```
elitebbs/
├── config.php              - Database & site settings
├── database.sql            - MySQL schema
├── seed.php                - Basic product seed
├── seed_full.php           - Full product seed (30+ products)
├── .htaccess               - URL rewriting
├── index.php               - Homepage
├── shop/                   - Product listing
│   └── index.php
├── product/                - Product detail
│   └── index.php
├── cart/                   - Shopping cart
│   └── index.php
├── checkout/               - Checkout
│   ├── index.php
│   └── success/
│       └── index.php
├── about/                  - About page
├── contact/                - Contact form
├── faq/                    - FAQ page
├── refund_returns/         - Refund policy
├── terms-conditions/       - Terms
├── testemonials/           - Reviews
├── includes/               - PHP includes
│   ├── db.php              - Database connection
│   ├── functions.php       - Helper functions
│   ├── cart_functions.php  - Cart logic
│   ├── cart_ajax.php       - Cart AJAX handler
│   ├── header.php          - Header
│   ├── footer.php          - Footer
│   └── product_card.php    - Product card component
└── assets/                 - Static assets
    ├── css/
    │   └── style.css
    ├── js/
    │   └── main.js
    └── images/
        ├── placeholder.png
        ├── logo.png
        ├── bbs-hero.jpg
        └── about-wheel.jpg
```

## Features

- Product catalog with categories
- Shopping cart (session-based)
- Checkout with email notifications
- Vehicle fitment search
- Responsive design
- Contact form
- Customer reviews

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify database exists in phpMyAdmin
- Check credentials in config.php

### Page Not Found
- Ensure .htaccess is present
- Enable mod_rewrite in XAMPP

### Images Not Showing
- Copy images from wp-content to assets/images/

### Cart Not Working
- Check PHP sessions are enabled
- Clear browser cache

## For Production

1. Change `SITE_URL` in config.php to your live domain
2. Set `display_errors` to 0
3. Update `EMAIL_TO` to your actual email
4. Secure config.php (don't commit to git)

---

Built with PHP, MySQL, and XAMPP# wheels-website
