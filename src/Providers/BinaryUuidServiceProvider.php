<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Providers;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;
use Verbanent\Uuid\Grammars\MySqlGrammar;

/**
 * Service provides grammar to change UUID default column type to binary.
 */
class BinaryUuidServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $connection = app('db')->connection();
        $connection->setSchemaGrammar($this->createGrammar($connection));

        $this->publishes([
            __DIR__ . '/../../config/binary-uuid.php' => config_path('binary-uuid.php'),
        ], 'binary-uuid-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/binary-uuid.php',
            'binary-uuid'
        );
    }

    private function createGrammar($connection): MySqlGrammar
    {
        if ($this->doesGrammarRequireConnection()) {
            return new MySqlGrammar($connection);
        }

        $prefix = $connection->getTablePrefix();
        $grammar = new MySqlGrammar();
        $grammar->setTablePrefix($prefix);

        return $grammar;
    }

    private function doesGrammarRequireConnection(): bool
    {
        $reflection = new ReflectionClass(MySqlGrammar::class);
        $constructor = $reflection->getConstructor();

        return $constructor !== null && $constructor->getNumberOfRequiredParameters() > 0;
    }
}
