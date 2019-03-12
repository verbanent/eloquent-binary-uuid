<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Trait for models with binary UUID in other columns than just a primary key.
 */
trait ForeignBinaryUuidSupportableTrait
{
    /**
     * Allows to find model or collection by any column with UUID stored values.
     *
     * @param string $columnName
     * @param string $uuid
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function findByUuid(string $columnName, string $uuid): Collection
    {
        $binaryUuid = Uuid::fromString($uuid)->getBytes();

        return static::where($columnName, '=', $binaryUuid)->get();
    }
}
