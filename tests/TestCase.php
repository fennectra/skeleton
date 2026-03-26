<?php

namespace Tests;

use Fennec\Core\DB;
use Fennec\Core\Env;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load .env if exists
        $envFile = dirname(__DIR__) . '/.env';
        if (file_exists($envFile)) {
            $ref = new \ReflectionClass(Env::class);
            $loaded = $ref->getProperty('loaded');
            $loaded->setValue(null, false);
            Env::load($envFile);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Reset DB connections between tests
        if (method_exists(DB::class, 'resetManager')) {
            DB::resetManager();
        }
    }

    /**
     * Run a raw SQL query and return all rows.
     */
    protected function query(string $sql, array $params = []): array
    {
        $stmt = DB::raw($sql, $params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Run a raw SQL query and return the first row.
     */
    protected function queryOne(string $sql, array $params = []): ?array
    {
        $rows = $this->query($sql, $params);

        return $rows[0] ?? null;
    }
}
