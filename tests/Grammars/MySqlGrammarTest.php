<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Grammars;

use PHPUnit\Framework\TestCase;
use Verbanent\Uuid\Grammars\MySqlGrammar;

/**
 * Unit tests for class \Verbanent\Uuid\Grammars\MySqlGrammar.
 */
class MySqlGrammarTest extends TestCase
{
    /**
     * Allows to test private and protected methods.
     *
     * @param string $name
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionMethod
     */
    protected static function getMethod(string $name): \ReflectionMethod
    {
        $class = new \ReflectionClass(MySqlGrammar::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Test for MySqlGrammar::typeUuid.
     *
     * @throws \ReflectionException
     */
    public function testTypeUuid(): void
    {
        $mySqlGrammar = new MySqlGrammar();
        $typeUuid = self::getMethod('typeUuid');
        $uuidMySqlType = $typeUuid->invokeArgs($mySqlGrammar, [new \Illuminate\Support\Fluent()]);

        $this->assertIsString($uuidMySqlType, 'Got '.gettype($uuidMySqlType).' instead of string');
        $this->assertEquals('binary(16)', $uuidMySqlType, 'Got '.$uuidMySqlType.' instead of \'binary(16)\'');
    }
}
