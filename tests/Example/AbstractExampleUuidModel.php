<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Example;

use Verbanent\Uuid\AbstractModel;

/**
 * Example class for traits tests.
 */
class AbstractExampleUuidModel extends AbstractModel
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'model_test_uuid';

    /**
     * Create rows in table without timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Column name for primary key.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * Allow to fill UUID column.
     *
     * @var array
     */
    protected $fillable = ['uuid'];
}
