<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\ForeignBinary;

use Verbanent\Uuid\Test\Example\AbstractExampleIdModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

/**
 * Mouse model.
 */
class MouseUuidModel extends AbstractExampleIdModel
{
    use ForeignBinaryUuidSupportableTrait;

    protected $fillable = ['uuid', 'foreignUuid'];
    private $uuidable = 'test';
}
