<?php
require_once '../config.php';
require_once INCLUDES_PATH . '/db.php';

session_destroy();
header('Location: ' . site_url('admin/login.php'));
exit;