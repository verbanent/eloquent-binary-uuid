<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default UUID Column Name
    |--------------------------------------------------------------------------
    |
    | This value determines the default column name used for UUID primary keys.
    | You can override this per model using the $uuidColumn property.
    |
    */
    'default_column' => env('BINARY_UUID_DEFAULT_COLUMN', 'id'),
];