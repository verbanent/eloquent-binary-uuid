<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinaryId;

use Verbanent\Uuid\Test\Example\AbstractExampleIdModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Mouse model.
 */
class MouseIdModel extends AbstractExampleIdModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $fillable = ['id', 'foreignUuid'];
    private $uuidable = 'test';
}
