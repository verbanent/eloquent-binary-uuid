<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as IlluminateMySqlGrammar;
use Illuminate\Support\Fluent;

/**
 * Class change UUID type from default char(36) to binary(16).
 */
class MySqlGrammar extends IlluminateMySqlGrammar
{
    /**
     * Create the column definition for a uuid type.
     *
     * @param \Illuminate\Support\Fluent
     *
     * @return string
     */
    protected function typeUuid(Fluent $column): string
    {
        return 'binary(16)';
    }
}
