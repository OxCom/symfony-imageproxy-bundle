<?php

namespace Tests;

trait ReflectPropsTrait
{
    /**
     * Modify not public properties on a given object via reflection
     *
     * @param       $object     - instance to modify list of properties
     * @param array $properties - associative array ['propertyName' => 'propertyValue']
     *
     * @throws \ReflectionException
     */
    public function setObjectProperties($object, array $properties): void
    {
        $reflection = new \ReflectionClass($object);

        foreach ($properties as $name => $value) {
            $rProp = $reflection->getProperty($name);
            $rProp->setAccessible(true);
            $rProp->setValue($object, $value);
        }
    }

    /**
     * Read not public property on a given object via reflection
     *
     * @param        $object   - instance to ready property value
     * @param string $property - associative property
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function getObjectProperty($object, string $property): mixed
    {
        $reflection = new \ReflectionClass($object);
        $rProp = $reflection->getProperty($property);
        $rProp->setAccessible(true);

        return $rProp->getValue($object);
    }

    /**
     * Read not public properties on a given object via reflection
     *
     * @param       $object - instance to ready property value
     * @param array $properties - associative properties array ['propertyName1', 'propertyName2']
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getObjectProperties($object, array $properties): array
    {
        return \array_map(
            fn ($name) => $this->getObjectProperty($object, $name),
            \array_combine($properties, $properties)
        );
    }
}
