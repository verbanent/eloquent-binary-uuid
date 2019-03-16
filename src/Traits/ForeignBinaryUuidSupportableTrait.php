<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

use Illuminate\Database\Eloquent\{Collection, Model};
use Ramsey\Uuid\Uuid;

/**
 * Trait for models with binary UUID in other columns than just a primary key.
 */
trait ForeignBinaryUuidSupportableTrait
{
    /**
     * Method for Laravel's bootable Eloquent traits, generates UUID for every uuidable column automatically.
     */
    public static function bootForeignBinaryUuidSupportableTrait(): void
    {
        static::creating(function (Model $model) {
            foreach ($model->uuidable as $uuidable) {
                if (isset($model->attributes[$uuidable]) && !empty($model->attributes[$uuidable]) && is_string($model->attributes[$uuidable])) {
                    $model->attributes[$uuidable] = Uuid::fromString($model->attributes[$uuidable])->getBytes();
                }
            }
        });
    }

    /**
     * Allows to find model or collection by any column with UUID stored values.
     *
     * @param string $columnName
     * @param string $uuid
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findByUuid(string $columnName, string $uuid): Collection
    {
        $binaryUuid = Uuid::fromString($uuid)->getBytes();

        return static::where($columnName, '=', $binaryUuid)->get();
    }

    /**
     * Returns string form of UUID from foreign column.
     *
     * @param string $columnName
     *
     * @return string
     */
    public function foreignUuid(string $columnName): string
    {
        return Uuid::fromBytes($this->$columnName)->toString();
    }
}
