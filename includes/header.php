<?php
/**
 * Shared site header — include after $page is set
 */
$_nav = $page ?? '';
$_search_val = isset($search) ? htmlspecialchars($search) : '';
?>
<style>
/* ── Site logo ── */
.site-logo-link {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none !important;
    user-select: none;
}
.logo-mark {
    position: relative;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
}
.logo-mark::before,
.logo-mark::after {
    content: '';
    position: absolute;
    border-radius: 50%;
}
/* Outer ring */
.logo-mark::before {
    inset: 0;
    border: 2.5px solid rgba(255,255,255,0.55);
}
/* Inner filled circle */
.logo-mark::after {
    inset: 6px;
    background: linear-gradient(135deg, #008cb2, #00d4ff);
    box-shadow: 0 0 10px rgba(0,180,224,0.5);
}
.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1;
    gap: 2px;
}
.logo-elite {
    font-family: 'Barlow', 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 5px;
    color: rgba(255,255,255,0.55);
    text-transform: uppercase;
}
.logo-bbs {
    font-family: 'Barlow', 'Montserrat', sans-serif;
    font-size: 24px;
    font-weight: 900;
    letter-spacing: 2px;
    color: #fff;
    text-transform: uppercase;
    line-height: 0.95;
}
.logo-sub {
    font-family: 'Barlow', 'Montserrat', sans-serif;
    font-size: 8px;
    font-weight: 600;
    letter-spacing: 3.5px;
    color: #008cb2;
    text-transform: uppercase;
    margin-top: 3px;
}
#logo { width: auto !important; }
#logo img { display: none; }

/* ── Mobile hamburger ── */
.mobile-menu-wrapper { display: none; }
.hamburger-btn {
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    width: 44px; height: 44px; background: #008cb2; border-radius: 6px;
    cursor: pointer; gap: 5px; padding: 10px; border: none;
}
.hamburger-btn span { width: 22px; height: 2px; background: #fff; border-radius: 2px; transition: all 0.3s; display: block; }
.hamburger-btn.is-open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger-btn.is-open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
.hamburger-btn.is-open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── Mobile drawer ── */
#main-menu {
    position: fixed;
    top: 0; left: 0;
    width: 280px;
    height: 100%;
    background: #111318;
    z-index: 99999;
    transform: translateX(-100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    padding: 0;
    box-shadow: 4px 0 30px rgba(0,0,0,0.5);
}
#main-menu.is-open { transform: translateX(0); }

#mobile-menu-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 99998;
    backdrop-filter: blur(2px);
}
#mobile-menu-overlay.is-open { display: block; }

.mobile-menu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.mobile-menu-logo {
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 2px;
    color: #fff;
    text-transform: uppercase;
}
.mobile-menu-close {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.07);
    border: none; border-radius: 6px; cursor: pointer;
    color: #fff; font-size: 20px; line-height: 1;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.2s;
}
.mobile-menu-close:hover { background: rgba(255,255,255,0.14); }

.mobile-menu-search {
    padding: 14px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.mobile-menu-search form {
    display: flex;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    overflow: hidden;
}
.mobile-menu-search input {
    flex: 1; border: none; background: transparent;
    padding: 10px 14px; color: #fff; font-size: 14px; outline: none;
}
.mobile-menu-search input::placeholder { color: rgba(255,255,255,0.35); }
.mobile-menu-search button {
    border: none; background: transparent; color: rgba(255,255,255,0.5);
    padding: 10px 14px; cursor: pointer; font-size: 16px;
}

.mobile-menu-nav { padding: 8px 0; }
.mobile-menu-nav a {
    display: block;
    padding: 13px 20px;
    color: rgba(255,255,255,0.8);
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: color 0.2s, background 0.2s, border-color 0.2s;
}
.mobile-menu-nav a:hover,
.mobile-menu-nav a.active {
    color: #fff;
    background: rgba(0,140,178,0.12);
    border-left-color: #008cb2;
}

.mobile-menu-footer {
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,0.07);
    margin-top: auto;
}
.mobile-menu-footer a {
    display: block;
    padding: 12px 16px;
    background: linear-gradient(135deg, #008cb2, #00b4e0);
    color: #fff;
    font-family: 'Barlow', sans-serif;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-decoration: none;
    border-radius: 8px;
    text-align: center;
}

@media (max-width: 849px) {
    .mobile-menu-wrapper { display: flex !important; margin-left: 10px; }
    .hide-for-medium { display: none !important; }
}
</style>
<header id="header" class="header header-full-width has-sticky sticky-jump">
    <div class="header-wrapper">
        <div id="masthead" class="header-main nav-dark">
            <div class="header-inner flex-row container logo-left medium-logo-center" role="navigation">

                <div id="logo" class="flex-col logo">
                    <a href="<?php echo SITE_URL; ?>/" title="<?php echo htmlspecialchars(SITE_NAME); ?>" rel="home" class="site-logo-link">
                        <span class="logo-mark" aria-hidden="true"></span>
                        <span class="logo-text">
                            <span class="logo-elite">ELITE</span><span class="logo-bbs">BBS</span>
                            <span class="logo-sub">WHEELS &amp; RIMS</span>
                        </span>
                    </a>
                </div>

                <div class="mobile-menu-wrapper show-for-medium" style="display:none;align-items:center;justify-content:center;">
                    <a href="#" data-open="#main-menu" class="hamburger-btn" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>

                <div class="flex-col hide-for-medium flex-left flex-grow">
                    <ul class="header-nav header-nav-main nav nav-left nav-uppercase">
                        <li class="header-search header-search-dropdown has-icon has-dropdown menu-item-has-children">
                            <a href="#" aria-label="Search" class="is-small"><i class="icon-search"></i></a>
                            <ul class="nav-dropdown nav-dropdown-default dark dropdown-uppercase">
                                <li class="header-search-form search-form html relative has-icon">
                                    <div class="header-search-form-wrapper">
                                        <form role="search" method="get" action="<?php echo SITE_URL; ?>/shop" class="searchform">
                                            <div class="flex-row relative">
                                                <div class="flex-col flex-grow">
                                                    <input type="search" class="search-field mb-0" placeholder="Search..." value="<?php echo $_search_val; ?>" name="search">
                                                </div>
                                                <div class="flex-col">
                                                    <button type="submit" class="ux-search-submit submit-button secondary button icon mb-0">
                                                        <i class="icon-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li><a href="<?php echo SITE_URL; ?>/"               class="nav-top-link<?php echo $_nav==='home'         ? ' active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/shop"           class="nav-top-link<?php echo $_nav==='shop'         ? ' active' : ''; ?>">Shop</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blog"           class="nav-top-link<?php echo $_nav==='blog'         ? ' active' : ''; ?>">Blog</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about"          class="nav-top-link<?php echo $_nav==='about'        ? ' active' : ''; ?>">About</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact"        class="nav-top-link<?php echo $_nav==='contact'      ? ' active' : ''; ?>">Contact</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/testemonials"   class="nav-top-link<?php echo $_nav==='testemonials' ? ' active' : ''; ?>">Reviews</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/refund_returns" class="nav-top-link<?php echo $_nav==='refund_returns' ? ' active' : ''; ?>">Returns</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/faq"            class="nav-top-link<?php echo $_nav==='faq'          ? ' active' : ''; ?>">FAQ</a></li>
                    </ul>
                </div>

                <div class="flex-col hide-for-medium flex-right">
                    <ul class="header-nav header-nav-main nav nav-right nav-uppercase">
                        <li class="account-item has-icon">
                            <a href="<?php echo SITE_URL; ?>/my-account" class="nav-top-link">
                                <span>Login</span>
                            </a>
                        </li>
                        <li class="header-divider"></li>
                        <li class="cart-item has-icon has-dropdown">
                            <a href="<?php echo SITE_URL; ?>/cart" class="header-cart-link is-small" title="Cart">
                                <span class="header-cart-title">Cart / <span class="cart-price">$<span id="cart-total">0.00</span></span></span>
                                <span class="cart-icon image-icon"><strong id="cart-count">0</strong></span>
                            </a>
                            <ul class="nav-dropdown nav-dropdown-default dark dropdown-uppercase">
                                <li class="html widget_shopping_cart">
                                    <div class="widget_shopping_cart_content">
                                        <div class="ux-mini-cart-empty flex flex-row-col text-center pt pb">
                                            <p>No products in the cart.</p>
                                            <p><a class="button primary wc-backward" href="<?php echo SITE_URL; ?>/shop">Return to shop</a></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="flex-col show-for-medium flex-right">
                    <ul class="mobile-nav nav nav-right">
                        <li class="cart-item has-icon">
                            <a href="<?php echo SITE_URL; ?>/cart" class="header-cart-link is-small">
                                <span class="cart-icon image-icon"><strong id="cart-count-mobile">0</strong></span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="container"><div class="top-divider full-width"></div></div>
        </div>
        <div class="header-bg-container fill"><div class="header-bg-image fill"></div><div class="header-bg-color fill"></div></div>
    </div>
</header>

<div id="mobile-menu-overlay"></div>

<div id="main-menu">
    <div class="mobile-menu-header">
        <span class="mobile-menu-logo">Elite BBS Rims</span>
        <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Close menu">&times;</button>
    </div>

    <div class="mobile-menu-search">
        <form method="get" action="<?php echo SITE_URL; ?>/shop">
            <input type="search" name="search" placeholder="Search wheels...">
            <button type="submit"><i class="icon-search"></i></button>
        </form>
    </div>

    <nav class="mobile-menu-nav">
        <a href="<?php echo SITE_URL; ?>/"               <?php echo $_nav==='home'          ? 'class="active"' : ''; ?>>Home</a>
        <a href="<?php echo SITE_URL; ?>/shop"           <?php echo $_nav==='shop'          ? 'class="active"' : ''; ?>>Shop</a>
        <a href="<?php echo SITE_URL; ?>/about"          <?php echo $_nav==='about'         ? 'class="active"' : ''; ?>>About Us</a>
        <a href="<?php echo SITE_URL; ?>/contact"        <?php echo $_nav==='contact'       ? 'class="active"' : ''; ?>>Contact</a>
        <a href="<?php echo SITE_URL; ?>/testemonials"   <?php echo $_nav==='testemonials'  ? 'class="active"' : ''; ?>>Reviews</a>
        <a href="<?php echo SITE_URL; ?>/refund_returns" <?php echo $_nav==='refund_returns'? 'class="active"' : ''; ?>>Returns</a>
        <a href="<?php echo SITE_URL; ?>/faq"            <?php echo $_nav==='faq'           ? 'class="active"' : ''; ?>>FAQ</a>
    </nav>

    <div class="mobile-menu-footer">
        <a href="<?php echo SITE_URL; ?>/shop">Shop All Wheels</a>
    </div>
</div>

<script>
(function() {
    var btn     = document.querySelector('[data-open="#main-menu"]');
    var menu    = document.getElementById('main-menu');
    var overlay = document.getElementById('mobile-menu-overlay');
    var closeBtn = document.getElementById('mobile-menu-close');

    function openMenu() {
        menu.classList.add('is-open');
        overlay.classList.add('is-open');
        if (btn) btn.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        menu.classList.remove('is-open');
        overlay.classList.remove('is-open');
        if (btn) btn.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (btn)      btn.addEventListener('click', function(e){ e.preventDefault(); openMenu(); });
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (overlay)  overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeMenu(); });
})();
</script>
