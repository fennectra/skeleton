<?php

/**
 * FrankenPHP Worker Script (legacy)
 *
 * Redirige vers index.php qui gere automatiquement le mode worker.
 * Ce fichier existe pour la retrocompatibilite avec les anciens Caddyfiles
 * ou deployments qui referencent worker.php directement.
 */

require __DIR__ . '/index.php';
