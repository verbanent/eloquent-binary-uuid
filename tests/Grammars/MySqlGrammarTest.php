<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Grammars;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as IlluminateMySqlGrammar;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Verbanent\Uuid\Grammars\MySqlGrammar;

/**
 * Unit tests for class \Verbanent\Uuid\Grammars\MySqlGrammar.
 */
class MySqlGrammarTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

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
        $class = new ReflectionClass(MySqlGrammar::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Check if Grammar constructor requires Connection (Laravel 12+).
     */
    private function grammarRequiresConnection(): bool
    {
        $reflection = new ReflectionClass(IlluminateMySqlGrammar::class);
        $constructor = $reflection->getConstructor();

        return $constructor !== null && $constructor->getNumberOfParameters() > 0;
    }

    /**
     * Test for MySqlGrammar::typeUuid.
     *
     * @throws ReflectionException
     */
    public function testTypeUuid(): void
    {
        if ($this->grammarRequiresConnection()) {
            $connection = Mockery::mock(Connection::class);
            $mySqlGrammar = new MySqlGrammar($connection);
        } else {
            $mySqlGrammar = new MySqlGrammar();
        }

        $typeUuid = self::getMethod('typeUuid');
        $uuidMySqlType = $typeUuid->invokeArgs($mySqlGrammar, [new \Illuminate\Support\Fluent()]);

        $this->assertIsString($uuidMySqlType, 'Got ' . gettype($uuidMySqlType) . ' instead of string');
        $this->assertEquals('binary(16)', $uuidMySqlType, 'Got ' . $uuidMySqlType . ' instead of \'binary(16)\'');
    }
}
