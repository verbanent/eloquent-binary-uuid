<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinary;

use Verbanent\Uuid\Test\Example\AbstractExampleModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Snake model.
 */
class SnakeModel extends AbstractExampleModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $readable = true;
    protected $fillable = ['uuid', 'foreignUuid'];
    private $uuidable = ['foreignUuid'];
}
