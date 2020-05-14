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
 * Tests for function `dbgr`.
 */
class DbgrTest extends \PHPUnit\Framework\TestCase
{
    public function testDebugNumber()
    {
        $var = TestHelper::randomInt();

        self::assertEquals($var, dbgr($var));
    }

    public function testDebugString()
    {
        $var = TestHelper::randomString();

        self::assertSame($var, dbgr($var));
    }

    public function testDebugArray()
    {
        $var = TestHelper::randomArray();
        $expected = TestHelper::makeArrayOutput($var);

        self::assertEquals($expected, dbgr($var));
    }

    public function testDebugStringUsingVardumpByInit()
    {
        dbginit(['use_vardump' => true]);

        $var = TestHelper::randomString();

        self::assertSame(
            $this->extractDumped($this->captureVardump($var), 'string'),
            $this->extractDumped(dbgr($var), 'string')
        );
    }

    public function testDebugStringUsingVardumpByArg()
    {
        $var = TestHelper::randomString();

        self::assertSame(
            $this->extractDumped($this->captureVardump($var), 'string'),
            $this->extractDumped(dbgr($var, Debug::USE_VARDUMP), 'string')
        );
    }

    public function testDebugStringUsingHtmlentitiesByInit()
    {
        dbginit(['use_htmlentities' => true]);

        $var = '<b>' . TestHelper::randomInt() . '<b>';

        self::assertSame(htmlentities($var), dbgr($var));
    }

    public function testDebugStringUsingHtmlentitiesByArg()
    {
        $var = '<b>' . TestHelper::randomInt() . '<b>';

        self::assertSame(htmlentities($var), dbgr($var, Debug::USE_HTMLENTITIES));
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
