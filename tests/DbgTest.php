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

class DbgTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintNumber(): void
    {
        $var = TestHelper::randomInt();

        self::assertEquals($var, $this->captureOutput($var));
    }

    public function testPrintString(): void
    {
        $var = TestHelper::randomString();

        self::assertEquals($var, $this->captureOutput($var));
    }

    public function testPrintArray(): void
    {
        $var = TestHelper::randomArray();
        $expected = TestHelper::makeArrayOutput($var);

        self::assertEquals($expected, $this->captureOutput($var));
    }

    public function testPrintArrayNonCli(): void
    {
        TestDebug::init();

        $var = TestHelper::randomArray();
        $expected = '<pre>' . TestHelper::makeArrayOutput($var) . '</pre>';

        self::assertEquals($expected, $this->captureOutput($var));
    }

    /**
     * Capture and return output of function `dbg`.
     *
     * @param mixed $var The variable to analyse.
     * @param int|null $flags Flags for tweaking the output.
     */
    private function captureOutput($var, ?int $flags = null): string
    {
        ob_start();
        dbg($var, $flags);
        $output = ob_get_contents();
        ob_end_clean();

        if ($output === false) {
            throw new \UnexpectedValueException('Unable to capture output');
        }

        return $output;
    }
}
