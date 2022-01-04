<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinary;

use Verbanent\Uuid\Test\Example\AbstractExampleUuidModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Rabbit model.
 */
class RabbitUuidModel extends AbstractExampleUuidModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $readable = true;
    protected $fillable = ['uuid', 'foreignUuid'];
    private $uuidable = ['foreignUuid'];
}
