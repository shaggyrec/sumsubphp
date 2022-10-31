<?php

namespace Shaggyrec\Sumsubphp\DTO;

use \ReflectionClass;
use \ReflectionProperty;

abstract class AbstractDTO implements DTOInterface
{
    private const PRIMITIVE_TYPES = [
        'string',
        'int',
        'float',
        'array',
    ];
    public function __construct(array $sumSubData)
    {
        $reflectionClass = new ReflectionClass(static::class);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            if (isset($sumSubData[$propertyName])) {
                $type = $reflectionProperty->getType()->getName();
                $this->{$propertyName} = in_array($type, self::PRIMITIVE_TYPES)
                    ? $sumSubData[$propertyName]
                    : new $type($sumSubData[$propertyName]);
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}