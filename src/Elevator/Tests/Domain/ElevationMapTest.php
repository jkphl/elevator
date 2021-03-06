<?php

/**
 * elevator
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Elevator\Tests\Domain
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

namespace Jkphl\Elevator\Tests\Domain;

use Jkphl\Elevator\Domain\ElevationMap;
use Jkphl\Elevator\Tests\Fixture\Inner;
use Jkphl\Elevator\Tests\Fixture\Outer;

/**
 * Elevation map tests
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Tests
 */
class ElevationMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the elevation map
     */
    public function testElevationMap()
    {
        $testObject = new Outer();
        $elevationMap = new ElevationMap($testObject);
        $expectedElevationMap = json_decode(
            file_get_contents(
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Fixture' . DIRECTORY_SEPARATOR . 'outer-elevation-map.json'
            ),
            true
        );
        $this->assertEquals($expectedElevationMap, $elevationMap->getMap());
        $this->assertEquals(
            $expectedElevationMap[Inner::class],
            $elevationMap->getPropertyValues(new \ReflectionClass(Inner::class))
        );
    }

    /**
     * Test the elevation map with a standard object
     */
    public function testElevationMapStdClass()
    {
        $stdObject = new \stdClass();
        $stdObject->property = 'value';
        $elevationMap = new ElevationMap($stdObject);
        $this->assertEquals([], $elevationMap->getMap());
    }

    /**
     * Test the elevation map with a native object
     */
    public function testElevationMapNativeObject()
    {
        $arrayObject = new \ArrayObject([1, 2, 3], \ArrayObject::STD_PROP_LIST);
        $elevationMap = new ElevationMap($arrayObject);
        $this->assertEquals([], $elevationMap->getMap());
    }
}
