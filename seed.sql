-- Elite BBS Rims - Complete Seed Data
-- Run this AFTER database.sql to inject all data
-- Usage: mysql -u root -p elitebbs_db < seed.sql

USE elitebbs_db;

-- Clear existing data (optional - comment out if you want to keep existing)
-- DELETE FROM order_items;
-- DELETE FROM orders;
-- DELETE FROM products;

-- Reset auto-increment counters (optional)
-- ALTER TABLE products AUTO_INCREMENT = 1;
-- ALTER TABLE orders AUTO_INCREMENT = 1;
-- ALTER TABLE order_items AUTO_INCREMENT = 1;

-- ============================================
-- PRODUCTS DATA
-- ============================================

INSERT INTO products (name, slug, short_description, description, price, sale_price, sku, stock, category, brand, size, finish, images, featured, status) VALUES
-- BBS Wheels
('BBS Super RS - Silver', 'bbs-super-rs-silver', 'Classic 3-piece forged wheel - Silver Finish', 'The BBS Super RS is a legendary 3-piece forged wheel, crafted with the same technology used in motorsport. Features iconic Y-spoke design with exceptional strength-to-weight ratio.', 450.00, NULL, 'BBS-SRS-SIL', 20, 'Wheels', 'BBS', '18x8.5', 'Silver', '["assets/images/products/bbs-super-rs.jpg"]', TRUE, 'active'),

('BBS Super RS - Black', 'bbs-super-rs-black', 'Classic 3-piece forged wheel - Matte Black', 'The BBS Super RS in stunning matte black finish. Same legendary quality and construction as the original, with a stealthy modern look.', 475.00, 425.00, 'BBS-SRS-BLK', 15, 'Wheels', 'BBS', '18x8.5', 'Matte Black', '["assets/images/products/bbs-super-rs-black.jpg"]', TRUE, 'active'),

('BBS LM - Gold', 'bbs-lm-gold', 'Legendary multi-piece forged wheel - Gold', 'The BBS LM (Lichtmetall) is an iconic motorsport-derived wheel. Features intricate mesh design with exceptional rigidity.', 550.00, NULL, 'BBS-LM-GOLD', 12, 'Wheels', 'BBS', '19x9.5', 'Gold', '["assets/images/products/bbs-lm.jpg"]', TRUE, 'active'),

('BBS FI-R - Gloss Black', 'bbs-fi-r-gloss-black', 'Flow-formed sport wheel - Gloss Black', 'The BBS FI-R features innovative flow-forming technology for lightweight strength. Multi-spoke design with aggressive fitment capability.', 380.00, NULL, 'BBS-FIR-BLK', 25, 'Wheels', 'BBS', '18x9', 'Gloss Black', '["assets/images/products/bbs-fi-r.jpg"]', FALSE, 'active'),

('BBS SR - Gunmetal', 'bbs-sr-gunmetal', 'Sport rim flow-formed - Gunmetal', 'The BBS SR combines flow-forming technology with sophisticated design. Lightweight and strong, perfect for modern performance vehicles.', 320.00, 290.00, 'BBS-SR-GM', 30, 'Wheels', 'BBS', '17x8', 'Gunmetal', '["assets/images/products/bbs-sr.jpg"]', FALSE, 'active'),

('BBS Super Racing - Diamond Black', 'bbs-super-racing-diamond-black', 'Track-ready multi-piece wheel', 'The BBS Super Racing is designed for serious track use. 3-piece construction allows for precise fitment adjustments.', 650.00, NULL, 'BBS-SR-DB', 8, 'Wheels', 'BBS', '18x10', 'Diamond Black', '["assets/images/products/bbs-super-racing.jpg"]', TRUE, 'active'),

-- Ultra Wheels
('123U SCORPION - Gloss Black', '123u-scorpion', 'Ultra Wheels 123U SCORPION GLOSS BLACK WITH DIAMOND CUT FACE', 'A259196 Ultra Wheels 123U SCORPION GLOSS BLACK WITH DIAMOND CUT FACE AND CLEAR COAT 20x9 8X170 18', 300.86, NULL, 'A259196', 10, 'Wheels', 'Ultra Wheels', '20x9', 'Gloss Black', '["assets/images/products/123u-scorpion.jpg"]', FALSE, 'active'),

-- SSR Wheels
('SSR Vienna Courage - Chrome', 'ssr-vienna-courage-chrome', 'SSR Vienna Courage Wheels 18x8.5 9.5 ET18-22 Chrome', 'SSR Vienna Courage wheels in stunning chrome finish. Perfect for luxury and performance vehicles.', 450.00, NULL, 'SSR-VC-CHR', 8, 'Wheels', 'SSR', '18x8.5', 'Chrome', '["assets/images/products/ssr-vienna.jpg"]', FALSE, 'active'),

('SSR Professor SP1', 'ssr-professor-sp1', 'SSR Professor SP1 Wheel 18x8.5 9.5 ET26 Hi Disk', 'The legendary SSR Professor SP1 - a timeless classic in the Japanese tuning community.', 520.00, 480.00, 'SSR-SP1', 6, 'Wheels', 'SSR', '18x8.5', 'Silver', '["assets/images/products/ssr-professor.jpg"]', TRUE, 'active'),

('SSR XRX - 4x114.3', 'ssr-xrx-4x114', 'SSR XRX Wheels Set 4x114.3', 'SSR XRX flow-formed wheels, perfect for Japanese imports with 4x114.3 bolt pattern.', 380.00, NULL, 'SSR-XRX-114', 12, 'Wheels', 'SSR', '17x7', 'Black', '["assets/images/products/ssr-xrx.jpg"]', FALSE, 'active'),

('SSR Koenig - Raw Silver', 'ssr-koenig', 'SSR Koenig Wheels 18x9.5 10.5 ET24 Raw Silver', 'SSR Koenig wheels in beautiful raw silver finish. Multi-piece construction for ultimate customizability.', 580.00, NULL, 'SSR-KOENIG', 5, 'Wheels', 'SSR', '18x9.5', 'Raw Silver', '["assets/images/products/ssr-koenig.jpg"]', FALSE, 'active'),

-- Work Wheels
('Work VSMX', 'work-vsmx', 'Work VSMX Wheels 18x8.5 ET18 18x9.5 ET22 A-Disk', 'Work VSMX multi-piece wheels. The ultimate choice for show and track.', 650.00, 599.00, 'WORK-VSMX', 4, 'Wheels', 'Work', '18x8.5/9.5', 'Black', '["assets/images/products/work-vsmx.jpg"]', TRUE, 'active'),

('Work Ewing Wheels Set', 'work-ewing', 'Work Ewing Wheels Set', 'Work Ewing - classic mesh design with modern engineering. Sold as a set of 4.', 1200.00, NULL, 'WORK-EWING', 3, 'Wheels', 'Work', '18x9.5', 'Silver', '["assets/images/products/work-ewing.jpg"]', FALSE, 'active'),

('Work Carving Head 40', 'work-carving-head-40', 'Work Carving Head 40 Wheels 17x8.5', 'Work Carving Head 40 - lightweight flow-formed design for performance.', 420.00, NULL, 'WORK-CH40', 10, 'Wheels', 'Work', '17x8.5', 'Black', '["assets/images/products/work-carving.jpg"]', FALSE, 'active'),

-- Advan Tires
('Advan Sport V105', 'advan-sport-v105', 'Advan Sport V105 High Performance Tire', 'Advan Sport V105 - Ultra high performance summer tire. Sold individually.', 280.00, 249.00, 'ADVAN-V105', 50, 'Tires', 'Advan', '225/45R17', NULL, '["assets/images/products/advan-v105.jpg"]', FALSE, 'active'),

('Advan Sport V107', 'advan-sport-v107', 'Advan Sport V107 Performance Tire', 'Advan Sport V107 - Next generation ultra high performance tire.', 320.00, NULL, 'ADVAN-V107', 40, 'Tires', 'Advan', '235/40R18', NULL, '["assets/images/products/advan-v107.jpg"]', TRUE, 'active'),

('Advan Neova AD09', 'advan-neova-ad09', 'Advan Neova AD09 Extreme Performance Tire', 'Advan Neova AD09 - Ultimate grip for track and aggressive street use.', 350.00, NULL, 'ADVAN-AD09', 30, 'Tires', 'Advan', '265/35R18', NULL, '["assets/images/products/advan-ad09.jpg"]', FALSE, 'active'),

-- Drive Wheels
('Drive 36D-II Double Wheels', 'drive-36d-ii', 'Drive 36D-II Double Wheels - Front and Rear Set', 'Drive 36D-II complete set with front and rear wheels. Perfect for drift and track.', 850.00, 780.00, 'DRIVE-36D', 5, 'Wheels', 'Drive', '18x9.5', 'Black', '["assets/images/products/drive-36d.jpg"]', TRUE, 'active'),

('Drive G45 CS', 'drive-g45-cs', 'Drive G45 CS Ceramic Series', 'Drive G45 CS (Ceramic Series) - Premium multi-piece wheels with ceramic finish.', 680.00, NULL, 'DRIVE-G45', 6, 'Wheels', 'Drive', '19x10', 'Ceramic', '["assets/images/products/drive-g45.jpg"]', FALSE, 'active'),

('Drive Helix 68D SS', 'drive-helix-68d-ss', 'Drive Helix 68D SS - Surface Silver', 'Drive Helix 68D in stunning surface silver finish. Multi-spoke design.', 550.00, NULL, 'DRIVE-68D', 8, 'Wheels', 'Drive', '18x9', 'Silver', '["assets/images/products/drive-helix.jpg"]', FALSE, 'active'),

('Drive 40V 3K Black', 'drive-40v-3k-black', 'Drive 40V 3K Black Partial Wheelset', 'Drive 40V 3K in black finish. Sold as partial set for rear fitment.', 420.00, NULL, 'DRIVE-40V-BLK', 4, 'Wheels', 'Drive', '18x10', 'Black', '["assets/images/products/drive-40v.jpg"]', FALSE, 'active'),

-- Volk Racing
('Volk Racing TE37 SL', 'volk-te37-sl', 'Volk Racing TE37 SL Saga Wheel 19x9.5 ET42 A-Disk', 'The legendary TE37 SL (Super Light) - the benchmark for forged wheels.', 750.00, NULL, 'VOLK-TE37', 6, 'Wheels', 'Volk Racing', '19x9.5', 'Black', '["assets/images/products/volk-te37.jpg"]', TRUE, 'active'),

-- Weds Kranze
('Weds Kranze Cerberus', 'weds-kranze-cerberus', 'Weds Kranze Cerberus Wheels 18x9.5 ET12-16 Ceramic Silver', 'Weds Kranze Cerberus - aggressive multi-spoke design in ceramic silver.', 620.00, 580.00, 'KRANZE-CERB', 4, 'Wheels', 'Weds Kranze', '18x9.5', 'Ceramic Silver', '["assets/images/products/kranze-cerberus.jpg"]', FALSE, 'active'),

('Weds Kranze Vishunu', 'weds-kranze-vishunu', 'Weds Kranze Vishunu Wheels 18x9.5 ET28-32 Raw Chrome', 'Weds Kranze Vishunu in raw chrome finish. Bold and aggressive design.', 680.00, NULL, 'KRANZE-VISH', 3, 'Wheels', 'Weds Kranze', '18x9.5', 'Raw Chrome', '["assets/images/products/kranze-vishunu.jpg"]', FALSE, 'active'),

-- Leon Hardiritt
('Leon Hardiritt Waffe', 'leon-hardiritt-waffe', 'Leon Hardiritt Waffe Wheels 18x9.5 ET32 Hi-Disk Chrome', 'Leon Hardiritt Waffe - classic German engineering meets custom styling.', 850.00, NULL, 'LEON-WAFF', 2, 'Wheels', 'Leon Hardiritt', '18x9.5', 'Chrome', '["assets/images/products/leon-waffe.jpg"]', FALSE, 'active'),

-- Enkei
('Enkei RPF1', 'enkei-rpf1', 'Enkei RPF1 - Classic Single Piece', 'Enkei RPF1 - the legendary affordable performance wheel. Lightweight and strong.', 220.00, 199.00, 'ENKEI-RPF1', 25, 'Wheels', 'Enkei', '17x8', 'Black', '["assets/images/products/enkei-rpf1.jpg"]', FALSE, 'active'),

-- Misc Products
('TT Rim Brake Bundle', 'tt-rim-brake-bundle', 'TT Rim with Brake Bundle Package', 'Complete TT rim package including brake components. Perfect for track setup.', 450.00, NULL, 'TT-BUNDLE', 10, 'Packages', 'Various', '18x9', 'Black', '["assets/images/products/tt-rim.jpg"]', TRUE, 'active'),

('Velo TT Rim', 'velo-tt-rim', 'Velo TT Rim - Lightweight Track Rim', 'Velo TT rim - designed for time attack and track use. Extremely lightweight.', 380.00, NULL, 'VELO-TT', 8, 'Wheels', 'Velo', '17x9', 'Silver', '["assets/images/products/velo-tt.jpg"]', FALSE, 'active'),

('Drive 65D Disc Brake', 'drive-65d-disc-brake', 'Drive 65D Disc Brake Set', 'Drive 65D performance disc brake kit. Compatible with various wheel setups.', 280.00, NULL, 'DRIVE-65D', 15, 'Brakes', 'Drive', NULL, NULL, '["assets/images/products/drive-brake.jpg"]', FALSE, 'active'),

-- Raceline
('Raceline 131B Evo - Satin Black', 'raceline-131b-evo', 'Raceline 131B Evo Satin Black Wheels', 'Raceline 131B Evo in satin black finish. Modern multi-spoke design.', 260.00, 230.00, 'RACELINE-131B', 18, 'Wheels', 'Raceline', '18x8', 'Satin Black', '["assets/images/products/raceline-131b.jpg"]', FALSE, 'active'),

('Raceline 141S Mystique', 'raceline-141s-mystique', 'Raceline 141S Mystique Silver Wheels', 'Raceline 141S Mystique in classic silver. Timeless design.', 240.00, NULL, 'RACELINE-141S', 20, 'Wheels', 'Raceline', '17x7.5', 'Silver', '["assets/images/products/raceline-141s.jpg"]', FALSE, 'active');

-- ============================================
-- SAMPLE ORDERS DATA
-- ============================================

INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, billing_address, shipping_address, vehicle_make, vehicle_model, vehicle_year, notes, items_json, subtotal, shipping_cost, total, payment_method, status, created_at) VALUES
('ORD-2026-00001', 'John Smith', 'john.smith@email.com', '(555) 123-4567', '123 Main St, Los Angeles, CA 90001', '123 Main St, Los Angeles, CA 90001', 'BMW', 'M3', '2024', 'Please call before delivery', '[{"product_id":1,"name":"BBS Super RS - Silver","sku":"BBS-SRS-SIL","quantity":4,"unit_price":450.00,"total":1800.00}]', 1800.00, 150.00, 1950.00, 'email_transfer', 'completed', '2026-04-10 14:30:00'),

('ORD-2026-00002', 'Sarah Johnson', 'sarah.j@email.com', '(555) 987-6543', '456 Oak Ave, San Francisco, CA 94102', '456 Oak Ave, San Francisco, CA 94102', 'Mercedes-Benz', 'C63 AMG', '2023', 'Gift wrapping requested', '[{"product_id":3,"name":"BBS LM - Gold","sku":"BBS-LM-GOLD","quantity":4,"unit_price":550.00,"total":2200.00}]', 2200.00, 200.00, 2400.00, 'card', 'shipped', '2026-04-12 09:15:00'),

('ORD-2026-00003', 'Mike Chen', 'mike.chen@email.com', '(555) 456-7890', '789 Pine Rd, Las Vegas, NV 89101', '789 Pine Rd, Las Vegas, NV 89101', 'Toyota', 'Supra', '2024', 'Track use - prioritize strength', '[{"product_id":6,"name":"BBS Super Racing - Diamond Black","sku":"BBS-SR-DB","quantity":4,"unit_price":650.00,"total":2600.00}]', 2600.00, 175.00, 2775.00, 'email_transfer', 'processing', '2026-04-14 16:45:00'),

('ORD-2026-00004', 'Emily Davis', 'emily.d@email.com', '(555) 321-0987', '321 Elm St, Phoenix, AZ 85001', '321 Elm St, Phoenix, AZ 85001', 'Honda', 'Civic Type R', '2025', '', '[{"product_id":5,"name":"BBS SR - Gunmetal","sku":"BBS-SR-GM","quantity":4,"unit_price":290.00,"total":1160.00},{"product_id":15,"name":"Advan Sport V105","sku":"ADVAN-V105","quantity":4,"unit_price":249.00,"total":996.00}]', 2156.00, 125.00, 2281.00, 'card', 'pending', '2026-04-15 11:20:00');

-- ============================================
-- SAMPLE ORDER ITEMS DATA
-- ============================================

INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price) VALUES
(1, 1, 'BBS Super RS - Silver', 'BBS-SRS-SIL', 4, 450.00, 1800.00),
(2, 3, 'BBS LM - Gold', 'BBS-LM-GOLD', 4, 550.00, 2200.00),
(3, 6, 'BBS Super Racing - Diamond Black', 'BBS-SR-DB', 4, 650.00, 2600.00),
(4, 5, 'BBS SR - Gunmetal', 'BBS-SR-GM', 4, 290.00, 1160.00),
(4, 15, 'Advan Sport V105', 'ADVAN-V105', 4, 249.00, 996.00);

-- ============================================
-- CONFIRMATION MESSAGE
-- ============================================

SELECT 'Seed data inserted successfully!' AS status;
SELECT CONCAT('Total products: ', COUNT(*)) AS summary FROM products;
SELECT CONCAT('Total orders: ', COUNT(*)) AS summary FROM orders;
SELECT CONCAT('Total order items: ', COUNT(*)) AS summary FROM order_items;
