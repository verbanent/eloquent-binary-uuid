<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Providers;

use Illuminate\Support\ServiceProvider;
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
    }

    private function createGrammar($connection): MySqlGrammar
    {
        $queryGrammar = $connection->getQueryGrammar();
        $grammar = new MySqlGrammar();
        $grammar->setTablePrefix($queryGrammar->getTablePrefix());

        return $grammar;
    }
}
