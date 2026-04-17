<?php
/**
 * Minimal autoloader for PHPMailer (no Composer needed).
 */
spl_autoload_register(function ($class) {
    // PHPMailer namespace
    $prefix = 'PHPMailer\\PHPMailer\\';
    $base   = __DIR__ . '/phpmailer/phpmailer/src/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $base . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
