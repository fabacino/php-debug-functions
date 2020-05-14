<?php

/*
 * This file is part of the fabacino/debug-functions package.
 *
 * (c) Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fbn\Debug\Test;

use Fbn\Debug\Debug;

/**
 * Tests for function `dbgthrow`.
 */
class DbgThrowTest extends \PHPUnit\Framework\TestCase
{
    public function testThrowNumber()
    {
        $var = TestHelper::randomInt();

        self::assertEquals($var, $this->captureException($var));
    }

    public function testThrowString()
    {
        $var = TestHelper::randomString();

        self::assertEquals($var, $this->captureException($var));
    }

    public function testThrowArray()
    {
        $var = TestHelper::randomArray();
        $expected = TestHelper::makeArrayOutput($var);

        self::assertEquals($expected, $this->captureException($var));
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
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        return null;
    }
}
