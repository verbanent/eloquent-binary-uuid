<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example\BinaryId;

use Verbanent\Uuid\Test\Example\AbstractExampleIdModel;

/**
 * Cow model.
 */
class CowIdModel extends AbstractExampleIdModel
{
    protected $fillable = ['id'];
}
