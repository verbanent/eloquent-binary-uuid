<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Builder\DefaultUuidBuilder;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Ramsey\Uuid\Converter\Number\BigNumberConverter;
use Ramsey\Uuid\Uuid;

/**
 * Trait for models with binary UUID.
 */
trait BinaryUuidSupportableTrait
{
    /**
     * Construct is used here, because it's nice to have UUID just after creating the object,
     * and even before writing to the database.
     *
     * @param array $attributes
     *
     * @throws \Exception
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->uuid = $this->generateUuid();
    }

    /**
     * Returns time ordered binary UUID version 1, because it's the only version can be time-ordered.
     *
     * @see https://github.com/ramsey/uuid-doctrine/blob/master/src/UuidBinaryOrderedTimeType.php#L151
     *
     * @throws \Exception
     *
     * @return string
     */
    private function generateUuid(): string
    {
        $codec = new OrderedTimeCodec(new DefaultUuidBuilder(new BigNumberConverter()));

        return $codec->encodeBinary(Uuid::uuid1());
    }

    /**
     * Method for Laravel's bootable Eloquent traits, genereates UUID for every model automatically,
     * just in case if construct method will be overwritten in a model class.
     */
    public static function bootBinaryUuidSupportableTrait(): void
    {
        static::saving(function (Model $model) {
            if (!isset($model->uuid) || empty($model->uuid)) {
                $model->uuid = $model->generateUuid();
            }
        });
    }

    /**
     * Returns model by its UUID or fails.
     *
     * @param string $uuid
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function find(string $uuid): Model
    {
        $binaryUuid = Uuid::fromString($uuid)->getBytes();

        return static::where('uuid', '=', $binaryUuid)->firstOrFail();
    }

    /**
     * Returns string form of UUID.
     *
     * @return string
     */
    public function uuid(): string
    {
        return Uuid::fromBytes($this->uuid)->toString();
    }
}
