<?php
/**
 * Shared site footer — include before </body>
 */
?>
<footer id="footer" class="footer-wrapper">

    <div class="footer-widgets footer footer-1">
        <div class="row dark large-columns-3 mb-0" style="max-width:1200px;margin:0 auto;padding:0 20px;">
            <div class="col pb-0 widget woocommerce widget_products">
                <span class="widget-title">Latest</span>
                <div class="is-divider small"></div>
                <ul class="product_list_widget">
                    <?php foreach (array_slice(get_all_products(), 0, 4) as $fp):
                        $fi   = json_decode($fp['images'] ?? '[]', true);
                        $fimg = !empty($fi[0]) ? $fi[0] : asset_url('images/placeholder.png');
                        $fpi  = get_display_price($fp);
                    ?>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/product/<?php echo $fp['slug']; ?>">
                            <img src="<?php echo htmlspecialchars($fimg); ?>" alt="<?php echo htmlspecialchars($fp['name']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                            <span class="product-title"><?php echo htmlspecialchars($fp['name']); ?></span>
                        </a>
                        <span class="woocommerce-Price-amount amount">$<?php echo number_format($fpi['price'], 2); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col pb-0 widget block_widget footer-about">
                <span class="widget-title">About us</span>
                <div class="is-divider small"></div>
                <p>Welcome to Elite BBS Wheels, your premier boutique destination for genuine BBS forged and performance wheels in America. We specialize in hand-selecting iconic, lightweight, and timeless BBS rims that blend German motorsport heritage with street-dominating style.</p>
            </div>
            <div class="col pb-0 widget widget_text footer-contact">
                <span class="widget-title">Contact us</span>
                <div class="is-divider small"></div>
                <p><a href="mailto:info@elitebbswheels.store">info@elitebbswheels.store</a></p>
                <p><a href="tel:+16177082284">+1(617)708-2284</a></p>
                <p>20802 Highland Knolls Drive<br>Katy, TX 77450, USA</p>
            </div>
        </div>
    </div>

    <div class="footer-widgets footer footer-2 dark">
        <div class="row dark large-columns-3 mb-0" style="max-width:1200px;margin:0 auto;padding:0 20px;">
            <div class="col pb-0 widget widget_nav_menu">
                <span class="widget-title">Legal Policy</span>
                <div class="is-divider small"></div>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/refund_returns">Refund and Returns Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/privacy-policy">Privacy Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/terms-conditions">Terms &amp; Conditions</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/shipping-policy">Shipping Policy</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/faq">FAQ</a></li>
                </ul>
            </div>
            <div class="col pb-0 widget">
                <span class="widget-title">Quick Links</span>
                <div class="is-divider small"></div>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/shop">Shop</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/testemonials">Reviews</a></li>
                </ul>
            </div>
            <div class="col pb-0 widget footer-contact">
                <span class="widget-title">Contact us</span>
                <div class="is-divider small"></div>
                <p><a href="mailto:info@elitebbswheels.store">info@elitebbswheels.store</a></p>
                <p><a href="tel:+16177082284">+1(617)708-2284</a></p>
                <p>Monday - Friday, 9:00 AM - 5:00 PM</p>
            </div>
        </div>
    </div>

    <div class="absolute-footer dark medium-text-center text-center">
        <div class="container clearfix">
            <div class="footer-secondary pull-right">
                <div class="payment-icons inline-block">
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349221.png" alt="Visa" style="height:24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349228.png" alt="Mastercard" style="height:24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/349/349230.png" alt="Amex" style="height:24px;"></div>
                    <div class="payment-icon"><img src="https://cdn-icons-png.flaticon.com/128/888/888870.png" alt="Apple Pay" style="height:24px;"></div>
                </div>
            </div>
            <div class="footer-primary pull-left">
                <div class="links footer-nav uppercase" style="display:flex;justify-content:center;gap:20px;margin-bottom:15px;flex-wrap:wrap;">
                    <a href="<?php echo SITE_URL; ?>/refund_returns">Refund Policy</a>
                    <a href="<?php echo SITE_URL; ?>/privacy-policy">Privacy Policy</a>
                    <a href="<?php echo SITE_URL; ?>/terms-conditions">Terms &amp; Conditions</a>
                    <a href="<?php echo SITE_URL; ?>/shipping-policy">Shipping Policy</a>
                    <a href="<?php echo SITE_URL; ?>/faq">FAQ</a>
                </div>
                <div class="copyright-footer">
                    Copyright <?php echo date('Y'); ?> &copy; <strong>ELITE BBS RIMS</strong>
                </div>
            </div>
        </div>
    </div>

</footer>

<a href="#top" class="back-to-top button icon invert plain fixed bottom z-1 is-outline hide-for-medium circle" id="top-link" aria-label="Go to top">
    <i class="icon-angle-up"></i>
</a>

</div><!-- #wrapper -->

<script src="<?php echo SITE_URL; ?>/wp-content/themes/flatsome/assets/js/flatsomed02f.js"></script>
<script>var siteUrl = '<?php echo SITE_URL; ?>';</script>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    var btt = document.getElementById('top-link');
    if (btt) {
        window.addEventListener('scroll', function() {
            btt.classList.toggle('show', window.scrollY > 500);
        });
    }
});
</script>
