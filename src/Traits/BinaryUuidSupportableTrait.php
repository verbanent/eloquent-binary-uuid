<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Ramsey\Uuid\Uuid;
use Verbanent\Uuid\Exceptions\AccessedUnsetUuidPropertyException;
use Verbanent\Uuid\Exceptions\InvalidBinaryUuidException;

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
                $uuid = $model->getUuidColumn();
                $value = $model->attributes[$uuid] ?? null;

                /** @var Model|BinaryUuidSupportableTrait $model */
                if ($value === null) {
                    $model->$uuid = $model->generateUuid();
                } elseif (is_string($value) && Uuid::isValid($value)) {
                    $model->$uuid = Uuid::fromString($value)->getBytes();
                } elseif (is_string($value) && strlen($value) === 16) {
                    $model->$uuid = $value;
                } else {
                    throw InvalidBinaryUuidException::forAssignedValue(get_class($model), $uuid, $value);
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
     * @throws AccessedUnsetUuidPropertyException
     * @throws InvalidBinaryUuidException
     * @throws Exception
     *
     * @return string
     */
    public function uuid(): string
    {
        $uuid = $this->getUuidColumn();

        if (!isset($this->$uuid)) {
            throw new AccessedUnsetUuidPropertyException(sprintf(
                'Cannot get UUID from column "%s" for not saved model %s',
                $uuid,
                static::class
            ));
        }

        $value = $this->$uuid;

        if (!is_string($value) || strlen($value) !== 16) {
            throw InvalidBinaryUuidException::forColumn(static::class, $uuid, $value);
        }

        return Uuid::fromBytes($value)->toString();
    }

    /**
     * Return primary UUID column.
     *
     * @return string
     */
    public function getUuidColumn(): string
    {
        return $this->uuidColumn ?? $this->resolveDefaultUuidColumn();
    }

    /**
     * Resolve default UUID column without requiring a full Laravel app context.
     */
    private function resolveDefaultUuidColumn(): string
    {
        $container = Container::getInstance();

        if ($container->bound('config')) {
            $config = $container->make('config');

            if (is_object($config) && method_exists($config, 'get')) {
                return (string) $config->get('binary-uuid.default_column', 'id');
            }
        }

        return 'id';
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
        $uuidKey = app(static::class)->getUuidColumn();

        return static::where($uuidKey, '=', Uuid::fromString($uuid)->getBytes())->firstOrFail();
    }
}
