<?php

declare(strict_types=1);

namespace Gemini\Database\Migration\Runner\DTO;

use Gemini\Database\Migration\Design\Table\Enum\FieldTypeEnum;
use Gemini\Database\Migration\Design\Table\Enum\ForeignActionEnum;
use Gemini\DataHandling\ObjectHandling\DTO\AbstractDTO;
use Gemini\DataHandling\Validation\Attributes\Rules\ArrayType;
use Gemini\DataHandling\Validation\Attributes\Rules\MigrationArrayRule;
use Gemini\DataHandling\Validation\Attributes\Rules\MigrationFieldAttributesRule;
use Gemini\DataHandling\Validation\Attributes\Rules\MigrationFieldTypeRule;
use Gemini\DataHandling\Validation\Attributes\Rules\MigrationForeignActionRule;
use Gemini\DataHandling\Validation\Attributes\Rules\MigrationIntegerRule;
use Gemini\DataHandling\Validation\Attributes\Rules\MigrationStringRule;
use Gemini\DataHandling\Validation\Attributes\Rules\Required;
use Gemini\DataHandling\Validation\Attributes\Rules\StringType;
use Gemini\DataHandling\Validation\Attributes\Rules\Trimmed;
use InvalidArgumentException;

final class FieldDTO extends AbstractDTO
{
    #[Required]
    #[Trimmed]
    #[StringType]
    public string                 $name;

    #[MigrationFieldTypeRule]
    public FieldTypeEnum|null     $type       = null;

    #[MigrationIntegerRule]
    public int|null               $length     = null;

    #[MigrationIntegerRule]
    public int|null               $total      = null;

    #[MigrationIntegerRule]
    public int|null               $places     = null;

    #[MigrationArrayRule]
    public array|null             $values     = null;

    public mixed                  $default    = null;

    #[MigrationFieldAttributesRule]
    public array|null             $attributes = null;

    #[MigrationStringRule]
    #[Trimmed]
    public string|null            $comment    = null;

    #[MigrationStringRule]
    #[Trimmed]
    public string|null            $references = null;

    #[MigrationStringRule]
    #[Trimmed]
    public string|null            $on         = null;

    #[MigrationForeignActionRule]
    public ForeignActionEnum|null $onDelete   = null;

    #[MigrationForeignActionRule]
    public ForeignActionEnum|null $onUpdate   = null;

    #[ArrayType]
    public array|null             $columns    = null;

    public function __construct(array|object $data)
    {
        $data = (array) $data;

        if (isset($data['name'], $data['type'])) {
            parent::__construct(data: $data);

            return;
        }

        $fieldName  = array_key_first($data);
        $definition = (array) ($data[$fieldName] ?? []);

        if (! isset($definition['type'])) {
            throw new InvalidArgumentException("Missing required 'type' key for field '{$fieldName}'");
        }

        $definition['name'] = $fieldName;
        parent::__construct(data: $definition);
    }
}
