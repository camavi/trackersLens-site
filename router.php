<?php
// Run: php -S 127.0.0.1:8000 -t public router.php
$public = realpath(__DIR__.'/public');
if (realpath($_SERVER['DOCUMENT_ROOT'] ?? '') !== $public) {
    http_response_code(500);
    exit('Start with php artisan serve, or set the document root to public/.');
}
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$file = realpath($public.$path);
if ($path !== '/' && $file && str_starts_with($file, $public.'/') && is_file($file)) {
    return false;
}
require $public.'/index.php';
