<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/functions.php';

/* Redirect already-logged-in admins */
if (isset($_SESSION['admin_id'])) {
    header('Location: ' . site_url('admin/'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter your username and password.';
    } else {
        if (admin_login($username, $password)) {
            header('Location: ' . site_url('admin/'));
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login — <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d0f13;
            font-family: 'Lato', sans-serif;
            padding: 20px;
        }

        /* Background grid */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        /* Glow blob */
        body::after {
            content: '';
            position: fixed;
            top: -120px; left: 50%; transform: translateX(-50%);
            width: 600px; height: 400px;
            background: radial-gradient(ellipse, rgba(0,140,178,0.14) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-card {
            position: relative;
            width: 100%; max-width: 420px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 44px 40px 40px;
            backdrop-filter: blur(8px);
        }

        /* Logo mark */
        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 32px;
            text-decoration: none;
        }
        .logo-mark {
            position: relative;
            width: 36px; height: 36px; flex-shrink: 0;
        }
        .logo-mark::before {
            content: '';
            position: absolute; inset: 0;
            border-radius: 50%;
            border: 2.5px solid rgba(255,255,255,0.4);
        }
        .logo-mark::after {
            content: '';
            position: absolute; inset: 6px;
            border-radius: 50%;
            background: linear-gradient(135deg, #008cb2, #00d4ff);
            box-shadow: 0 0 12px rgba(0,180,224,0.5);
        }
        .logo-text-wrap { display: flex; flex-direction: column; line-height: 1; gap: 2px; }
        .logo-elite { font-family: 'Barlow', sans-serif; font-size: 10px; font-weight: 600; letter-spacing: 5px; color: rgba(255,255,255,0.45); text-transform: uppercase; }
        .logo-bbs   { font-family: 'Barlow', sans-serif; font-size: 22px; font-weight: 900; letter-spacing: 2px; color: #fff; text-transform: uppercase; line-height: 0.95; }

        .login-title {
            font-family: 'Barlow', sans-serif;
            font-size: 22px; font-weight: 900;
            color: #fff; text-align: center;
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: 13px; color: rgba(255,255,255,0.35);
            text-align: center; margin-bottom: 28px;
        }

        /* Error alert */
        .alert-error {
            background: rgba(229,62,62,0.12);
            border: 1px solid rgba(229,62,62,0.35);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-error::before { content: '⚠'; font-size: 14px; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-family: 'Barlow', sans-serif;
            font-size: 11px; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: #fff;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input::placeholder { color: rgba(255,255,255,0.2); }
        .form-group input:focus {
            border-color: #008cb2;
            box-shadow: 0 0 0 3px rgba(0,140,178,0.15);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #008cb2, #00b4e0);
            color: #fff;
            border: none; border-radius: 8px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px; font-weight: 900;
            letter-spacing: 2px; text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s;
            margin-top: 4px;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,140,178,0.4);
        }
        .login-btn:active { transform: translateY(0); }

        .card-footer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .back-link {
            font-family: 'Barlow', sans-serif;
            font-size: 12px; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #008cb2; }
        .powered {
            font-size: 11px; color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">

        <a href="<?php echo site_url(); ?>" class="login-logo">
            <span class="logo-mark"></span>
            <span class="logo-text-wrap">
                <span class="logo-elite">Elite</span>
                <span class="logo-bbs">BBS</span>
            </span>
        </a>

        <h1 class="login-title">Admin Panel</h1>
        <p class="login-sub">Sign in to manage your store</p>

        <?php if ($error): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       placeholder="Enter username" required autofocus
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Enter password" required>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>

        <div class="card-footer">
            <a href="<?php echo site_url(); ?>" class="back-link">&#8592; Back to Site</a>
            <span class="powered">Elite BBS Admin</span>
        </div>

    </div>
</body>
</html>
