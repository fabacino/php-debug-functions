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

/**
 * Tests for function `dbg`.
 */
class DbgTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintNumber()
    {
        $var = TestHelper::randomInt();

        $this->assertEquals($var, $this->captureOutput($var));
    }

    public function testPrintString()
    {
        $var = TestHelper::randomString();

        $this->assertEquals($var, $this->captureOutput($var));
    }

    public function testPrintArray()
    {
        $var = TestHelper::randomArray();
        $expected = TestHelper::makeArrayOutput($var);

        $this->assertEquals($expected, $this->captureOutput($var));
    }

    public function testPrintArrayNonCli()
    {
        TestDebug::init();

        $var = TestHelper::randomArray();
        $expected = '<pre>' . TestHelper::makeArrayOutput($var) . '</pre>';

        $this->assertEquals($expected, $this->captureOutput($var));
    }

    /**
     * Capture and return output of function `dbg`.
     *
     * @param mixed  $var    The variable to analyse.
     * @param int    $flags  Flags for tweaking the output.
     *
     * @return string
     */
    private function captureOutput($var, int $flags = null)
    {
        ob_start();
        dbg($var, $flags);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
