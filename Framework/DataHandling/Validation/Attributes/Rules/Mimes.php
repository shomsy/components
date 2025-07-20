<?php

declare(strict_types=1);

/**
 * Attribute class Mimes to enforce file type restrictions on a given property.
 *
 * This class is marked as `readonly` to indicate that once instantiated, the `$mimes` array should not be modified,
 * maintaining the integrity of the type rules it enforces.
 */

namespace Gemini\DataHandling\Validation\Attributes\Rules;

use Attribute;
use Gemini\Exceptions\ValidationException;

/**
 * ########################################################################
 * Represents a validation rule for file mime types.
 *
 * `readonly` keyword ensures the $mimes array cannot be modified after
 * the object is constructed, which guarantees immutability and consistency.
 * This is crucial for validation logic as allowed mime types must remain constant.
 * ########################################################################
 */
#[Attribute(flags: Attribute::TARGET_PROPERTY)]
readonly class Mimes
{
    public function __construct(private array $mimes) {}

    /**
     * @throws \Gemini\Exceptions\ValidationException
     */
    public function validate(mixed $value, string $property) : void
    {
        $extension = strtolower(pathinfo((string) $value, PATHINFO_EXTENSION));
        if (! in_array($extension, $this->mimes)) {
            throw new ValidationException(
                message: $property . ' must be a file of type: ' . implode(', ', $this->mimes),
            );
        }
    }
}
