<?php

declare(strict_types=1);

/**
 * Attribute class to define permissible MIME types for a file property.
 *
 * The class is marked as readonly to ensure immutability after initialization.
 * It is targeted specifically for class properties using the `Attribute::TARGET_PROPERTY` flag.
 */

namespace Gemini\DataHandling\Validation\Attributes\Rules;

use Attribute;
use finfo;
use Gemini\Exceptions\ValidationException;

/**
 * This class is designed to enforce validation of file MIME types.
 * Declared as `readonly` to ensure immutability of `mimetypes` after instantiation.
 */
#[Attribute(flags: Attribute::TARGET_PROPERTY)]
readonly class Mimetypes
{
    public function __construct(private array $mimetypes) {}

    /**
     * @throws \Gemini\Exceptions\ValidationException
     */
    public function validate(mixed $value, string $property) : void
    {
        $finfo    = new finfo(flags: FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file(filename: $value);

        if (! in_array($mimeType, $this->mimetypes, true)) {
            throw new ValidationException(
                message: $property . ' must be a file of type: ' . implode(
                           ', ',
                           $this->mimetypes,
                       ),
            );
        }
    }
}
