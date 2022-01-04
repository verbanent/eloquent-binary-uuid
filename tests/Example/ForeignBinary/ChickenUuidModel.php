<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinary;

use Verbanent\Uuid\Test\Example\AbstractExampleUuidModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Chicken model.
 */
class ChickenUuidModel extends AbstractExampleUuidModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $fillable = ['uuid', 'foreignUuid'];

    private $uuidable = ['foreignUuid'];
}
