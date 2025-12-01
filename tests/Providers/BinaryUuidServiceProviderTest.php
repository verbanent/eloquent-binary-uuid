<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Grammars\Grammar as QueryGrammar;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as IlluminateMySqlGrammar;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Verbanent\Uuid\Providers\BinaryUuidServiceProvider;

class BinaryUuidServiceProviderTest extends TestCase
{
    /**
     * BinaryUuidServiceProvider instance.
     *
     * @var BinaryUuidServiceProvider
     */
    private $binaryUuidServiceProvider;

    /**
     * Check if Grammar constructor requires Connection (Laravel 12+).
     */
    private function grammarRequiresConnection(): bool
    {
        $reflection = new ReflectionClass(IlluminateMySqlGrammar::class);
        $constructor = $reflection->getConstructor();

        return $constructor !== null && $constructor->getNumberOfParameters() > 0;
    }

    public function setUp(): void
    {
        $queryGrammar = Mockery::mock(QueryGrammar::class);
        $queryGrammar->shouldReceive('getTablePrefix')->andReturn('');

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('getQueryGrammar')->andReturn($queryGrammar);
        $connection->shouldReceive('setSchemaGrammar')->andReturn(null);

        if ($this->grammarRequiresConnection()) {
            $connection->shouldReceive('setTablePrefix')->andReturn(null);
        }

        Mockery::mock('overload:db')
            ->shouldReceive('connection')->andReturn($connection);

        $app = Mockery::mock(Application::class);

        $this->binaryUuidServiceProvider = new BinaryUuidServiceProvider($app);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testBoot()
    {
        $this->assertInstanceOf(BinaryUuidServiceProvider::class, $this->binaryUuidServiceProvider);
        $this->assertNull($this->binaryUuidServiceProvider->boot());
    }
}
