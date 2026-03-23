<?php

use App\Controllers\HomeController;
use Fennec\Controllers\DocsController;
use Fennec\Core\Env;

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HomeController::class, 'health']);

// API Documentation — enabled via DOCS_ENABLED=true or APP_ENV=dev
if (Env::get('DOCS_ENABLED', Env::get('APP_ENV') === 'dev' ? 'true' : 'false') === 'true') {
    $router->get('/docs', [DocsController::class, 'ui']);
    $router->get('/docs/openapi', [DocsController::class, 'openapi']);
}
