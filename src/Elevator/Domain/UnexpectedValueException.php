<?php

/**
 * elevator
 *
 * @category Jkphl
 * @package Jkphl\Micrometa
 * @subpackage Jkphl\Elevator\Domain
 * @author Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright Copyright © 2017 Joschi Kuphal <joschi@kuphal.net> / @jkphl
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
 * Unexpected value exception
 *
 * @package Jkphl\Elevator
 * @subpackage Jkphl\Elevator\Domain
 */
class UnexpectedValueException extends \UnexpectedValueException
{
    /**
     * Object required
     *
     * @var string
     */
    const OBJECT_REQUIRED_STR = 'Only objects can be elevated';
    /**
     * Object required
     *
     * @var int
     */
    const OBJECT_REQUIRED = 1497459727;
    /**
     * Non-internal class required
     *
     * @var string
     */
    const NON_INTERNAL_REQUIRED_STR = 'Internal class "%s" cannot be elevated';
    /**
     * Non-internal class required
     *
     * @var int
     */
    const NON_INTERNAL_REQUIRED = 1497460006;
    /**
     * Invalid target class
     *
     * @var string
     */
    const INVALID_TARGET_CLASS_STR = 'Target class "%s" must extend "%s"';
    /**
     * Invalid target class
     *
     * @var int
     */
    const INVALID_TARGET_CLASS = 1497460391;
}
