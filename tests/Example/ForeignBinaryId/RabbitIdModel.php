<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinaryId;

use Verbanent\Uuid\Test\Example\AbstractExampleIdModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Rabbit model.
 */
class RabbitIdModel extends AbstractExampleIdModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $readable = true;
    protected $fillable = ['id', 'foreignUuid'];
    private $uuidable = ['foreignUuid'];
}
