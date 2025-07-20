<?php

declare(strict_types=1);

namespace Gemini\Container\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class Scope
{
    public function __construct(public string $name) {}
}