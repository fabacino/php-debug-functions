<?php

namespace Fabacino\Debug\Test;

/**
 * Tests for function `dbgr`.
 */
class DbgrTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test number output
     */
    public function testDebugNumber()
    {
        $var = 123;
        $this->assertSame('123', dbgr($var));
    }

    /**
     * Test string output
     */
    public function testDebugString()
    {
        $var = "some string";
        $this->assertSame($var, dbgr($var));
    }

    /**
     * Test array output
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
}
