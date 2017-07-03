<?php

/**
 * elevator
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Elevator\Tests\Fixture
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

namespace Jkphl\Elevator\Tests\Fixture;

use Jkphl\Elevator\Ports\ElevatorAwareInterface;

/**
 * Elevated test class
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Tests
 */
class Elevated extends Outer implements ElevatorAwareInterface
{
    /**
     * Property modified by the pseudo-constructor
     *
     * @var string
     */
    public $elevatedMagic = null;
    /**
     * Public property
     *
     * @var string
     */
    public $elevatedPublic = 'elevated-public';
    /**
     * Private property
     *
     * @var string
     */
    protected $elevatedPrivate = 'elevated-private';
    /**
     * Protected property
     *
     * @var string
     */
    protected $elevatedProtected = 'elevated-protected';

    /**
     * Custom elevation pseudo constructor
     *
     * @param array ...$args Elevation arguments
     */
    public function __elevate(...$args)
    {
        if (count($args)) {
            $this->elevatedMagic = strval($args[0]);
        }
    }
}
