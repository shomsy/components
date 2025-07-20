<?php

declare(strict_types=1);

/**
 * Attribute class for marking properties that should be validated as JSON.
 * Using #[Attribute(flags: Attribute::TARGET_PROPERTY)] to limit usage to properties.
 */

namespace Gemini\DataHandling\Validation\Attributes\Rules;

use Attribute;
use Gemini\Exceptions\ValidationException;

/**
 * Validates if the provided value is a valid JSON string.
 *
 * @throws \Gemini\Exceptions\ValidationException If the value is not a valid JSON string.
 *
 * The rationale for this function is to ensure that properties using this attribute
 * always contain valid JSON data. This helps maintain data integrity within the application.
 */
#[Attribute(flags: Attribute::TARGET_PROPERTY)]
class JSON
{
    /**
     * @throws \Gemini\Exceptions\ValidationException
     */
    public function validate(mixed $value, string $property) : void
    {
        json_decode((string) $value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ValidationException(message: $property . ' must be a valid JSON string.');
        }
    }
}
