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
     * The name of the UUID column.
     *
     * @var string|null
     */
    protected $uuidColumn;

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
     * Initialize the model.
     */
    public function __construct(array $attributes = [])
    {
        // Set default UUID column from config if not already set
        if (!isset($this->uuidColumn)) {
            $this->uuidColumn = $this->resolveDefaultUuidColumn();
        }

        parent::__construct($attributes);
    }
}
