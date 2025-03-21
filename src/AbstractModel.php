<?php

declare(strict_types=1);

namespace Verbanent\Uuid;

use Illuminate\Database\Eloquent\Model;
use Verbanent\Uuid\Traits\BinaryUuidSupportableTrait;

/**
 * Class can be used as parent for all model class using UUID binary primary keys.
 */
class AbstractModel extends Model
{
    use BinaryUuidSupportableTrait;

    public const DEFAULT_UUID_COLUMN = env('DEFAULT_UUID_COLUMN', 'id');

    /**
     * Disable incrementing of ID column.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Type for primary key.
     *
     * @var string
     */
    protected $keyType = 'uuid';
}
