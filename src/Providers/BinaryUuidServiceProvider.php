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
    public function boot()
    {
        $grammar = new MySqlGrammar();
        $connection = app('db')->connection();
        $connection->setSchemaGrammar($grammar);
    }
}
