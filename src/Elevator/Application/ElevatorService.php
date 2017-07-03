<?php

/**
 * elevator
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Elevator\Application
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

namespace Jkphl\Elevator\Application;

use Jkphl\Elevator\Domain\ElevationMap;
use Jkphl\Elevator\Domain\Elevator;
use Jkphl\Elevator\Ports\ElevatorAwareInterface;

/**
 * Elevator service
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Application
 */
class ElevatorService
{
    /**
     * Source object
     *
     * @var object
     */
    protected $source;

    /**
     * Constructor
     *
     * @param object $source Source object or exception
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Elevate the source object to the given target class
     *
     * @param string $class Target class name
     * @param array $args Elevation arguments
     * @return object Elevated object
     */
    public function elevate($class, ...$args)
    {
        $elevator = new Elevator($this->source);
        $elevationMap = new ElevationMap($this->source);
        $elevated = $elevator->elevate(new \ReflectionClass($class), $elevationMap);

        // Call the magic elevation method
        if ($elevated instanceof ElevatorAwareInterface) {
            $elevated->__elevate(...$args);
        }

        return $elevated;
    }
}
