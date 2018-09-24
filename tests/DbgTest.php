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
        $var = 123;
        $this->assertEquals('123', $this->captureOutput($var));
    }

    public function testPrintString()
    {
        $var = 'some string';
        $this->assertEquals($var, $this->captureOutput($var));
    }

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
        $this->assertEquals($expected, $this->captureOutput($var));
    }

    public function testPrintArrayNonCli()
    {
        TestDebug::init();
        $var = ['first', 'second', 'third'];
        $expected = <<<'EOT'
<pre>Array
(
    [0] => first
    [1] => second
    [2] => third
)
</pre>
EOT;
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
