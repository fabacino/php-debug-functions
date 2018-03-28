<?php

namespace Fabacino\Debug\Test;

/**
 * Tests for function `dbg`.
 */
class DbgTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test printing number.
     *
     * @return void
     */
    public function testPrintNumber()
    {
        $var = 123;
        $this->assertEquals('123', $this->captureOutput($var));
    }

    /**
     * Test printing string.
     *
     * @return void
     */
    public function testPrintString()
    {
        $var = 'some string';
        $this->assertEquals($var, $this->captureOutput($var));
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
        $this->assertEquals($expected, $this->captureOutput($var));
    }

    /**
     * Test printing output in non-CLI environment.
     *
     * @return void
     */
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
