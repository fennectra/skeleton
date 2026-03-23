<?php

namespace App\Middleware;

use App\Models\User;
use Fennec\Core\Container;
use Fennec\Core\HttpException;
use Fennec\Core\JwtService;

class Auth
{
    /**
     * Middleware : vérifie le token Bearer + rôle.
     *
     * @param string|array|null $allowedRoles
     */
    public function handle(string|array|null $allowedRoles = null): void
    {
        $claims = self::validateToken();

        if ($claims === null) {
            throw new HttpException(401, 'Token invalide ou expiré');
        }

        // Récupérer le token brut et l'email depuis les claims
        $email = $claims['sub'] ?? null;
        $rawToken = self::extractRawToken();

        if (!$email || !$rawToken) {
            throw new HttpException(401, 'Token invalide');
        }

        // Vérification du token en BDD (comme l'API Python)
        $user = User::findByEmailAndToken($email, $rawToken);

        if (!$user) {
            throw new HttpException(401, 'Token révoqué ou utilisateur inactif');
        }

        // Stocker l'utilisateur pour les controllers
        // $_REQUEST pour backward compat (nettoyé dans App::runWorker)
        $_REQUEST['__auth_user'] = $user;

        // Vérification du rôle
        if ($allowedRoles !== null) {
            if (is_string($allowedRoles)) {
                $allowedRoles = [$allowedRoles];
            }

            if (!in_array($user['role'] ?? '', $allowedRoles, true)) {
                throw new HttpException(403, 'Accès refusé : rôle insuffisant');
            }
        }
    }

    /**
     * Valide la signature JWT via firebase/php-jwt et retourne les claims.
     */
    public static function validateToken(): ?array
    {
        $rawToken = self::extractRawToken();
        if (!$rawToken) {
            return null;
        }

        try {
            $jwt = Container::getInstance()->get(JwtService::class);

            return $jwt->decode($rawToken);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Extrait le token brut depuis le header Authorization.
     */
    public static function extractRawToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if (preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Récupère l'utilisateur authentifié (stocké par le middleware).
     * Safe en mode worker : nettoyé entre chaque requête par App::runWorker().
     */
    public static function user(): ?array
    {
        return $_REQUEST['__auth_user'] ?? null;
    }
}
