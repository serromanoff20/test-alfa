<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use App\Controllers\MainController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$controller = new MainController();
if ($uri === '/' || $uri === '' || $uri === '/index.php' ) {
    echo $controller->frontend();
} else if (str_starts_with($uri, '/backend')) {
    echo $controller->backend();
} else {
    http_response_code(404);
    echo "Page not found";
}
