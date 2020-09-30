<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Ramsey\Uuid\Uuid;
use Verbanent\Uuid\Exceptions\AccessedUnsetUuidPropertyException;

/**
 * Trait for models with binary UUID.
 */
trait BinaryUuidSupportableTrait
{
    /**
     * Creates new instance if it doesn't exist or returns existing one.
     *
     * @param array $attributes
     * @param array $values
     *
     * @return Model
     */
    public static function firstOrCreate(array $attributes, array $values = []): Model
    {
        foreach ($attributes as $key => $attribute) {
            if (Uuid::isValid($attribute)) {
                $attributes[$key] = Uuid::fromString($attribute)->getBytes();
            }
        }

        $instance = static::where($attributes)->first();

        if ($instance === null) {
            $instance = static::create($attributes + $values);
        }

        return $instance;
    }

    /**
     * Encode given string format UUID to binary one.
     *
     * @param string $uuid
     *
     * @return string
     */
    public static function encodeUuid(string $uuid): string
    {
        return Uuid::fromString($uuid)->getBytes();
    }

    /**
     * Method for Laravel's bootable Eloquent traits, generates UUID for every model automatically.
     *
     * @throws Exception
     */
    public static function bootBinaryUuidSupportableTrait(): void
    {
        static::creating(
            function (Model $model) {
                /** @var Model|BinaryUuidSupportableTrait $model */
                if (!isset($model->attributes['uuid'])) {
                    $model->uuid = $model->generateUuid();
                } elseif (Uuid::isValid($model->attributes['uuid'])) {
                    $model->uuid = Uuid::fromString($model->attributes['uuid'])->getBytes();
                } elseif (is_string($model->attributes['uuid']) && strlen($model->attributes['uuid']) === 16) {
                    $model->uuid = $model->attributes['uuid'];
                }

                if (isset($model->readable) && $model->readable) {
                    $model->readable_uuid = $model->uuid();
                }
            }
        );
    }

    /**
     * Returns time ordered binary UUID version 1, because it's the only version
     * can be time-ordered.
     *
     * @see https://github.com/ramsey/uuid-doctrine/blob/master/src/UuidBinaryOrderedTimeType.php#L151
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateUuid(): string
    {
        return (new OrderedTimeCodec(Uuid::getFactory()->getUuidBuilder()))->encodeBinary(Uuid::uuid1());
    }

    /**
     * Returns string form of UUID.
     *
     * @throws Exception
     *
     * @return string
     */
    public function uuid(): string
    {
        if (!isset($this->uuid)) {
            throw new AccessedUnsetUuidPropertyException(
                'Cannot get UUID property for not saved model'
            );
        }

        return Uuid::fromBytes($this->uuid)->toString();
    }

    /**
     * Returns model by its UUID or fails.
     *
     * @param string $uuid
     *
     * @return Model
     */
    public static function find(string $uuid): Model
    {
        return static::where('uuid', '=', Uuid::fromString($uuid)->getBytes())->firstOrFail();
    }
}
