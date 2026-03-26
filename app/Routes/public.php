<?php

use App\Controllers\HomeController;

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HomeController::class, 'health']);
