<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Grammars;

use Illuminate\Database\Connection;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Verbanent\Uuid\Grammars\MySqlGrammar;

/**
 * Unit tests for class \Verbanent\Uuid\Grammars\MySqlGrammar.
 */
class MySqlGrammarTest extends TestCase
{
    private $connection;

    /**
     * Allows to test private and protected methods.
     *
     * @param string $name
     *
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected static function getMethod(string $name): ReflectionMethod
    {
        $class = new \ReflectionClass(MySqlGrammar::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function setUp(): void
    {
        $this->connection = Mockery::mock(Connection::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Test for MySqlGrammar::typeUuid.
     *
     * @throws ReflectionException
     */
    public function testTypeUuid(): void
    {
        $mySqlGrammar = new MySqlGrammar($this->connection);
        $typeUuid = self::getMethod('typeUuid');
        $uuidMySqlType = $typeUuid->invokeArgs($mySqlGrammar, [new \Illuminate\Support\Fluent()]);

        $this->assertIsString($uuidMySqlType, 'Got ' . gettype($uuidMySqlType) . ' instead of string');
        $this->assertEquals('binary(16)', $uuidMySqlType, 'Got ' . $uuidMySqlType . ' instead of \'binary(16)\'');
    }
}
