<?php

declare(strict_types=1);

/**
 * Attribute class used to enforce alphanumeric validation on properties.
 *
 * This class can be used to annotate class properties to indicate that
 * they must only contain letters and numbers. This is particularly useful
 * for ensuring data integrity in DTOs (Data Transfer Objects) by validating
 * their properties against the alphanumeric constraint.
 */

namespace Gemini\DataHandling\Validation\Attributes\Rules;

use Attribute;
use Gemini\Exceptions\ValidationException;

/**
 * An attribute class to enforce that a property value consists solely of letters and numbers.
 *
 * This attribute can be applied to class properties to ensure data validation for alphanumeric characters.
 *
 * Note: This class relies on Unicode property escapes (\pL, \pM, \pN) to cover all letters, marks, and numbers,
 * allowing for internationalization support.
 */
#[Attribute(flags: Attribute::TARGET_PROPERTY)]
class AlphaNum
{
    /**
     * @throws \Gemini\Exceptions\ValidationException
     */
    public function validate(mixed $value, string $property) : void
    {
        if (in_array(preg_match('/^[\pL\pM\pN]+$/u', (string) $value), [0, false], true)) {
            throw new ValidationException(message: $property . ' may only contain letters and numbers.');
        }
    }
}
