<?php

/*
 * This file is part of the php-debug-functions package.
 *
 * (c) Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabacino\Debug\Test;

use Fabacino\Debug\Debug;

/**
 * Tests for function `dbgthrow`.
 */
class DbgThrowTest extends \PHPUnit\Framework\TestCase
{
    public function testThrowNumber()
    {
        $var = TestHelper::randomInt();

        $this->assertEquals($var, $this->captureException($var));
    }

    public function testThrowString()
    {
        $var = TestHelper::randomString();

        $this->assertEquals($var, $this->captureException($var));
    }

    public function testThrowArray()
    {
        $var = TestHelper::randomArray();
        $expected = TestHelper::makeArrayOutput($var);

        $this->assertEquals($expected, $this->captureException($var));
    }

    /**
     * Capture and return output of function `dbg`.
     *
     * @param mixed  $var    The variable to analyse.
     * @param int    $flags  Flags for tweaking the output.
     *
     * @return string|null
     */
    private function captureException($var, int $flags = null)
    {
        try {
            dbgthrow($var, $flags);
        } catch (\Exception $Exception) {
            return $Exception->getMessage();
        }
        return null;
    }
}
