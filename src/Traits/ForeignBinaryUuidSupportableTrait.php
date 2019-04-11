<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
            if (!is_array($model->uuidable)) {
                return;
            }

            foreach ($model->uuidable as $uuidable) {
                if (!static::isUuidValid($model, $uuidable)) {
                    continue;
                }

                if (!static::isUuidFromString($model, $uuidable)) {
                    continue;
                }

                if (!static::isUuidFromBinary($model, $uuidable)) {
                    continue;
                }

                static::setReadableUuid($model, $uuidable);
            }
        });
    }

    /**
     * Set readable UUID in model if necessary.
     *
     * @param Model $model
     * @param string $uuidable
     */
    private static function setReadableUuid(Model &$model, string $uuidable): void
    {
        if (!isset($model->readable) || isset($model->readable) && !$model->readable) {
            return;
        }

        $model->readable_{$uuidable} = $model->foreignUuid($uuidable);
    }

    /**
     * Check if UUID can be created from given string.
     *
     * @param Model $model
     * @param string $uuidable
     *
     * @return bool
     */
    private static function isUuidFromString(Model &$model, string $uuidable): bool
    {
        if (!Uuid::isValid($model->attributes[$uuidable])) {
            return false;
        }

        $model->$uuidable = Uuid::fromString($model->attributes[$uuidable])->getBytes();

        return true;
    }

    /**
     * Check if UUID can be created from given binary.
     *
     * @param Model $model
     * @param string $uuidable
     *
     * @return bool
     */
    private static function isUuidFromBinary(Model &$model, string $uuidable): bool
    {
        if (strlen($model->attributes[$uuidable]) !== 16) {
            return false;
        }

        $model->$uuidable = $model->attributes[$uuidable];

        return true;
    }

    /**
     * Check if UUID is valid.
     *
     * @param Model $model
     * @param string $uuidable
     *
     * @return bool
     */
    private static function isUuidValid(Model $model, string $uuidable): bool
    {
        if (!isset($model->attributes[$uuidable])) {
            return false;
        }

        if (!is_string($model->attributes[$uuidable])) {
            return false;
        }

        return true;
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
