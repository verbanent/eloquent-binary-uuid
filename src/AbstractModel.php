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
