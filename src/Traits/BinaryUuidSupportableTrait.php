<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Traits;

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
     * Method for Laravel's bootable Eloquent traits, genereates UUID for every model automatically.
     */
    public static function bootBinaryUuidSupportableTrait()
    {
        static::saving(function ($model) {
            $codec = new OrderedTimeCodec(new DefaultUuidBuilder(new BigNumberConverter()));
            $model->uuid = $codec->encodeBinary(Uuid::uuid4());
        });
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
