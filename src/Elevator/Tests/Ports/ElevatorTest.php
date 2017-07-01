<?php

/**
 * elevator
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Elevator\Tests\Ports
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

namespace Jkphl\Elevator\Tests\Ports;

use Jkphl\Elevator\Ports\Elevator;
use Jkphl\Elevator\Tests\Fixture\Elevated;
use Jkphl\Elevator\Tests\Fixture\Outer;

/**
 * Elevator test
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Tests
 */
class ElevatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the elevator facade
     */
    public function testElevator()
    {
        $random = md5(rand());
        $outer = new Outer();
        $outer->innerPublic = $random;

        /** @var Elevated $elevated */
        $elevated = Elevator::elevate($outer, Elevated::class);
        $this->assertInstanceOf(Elevated::class, $elevated);
        $this->assertEquals($random, $elevated->innerPublic);
        $this->assertEquals('inner-private', $elevated->getInnerPrivate());
    }
}
