<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinary;

use Verbanent\Uuid\Test\Example\AbstractExampleModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Chicken model.
 */
class ChickenModel extends AbstractExampleModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $fillable = ['uuid', 'foreignUuid'];

    private $uuidable = ['foreignUuid'];
}
