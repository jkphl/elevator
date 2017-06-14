<?php

/**
 * elevator
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Elevator\Domain
 * @author Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright Copyright © 2017 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2017 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Jkphl\Elevator\Domain;

/**
 * Elevator
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Domain
 */
class Elevator
{
    /**
     * Source object
     *
     * @var object
     */
    protected $object;
    /**
     * Source object class reflection
     *
     * @var \ReflectionClass
     */
    protected $class;

    /**
     * Constructor
     *
     * @param object $object Source object
     * @throws UnexpectedValueException If the argument is not an object
     */
    public function __construct($object)
    {
        // If the argument is not an object
        if (!is_object($object)) {
            throw new UnexpectedValueException(
                UnexpectedValueException::OBJECT_REQUIRED_STR,
                UnexpectedValueException::OBJECT_REQUIRED
            );
        }

        $this->object = $object;
        $this->class = new \ReflectionClass($this->object);

        // If the object is of an internal class
        if ($this->class->isInternal()) {
            throw new UnexpectedValueException(
                sprintf(UnexpectedValueException::NON_INTERNAL_REQUIRED_STR, $this->class->getName()),
                UnexpectedValueException::NON_INTERNAL_REQUIRED
            );
        }
    }

    /**
     * Elevate the current object to a particular class
     *
     * @param \ReflectionClass $class Target class
     * @param ElevationMap $map
     * @return object Elevated target object
     */
    public function elevate(\ReflectionClass $class, ElevationMap $map)
    {
        // If the target class doesn't extend the source object class
        if (!$class->isSubclassOf($this->class->getName())) {
            throw new UnexpectedValueException(
                sprintf(
                    UnexpectedValueException::INVALID_TARGET_CLASS_STR,
                    $class->getName(),
                    $this->class->getName()
                ),
                UnexpectedValueException::INVALID_TARGET_CLASS
            );
        }

        return $this->mergeMap($class->newInstanceWithoutConstructor(), $class, $map);
    }

    /**
     * Recursively merge an elevation map into an elevated target object
     *
     * @param object $object Target object
     * @param \ReflectionClass $class Current class
     * @param ElevationMap $map Elevation map
     * @return object Target object
     */
    protected function mergeMap($object, \ReflectionClass $class, ElevationMap $map)
    {
        // Run through all property values declared by this class
        foreach ($map->getPropertyValues($class) as $propertyName => $propertyValue) {
            $property = $class->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $propertyValue);
            $property->setAccessible(false);
        }

        $parentClass = $class->getParentClass();
        if (($parentClass instanceof \ReflectionClass) && !$parentClass->isInternal()) {
            $this->mergeMap($object, $parentClass, $map);
        }

        return $object;
    }
}
