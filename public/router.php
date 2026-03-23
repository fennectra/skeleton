<?php

/**
 * Router script pour le serveur PHP intégré (php -S).
 *
 * Le serveur intégré intercepte les URLs avec extension (.jpg, .webp, etc.)
 * et tente de les servir comme fichiers statiques. Ce script force toutes
 * les requêtes non-statiques à passer par index.php.
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Servir les vrais fichiers statiques existants dans public/
$filePath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    return false; // Laisser le serveur intégré servir le fichier
}

// Tout le reste passe par index.php (y compris /storage/{path})
require __DIR__ . '/index.php';
