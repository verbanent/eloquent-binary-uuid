<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example;

use Verbanent\Uuid\AbstractModel;

/**
 * Example class for traits tests.
 */
class AbstractExampleModel extends AbstractModel
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'model_test';

    /**
     * Create rows in table without timestamps.
     *
     * @var bool
     */
    public $timestamps = false;
}