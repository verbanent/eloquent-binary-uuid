<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as IlluminateMySqlGrammar;
use Illuminate\Support\Fluent;

/**
 * Class changes the UUID type from default char(36) to binary(16).
 */
class MySqlGrammar extends IlluminateMySqlGrammar
{
    /**
     * Creates the column definition for the UUID type.
     */
    protected function typeUuid(Fluent $column): string
    {
        return 'binary(16)';
    }
}
