<?php
/**
 * Admin panel header — include at top of every admin page.
 * Expects $admin_page (string) to highlight the active nav item.
 */
$_ap = $admin_page ?? '';
$_admin = isset($admin) ? $admin : [];
$_admin_name = htmlspecialchars($_admin['full_name'] ?? $_admin['username'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo isset($page_title) ? $page_title . ' — ' : ''; ?>Admin — <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: 'Lato', sans-serif;
            background: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════
           TOP BAR
        ══════════════════════════════════════ */
        .adm-topbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: 60px;
            background: #0d0f13;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex; align-items: center;
            padding: 0 24px;
            z-index: 1000;
            gap: 16px;
        }
        .adm-topbar-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }
        .adm-logo-mark {
            position: relative; width: 30px; height: 30px;
        }
        .adm-logo-mark::before {
            content: ''; position: absolute; inset: 0;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.4);
        }
        .adm-logo-mark::after {
            content: ''; position: absolute; inset: 5px;
            border-radius: 50%;
            background: linear-gradient(135deg, #008cb2, #00d4ff);
            box-shadow: 0 0 8px rgba(0,180,224,0.5);
        }
        .adm-logo-text { display: flex; flex-direction: column; line-height: 1; gap: 1px; }
        .adm-logo-elite { font-family: 'Barlow', sans-serif; font-size: 8px; font-weight: 600; letter-spacing: 4px; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .adm-logo-bbs   { font-family: 'Barlow', sans-serif; font-size: 18px; font-weight: 900; letter-spacing: 2px; color: #fff; text-transform: uppercase; line-height: 1; }

        .adm-topbar-divider {
            width: 1px; height: 28px;
            background: rgba(255,255,255,0.1);
            margin: 0 8px;
        }
        .adm-topbar-badge {
            font-family: 'Barlow', sans-serif;
            font-size: 10px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            color: #008cb2;
            background: rgba(0,140,178,0.12);
            border: 1px solid rgba(0,140,178,0.25);
            padding: 3px 10px; border-radius: 3px;
        }

        .adm-topbar-spacer { flex: 1; }

        .adm-topbar-right {
            display: flex; align-items: center; gap: 16px;
        }
        .adm-admin-info {
            display: flex; align-items: center; gap: 8px;
        }
        .adm-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Barlow', sans-serif;
            font-size: 13px; font-weight: 900; color: #fff;
        }
        .adm-admin-name {
            font-family: 'Barlow', sans-serif;
            font-size: 13px; font-weight: 700;
            color: rgba(255,255,255,0.7);
        }
        .adm-logout-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 6px;
            font-family: 'Barlow', sans-serif;
            font-size: 11px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: all 0.2s;
        }
        .adm-logout-btn:hover {
            background: rgba(229,62,62,0.15);
            border-color: rgba(229,62,62,0.35);
            color: #fca5a5;
        }

        .adm-view-site {
            display: flex; align-items: center; gap: 6px;
            font-family: 'Barlow', sans-serif;
            font-size: 11px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color 0.2s;
        }
        .adm-view-site:hover { color: #008cb2; }

        /* ══════════════════════════════════════
           LAYOUT
        ══════════════════════════════════════ */
        .adm-layout {
            display: flex;
            flex: 1;
            padding-top: 60px;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .adm-sidebar {
            width: 220px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid #e8ecf0;
            position: fixed;
            top: 60px; left: 0; bottom: 0;
            overflow-y: auto;
            display: flex; flex-direction: column;
        }
        .adm-sidebar-section {
            padding: 20px 16px 8px;
        }
        .adm-sidebar-label {
            font-family: 'Barlow', sans-serif;
            font-size: 10px; font-weight: 800;
            letter-spacing: 2px; text-transform: uppercase;
            color: #bbb;
            margin-bottom: 6px;
            padding: 0 8px;
        }
        .adm-nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px; font-weight: 700;
            color: #666;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .adm-nav-link:hover {
            background: #f4f6f8;
            color: #1a1a1a;
        }
        .adm-nav-link.active {
            background: rgba(0,140,178,0.08);
            color: #008cb2;
            border-left: 3px solid #008cb2;
            padding-left: 9px;
        }
        .adm-nav-icon {
            width: 20px; text-align: center;
            font-size: 14px; flex-shrink: 0;
        }
        .adm-sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid #f0f2f5;
        }
        .adm-sidebar-version {
            font-size: 11px; color: #ccc;
            font-family: 'Barlow', sans-serif;
            text-align: center;
        }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        .adm-main {
            flex: 1;
            margin-left: 220px;
            min-height: calc(100vh - 60px);
            display: flex; flex-direction: column;
        }
        .adm-content {
            flex: 1;
            padding: 32px;
        }

        /* Page title bar */
        .adm-page-header {
            display: flex; align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap; gap: 12px;
        }
        .adm-page-title {
            font-family: 'Barlow', sans-serif;
            font-size: 24px; font-weight: 900;
            color: #1a1a1a; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .adm-page-sub {
            font-size: 13px; color: #888;
            margin-top: 3px;
        }

        /* Alert styles (reusable across pages) */
        .adm-alert {
            padding: 13px 18px;
            border-radius: 8px;
            font-size: 13px; font-weight: 600;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .adm-alert.success { background: #e8f8f0; border: 1px solid #a3d9b8; color: #1d6b3e; }
        .adm-alert.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }

        /* Shared card */
        .adm-card {
            background: #fff;
            border: 1px solid #e8ecf0;
            border-radius: 12px;
            overflow: hidden;
        }
        .adm-card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #f0f2f5;
            display: flex; align-items: center; justify-content: space-between;
        }
        .adm-card-title {
            font-family: 'Barlow', sans-serif;
            font-size: 13px; font-weight: 900;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: #1a1a1a;
        }
        .adm-card-body { padding: 24px; }

        /* Shared table */
        .adm-table { width: 100%; border-collapse: collapse; }
        .adm-table thead tr { background: #f8f9fb; }
        .adm-table th {
            padding: 11px 16px; text-align: left;
            font-family: 'Barlow', sans-serif;
            font-size: 10px; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase; color: #999;
            border-bottom: 1px solid #eef0f3;
        }
        .adm-table td {
            padding: 13px 16px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 13px; color: #444;
        }
        .adm-table tbody tr:last-child td { border-bottom: none; }
        .adm-table tbody tr:hover { background: #fafbfc; }

        /* Status badges */
        .adm-badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-family: 'Barlow', sans-serif;
            font-size: 10px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .adm-badge.pending    { background: #fffbe6; color: #92400e; border: 1px solid #fde68a; }
        .adm-badge.processing { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .adm-badge.shipped    { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .adm-badge.completed  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .adm-badge.cancelled  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Shared button */
        .adm-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            border-radius: 7px;
            font-family: 'Barlow', sans-serif;
            font-size: 12px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
            text-decoration: none; cursor: pointer;
            border: none; transition: all 0.2s;
        }
        .adm-btn.primary {
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff;
        }
        .adm-btn.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,140,178,0.3); }
        .adm-btn.ghost {
            background: transparent; color: #666;
            border: 1.5px solid #dde1e7;
        }
        .adm-btn.ghost:hover { border-color: #008cb2; color: #008cb2; }
        .adm-btn.danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .adm-btn.danger:hover { background: #fee2e2; }

        /* Form styles */
        .adm-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 4px; }
        .adm-form-group { margin-bottom: 18px; }
        .adm-form-group label {
            display: block;
            font-family: 'Barlow', sans-serif;
            font-size: 11px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase;
            color: #888; margin-bottom: 7px;
        }
        .adm-form-group input,
        .adm-form-group select,
        .adm-form-group textarea {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid #dde1e7; border-radius: 7px;
            font-family: 'Lato', sans-serif; font-size: 14px;
            color: #1a1a1a; background: #fff; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .adm-form-group input:focus,
        .adm-form-group select:focus,
        .adm-form-group textarea:focus {
            border-color: #008cb2;
            box-shadow: 0 0 0 3px rgba(0,140,178,0.1);
        }
        .adm-form-group textarea { resize: vertical; }

        @media (max-width: 900px) {
            .adm-sidebar { display: none; }
            .adm-main { margin-left: 0; }
            .adm-form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- Top bar -->
<div class="adm-topbar">
    <a href="<?php echo site_url('admin/'); ?>" class="adm-topbar-logo">
        <span class="adm-logo-mark"></span>
        <span class="adm-logo-text">
            <span class="adm-logo-elite">Elite</span>
            <span class="adm-logo-bbs">BBS</span>
        </span>
    </a>
    <div class="adm-topbar-divider"></div>
    <span class="adm-topbar-badge">Admin</span>

    <div class="adm-topbar-spacer"></div>

    <div class="adm-topbar-right">
        <a href="<?php echo site_url(); ?>" class="adm-view-site" target="_blank">
            &#8599; View Site
        </a>
        <div class="adm-admin-info">
            <div class="adm-avatar"><?php echo strtoupper(substr($_admin_name, 0, 1)); ?></div>
            <span class="adm-admin-name"><?php echo $_admin_name; ?></span>
        </div>
        <a href="<?php echo site_url('admin/logout.php'); ?>" class="adm-logout-btn">
            &#10005; Logout
        </a>
    </div>
</div>

<!-- Layout wrapper -->
<div class="adm-layout">

    <!-- Sidebar -->
    <aside class="adm-sidebar">
        <div class="adm-sidebar-section">
            <div class="adm-sidebar-label">Main</div>
            <a href="<?php echo site_url('admin/'); ?>" class="adm-nav-link <?php echo $_ap === 'dashboard' ? 'active' : ''; ?>">
                <span class="adm-nav-icon">&#9632;</span> Dashboard
            </a>
            <a href="<?php echo site_url('admin/orders.php'); ?>" class="adm-nav-link <?php echo $_ap === 'orders' ? 'active' : ''; ?>">
                <span class="adm-nav-icon">&#128220;</span> Orders
            </a>
            <a href="<?php echo site_url('admin/products.php'); ?>" class="adm-nav-link <?php echo $_ap === 'products' ? 'active' : ''; ?>">
                <span class="adm-nav-icon">&#9679;</span> Products
            </a>
        </div>
        <div class="adm-sidebar-section">
            <div class="adm-sidebar-label">Config</div>
            <a href="<?php echo site_url('admin/settings.php'); ?>" class="adm-nav-link <?php echo $_ap === 'settings' ? 'active' : ''; ?>">
                <span class="adm-nav-icon">&#9881;</span> Settings
            </a>
        </div>
        <div class="adm-sidebar-footer">
            <div class="adm-sidebar-version">Elite BBS &copy; <?php echo date('Y'); ?></div>
        </div>
    </aside>

    <!-- Main area -->
    <div class="adm-main">
        <div class="adm-content">
