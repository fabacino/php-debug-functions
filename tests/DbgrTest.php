<?php
/*
 * © Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Fabacino\Debug\Test;

use Fabacino\Debug\Debug;

/**
 * Tests for function `dbgr`.
 */
class DbgrTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test number output.
     *
     * @return void
     */
    public function testDebugNumber()
    {
        $var = 123;
        $this->assertSame('123', dbgr($var));
    }

    /**
     * Test string output.
     *
     * @return void
     */
    public function testDebugString()
    {
        $var = 'some string';
        $this->assertSame($var, dbgr($var));
    }

    /**
     * Test array output.
     *
     * @return void
     */
    public function testDebugArray()
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
        $this->assertEquals($expected, dbgr($var));
    }

    /**
     * Test string output setting vardump by init.
     *
     * @return void
     */
    public function testDebugStringUsingVardumpByInit()
    {
        dbginit(['use_vardump' => true]);
        $var = 'another string';
        $this->assertSame(
            $this->extractDumped($this->captureVardump($var), 'string'),
            $this->extractDumped(dbgr($var), 'string')
        );
    }

    /**
     * Test string output setting vardump by argument.
     *
     * @return void
     */
    public function testDebugStringUsingVardumpByArg()
    {
        $var = 'Some Third String';
        $this->assertSame(
            $this->extractDumped($this->captureVardump($var), 'string'),
            $this->extractDumped(dbgr($var, Debug::USE_VARDUMP), 'string')
        );
    }

    /**
     * Test string output setting htmlentities by init.
     *
     * @return void
     */
    public function testDebugStringUsingHtmlentitiesByInit()
    {
        dbginit(['use_htmlentities' => true]);
        $var = '<b>Header<b>';
        $this->assertSame(htmlentities($var), dbgr($var));
    }

    /**
     * Test string output setting htmlentities by argument.
     *
     * @return void
     */
    public function testDebugStringUsingHtmlentitiesByArg()
    {
        $var = '<b>Footer<b>';
        $this->assertSame(htmlentities($var), dbgr($var, Debug::USE_HTMLENTITIES));
    }

    /**
     * Capture and return output of function `var_dump`.
     *
     * @param mixed  $var  The variable to analyse.
     *
     * @return string
     */
    private function captureVardump($var)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Extract relevant information from vardump output.
     *
     * @param string  $output  The vardump output.
     * @param string  $start   The string the relevant info starts with.
     *
     * @return string
     */
    private function extractDumped(string $output, string $start)
    {
        $pos = strpos($output, $start);
        return $pos !== false ? substr($output, $pos) : $output;
    }
}
