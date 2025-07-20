<?php

declare(strict_types=1);

namespace Gemini\DataHandling\ObjectHandling\DTO\Support;

use ReflectionAttribute;
use ReflectionProperty;

final class PropertyMetadata
{
    public function __construct(
        public readonly string             $name,
        public readonly ReflectionProperty $property,
        /** @var array<ReflectionAttribute> */
        public readonly array              $attributes,
    ) {}

    /**
     * Checks whether the property has an explicit type.
     */
    public function isTyped() : bool
    {
        return $this->property->hasType();
    }

    /**
     * Checks whether the property is nullable.
     */
    public function isNullable() : bool
    {
        $type = $this->property->getType();

        return $type?->allowsNull() ?? true;
    }

    /**
     * Returns true if at least one attribute matches the given FQCN (case-sensitive).
     */
    public function hasAttribute(string $fqcn) : bool
    {
        return $this->property->getAttributes($fqcn) !== [];
    }

    /**
     * Instantiates all attributes.
     *
     * @return object[] List of attribute instances.
     */
    public function instantiateAttributes() : array
    {
        return array_map(
            static fn(ReflectionAttribute $attr) => $attr->newInstance(),
            $this->attributes
        );
    }
}
