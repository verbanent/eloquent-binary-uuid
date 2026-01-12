<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Fixtures;

final class MySqlGrammarNoConnection
{
    private $tablePrefix = '';

    public function setTablePrefix(string $prefix): void
    {
        $this->tablePrefix = $prefix;
    }

    public function getTablePrefix(): string
    {
        return $this->tablePrefix;
    }
}
