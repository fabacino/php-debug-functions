<?php

namespace Fabacino\Debug\Test;

/**
 * Tests for function `dbg`.
 */
class DbgTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test printing number
     */
    public function testPrintNumber()
    {
        $var = 123;
        $this->assertEquals('123', $this->captureOutput($var));
    }

    /**
     * Test printing string
     */
    public function testPrintString()
    {
        $var = 'some string';
        $this->assertEquals($var, $this->captureOutput($var));
    }

    /**
     * Test printing output
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
        $this->assertEquals($expected, $this->captureOutput($var));
    }

    /**
     * Capture and return output of function `dbg`.
     *
     * @param mixed  $var    The variable to analyse.
     * @param mixed  $flags  Flags for tweaking the output.
     *
     * @return string
     */
    private function captureOutput($var, $flags = null)
    {
        ob_start();
        dbg($var, $flags);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
