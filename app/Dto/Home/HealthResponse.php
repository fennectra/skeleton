<?php

namespace App\Dto\Home;

use Fennec\Attributes\Description;

readonly class HealthResponse
{
    public function __construct(
        #[Description('Statut du service')]
        public string $status,
        #[Description('Nom du service')]
        public string $service,
        #[Description('Horodatage ISO 8601')]
        public string $timestamp,
    ) {
    }
}
