<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Providers;

use Illuminate\Contracts\Foundation\Application;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Verbanent\Uuid\Providers\BinaryUuidServiceProvider;
use Verbanent\Uuid\Test\Fixtures\MySqlGrammarNoConnection;

class BinaryUuidServiceProviderLegacyGrammarTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCreateGrammarWithoutConnectionRequirementUsesTablePrefix(): void
    {
        // Simulate older Illuminate grammar with no required constructor params.
        class_alias(
            MySqlGrammarNoConnection::class,
            'Verbanent\\Uuid\\Grammars\\MySqlGrammar'
        );

        $provider = new BinaryUuidServiceProvider(Mockery::mock(Application::class));
        $connection = new class {
            public function getTablePrefix(): string
            {
                return 'legacy_';
            }
        };

        $method = new ReflectionMethod(BinaryUuidServiceProvider::class, 'createGrammar');
        $method->setAccessible(true);

        $grammar = $method->invoke($provider, $connection);

        $this->assertInstanceOf(MySqlGrammarNoConnection::class, $grammar);
        $this->assertSame('legacy_', $grammar->getTablePrefix());
    }
}
