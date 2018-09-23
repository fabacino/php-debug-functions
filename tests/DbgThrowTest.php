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
    /**
     * Test printing number.
     *
     * @return void
     */
    public function testThrowNumber()
    {
        $var = 123;
        $this->assertEquals($var, $this->captureException($var));
    }

    /**
     * Test printing string.
     *
     * @return void
     */
    public function testThrowString()
    {
        $var = 'some string';
        $this->assertEquals($var, $this->captureException($var));
    }

    /**
     * Test printing output.
     *
     * @return void
     */
    public function testPrintArray()
    {
        $var = ['first', 'second', 'third'];
        $expected = <<<'EOT'
Array
(
    [0] => first
    [1] => second
    [2] => third
)

EOT;
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
