<?php

use App\Controllers\HomeController;
use Fennec\Controllers\DocsController;
use Fennec\Core\Env;

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HomeController::class, 'health']);

// API Documentation — dev only
if (Env::get('APP_ENV') === 'dev') {
    $router->get('/docs', [DocsController::class, 'ui']);
    $router->get('/docs/openapi', [DocsController::class, 'openapi']);
}
