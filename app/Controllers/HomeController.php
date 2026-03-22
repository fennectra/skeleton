<?php

namespace App\Controllers;

use App\Dto\Home\HealthResponse;
use Fennec\Attributes\ApiDescription;

class HomeController
{
    #[ApiDescription('Welcome', 'Returns API information.')]
    public function index(): array
    {
        return [
            'service' => 'Fennectra API',
            'status' => 'ok',
            'version' => '1.0.0',
        ];
    }

    #[ApiDescription('Health check', 'Returns service health status.')]
    public function health(): HealthResponse
    {
        return new HealthResponse(
            status: 'ok',
            service: 'Fennectra',
            timestamp: date('c'),
        );
    }
}
