<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinary;

use Verbanent\Uuid\Test\Example\AbstractExampleIdModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Snake model.
 */
class SnakeUuidModel extends AbstractExampleIdModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $readable = true;
    protected $fillable = ['uuid', 'foreignUuid'];
    private $uuidable = ['foreignUuid'];
}
