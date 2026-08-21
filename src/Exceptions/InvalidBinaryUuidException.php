<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Exceptions;

use Ramsey\Uuid\Uuid;

class InvalidBinaryUuidException extends \RuntimeException
{
    /**
     * A column read as a binary UUID holds something else.
     *
     * @param string $model
     * @param string $column
     * @param mixed  $value
     *
     * @return self
     */
    public static function forColumn(string $model, string $column, $value): self
    {
        return new self(sprintf(
            'Column "%s" on %s does not contain a 16-byte binary UUID (found %s). %s',
            $column,
            $model,
            self::describe($value),
            self::hint($value)
        ));
    }

    /**
     * A value assigned to the UUID column cannot be stored as a binary UUID.
     *
     * @param string $model
     * @param string $column
     * @param mixed  $value
     *
     * @return self
     */
    public static function forAssignedValue(string $model, string $column, $value): self
    {
        return new self(sprintf(
            'Cannot store the value assigned to column "%s" on %s: a UUID in string form '
            .'or 16 raw bytes is expected, found %s.',
            $column,
            $model,
            self::describe($value)
        ));
    }

    /**
     * Describe the offending value without leaking its contents.
     *
     * @param mixed $value
     *
     * @return string
     */
    private static function describe($value): string
    {
        if (!is_string($value)) {
            return sprintf('a value of type %s', gettype($value));
        }

        if (Uuid::isValid($value)) {
            return 'a UUID in string form';
        }

        return sprintf('a %d-byte string', strlen($value));
    }

    /**
     * Suggest the most likely fix for the offending value.
     *
     * @param mixed $value
     *
     * @return string
     */
    private static function hint($value): string
    {
        if (is_string($value) && Uuid::isValid($value)) {
            return 'UUIDs are stored as 16 raw bytes, so this column was written without the package.';
        }

        return 'Point $uuidColumn on the model, or binary-uuid.default_column, '
            .'at the column holding the binary UUID.';
    }
}
