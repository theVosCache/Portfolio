<?php

declare(strict_types=1);

namespace App\Tests;

use ReflectionProperty;

trait PrivatePropertyManipulator
{
    public function setByReflection($object, string $property, $value): void
    {
        $reflectionProperty = $this->getAccessibleReflectionProperty(object: $object, property: $property);

        $reflectionProperty->setValue($object, value: $value);
    }

    public function getByReflection($object, string $property)
    {
        $reflectionProperty = $this->getAccessibleReflectionProperty(object: $object, property: $property);

        return $reflectionProperty->getValue(object: $object);
    }

    private function getAccessibleReflectionProperty($object, string $property): ReflectionProperty
    {
        $reflectionProperty = new ReflectionProperty(class: $object, property: $property);
        $reflectionProperty->setAccessible(accessible: true);

        return $reflectionProperty;
    }
}
