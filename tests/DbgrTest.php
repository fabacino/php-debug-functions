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

class DbgrTest extends \PHPUnit\Framework\TestCase
{
    public function testDebugNumber(): void
    {
        $var = TestHelper::randomInt();

        self::assertEquals($var, dbgr($var));
    }

    public function testDebugString(): void
    {
        $var = TestHelper::randomString();

        self::assertSame($var, dbgr($var));
    }

    public function testDebugArray(): void
    {
        $var = TestHelper::randomArray();
        $expected = TestHelper::makeArrayOutput($var);

        self::assertEquals($expected, dbgr($var));
    }

    public function testDebugStringUsingVardumpByInit(): void
    {
        dbginit(['use_vardump' => true]);

        $var = TestHelper::randomString();

        self::assertSame(
            $this->extractDumped($this->captureVardump($var), 'string'),
            $this->extractDumped(dbgr($var), 'string')
        );
    }

    public function testDebugStringUsingVardumpByArg(): void
    {
        $var = TestHelper::randomString();

        self::assertSame(
            $this->extractDumped($this->captureVardump($var), 'string'),
            $this->extractDumped(dbgr($var, Debug::USE_VARDUMP), 'string')
        );
    }

    public function testDebugStringUsingHtmlentitiesByInit(): void
    {
        dbginit(['use_htmlentities' => true]);

        $var = '<b>' . TestHelper::randomInt() . '<b>';

        self::assertSame(htmlentities($var), dbgr($var));
    }

    public function testDebugStringUsingHtmlentitiesByArg(): void
    {
        $var = '<b>' . TestHelper::randomInt() . '<b>';

        self::assertSame(htmlentities($var), dbgr($var, Debug::USE_HTMLENTITIES));
    }

    /**
     * Capture and return output of function `var_dump`.
     *
     * @param mixed $var The variable to analyse.
     */
    private function captureVardump($var): string
    {
        ob_start();
        var_dump($var);
        $output = ob_get_contents();
        ob_end_clean();

        if ($output === false) {
            throw new \UnexpectedValueException('Unable to capture output');
        }

        return $output;
    }

    /**
     * Extract relevant information from vardump output.
     *
     * @param string $output The vardump output.
     * @param string $start The string the relevant info starts with.
     */
    private function extractDumped(string $output, string $start): string
    {
        $pos = strpos($output, $start);
        return $pos !== false ? substr($output, $pos) : $output;
    }
}
