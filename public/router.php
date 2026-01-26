<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|webp)$/', $uri)) {
    return false; 
}

require_once __DIR__ . '/index.php';