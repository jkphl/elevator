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
 * Elevation property map
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Domain
 */
class ElevationMap
{
    /**
     * Source object
     *
     * @var object
     */
    protected $object;
    /**
     * Elevation property map
     *
     * @var array
     */
    protected $map = [];

    /**
     * Elevation map constructor
     *
     * @param object $object Source object
     */
    public function __construct($object)
    {
        $this->object = $object;
        $class = new \ReflectionClass($this->object);
        if (!$class->isInternal()) {
            $this->reflectProperties($class);
        }
    }

    /**
     * Recursively reflect the object properties for a particular class
     *
     * @param \ReflectionClass $class Class reflection
     */
    protected function reflectProperties(\ReflectionClass $class)
    {
        $classMap = [];

        // Run through all reflection properties
        foreach ($class->getProperties() as $property) {
            // If the property is defined by the current class
            if ($property->getDeclaringClass()->name === $class->name) {
                $property->setAccessible(true);
                $classMap[$property->name] = $property->getValue($this->object);
                $property->setAccessible(false);
            }
        }

        $this->map[$class->name] = $classMap;

        // Recurse if necessary
        $parentClass = $class->getParentClass();
        if (($parentClass instanceof \ReflectionClass) && !$parentClass->isInternal()) {
            $this->reflectProperties($parentClass);
        }
    }

    /**
     * Return the elevation map
     *
     * @return array Elevation map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Return the property values for a particular class
     *
     * @param \ReflectionClass $class Class reflection
     * @return array Property values
     */
    public function getPropertyValues(\ReflectionClass $class)
    {
        $className = $class->name;
        return isset($this->map[$className]) ? $this->map[$className] : [];
    }
}
