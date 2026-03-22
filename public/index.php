<?php

/**
 * Point d'entree HTTP unifie.
 *
 * - FrankenPHP worker mode : l'app est initialisee UNE SEULE FOIS,
 *   chaque requete est traitee dans la boucle frankenphp_handle_request().
 * - Mode classique (PHP-FPM, built-in) : bootstrap complet par requete.
 *
 * FrankenPHP detecte automatiquement le mode via $_SERVER['FRANKENPHP_WORKER'].
 * Si absent, on tente frankenphp_handle_request() qui leve une exception
 * en mode non-worker, et on fallback sur le mode classique.
 */

// Racine du projet
define('FENNEC_BASE_PATH', dirname(__DIR__));

require_once FENNEC_BASE_PATH . '/vendor/autoload.php';

use Fennec\Core\App;
use Fennec\Core\Logger;
use Fennec\Middleware\CorsMiddleware;
use Fennec\Middleware\ProfilerMiddleware;
use Fennec\Middleware\RequestLoggingMiddleware;
use Fennec\Middleware\SecurityHeadersMiddleware;
use Fennec\Middleware\TenantMiddleware;

$app = new App();
$app->router()->addGlobalMiddleware(CorsMiddleware::class);
$app->router()->addGlobalMiddleware(TenantMiddleware::class);
$app->router()->addGlobalMiddleware(ProfilerMiddleware::class);
$app->router()->addGlobalMiddleware(RequestLoggingMiddleware::class);
$app->router()->addGlobalMiddleware(SecurityHeadersMiddleware::class);
$app->loadRoutes(__DIR__ . '/../app/Routes');

// Detection du mode worker FrankenPHP
if (function_exists('frankenphp_handle_request')) {
    Logger::info('Worker boot complete', [
        'pid' => getmypid(),
        'memory_after_boot_mb' => round(memory_get_usage(true) / 1048576, 2),
    ]);
    $app->runWorker();
} else {
    // Mode classique : PHP-FPM, built-in server, CLI
    $app->run();
}
