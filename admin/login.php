<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password.';
    } else {
        if (admin_login($username, $password)) {
            header('Location: ' . site_url('admin/index.php'));
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

require_once INCLUDES_PATH . '/header.php';
?>
<div class="login-container">
    <div class="login-box">
        <h1>Admin Login</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        
        <p class="login-help">Default: admin / admin123</p>
        <p class="back-link"><a href="<?= site_url() ?>">&larr; Back to Site</a></p>
    </div>
</div>

<style>
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
    padding: 20px;
}
.login-box {
    background: #252525;
    padding: 40px;
    border-radius: 8px;
    width: 100%;
    max-width: 400px;
    color: #fff;
}
.login-box h1 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
}
.login-box .form-group {
    margin-bottom: 20px;
}
.login-box label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}
.login-box input[type="text"],
.login-box input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #444;
    border-radius: 4px;
    background: #333;
    color: #fff;
    font-size: 14px;
}
.login-box input:focus {
    outline: none;
    border-color: #e63946;
}
.btn-block {
    width: 100%;
    padding: 14px;
    font-size: 16px;
}
.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
}
.alert-error {
    background: #ff4444;
    color: #fff;
}
.login-help {
    text-align: center;
    margin-top: 20px;
    color: #888;
    font-size: 13px;
}
.back-link {
    text-align: center;
    margin-top: 15px;
}
.back-link a {
    color: #e63946;
}
.back-link a:hover {
    text-decoration: underline;
}
</style>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>