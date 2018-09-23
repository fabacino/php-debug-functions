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
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

/**
 * Tests for function `dbglog`.
 */
class DbglogTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test printing number with log file.
     *
     * @return void
     */
    public function testLogNumber()
    {
        $this->initWithLogFile();
        $this->execTestLogNumber();
    }

    /**
     * Test printing string with log file.
     *
     * @return void
     */
    public function testLogString()
    {
        $this->initWithLogFile();
        $this->execTestLogString();
    }

    /**
     * Test printing output with log file.
     *
     * @return void
     */
    public function testLogArray()
    {
        $this->initWithLogFile();
        $this->execTestLogArray();
    }

    /**
     * Test printing number with logger.
     *
     * @return void
     */
    public function testLogNumberWithLogger()
    {
        $this->initWithLogger();
        $this->execTestLogNumber();
    }

    /**
     * Test printing string with logger.
     *
     * @return void
     */
    public function testLogStringWithLogger()
    {
        $this->initWithLogger();
        $this->execTestLogString();
    }

    /**
     * Test printing output with logger.
     *
     * @return void
     */
    public function testLogArrayWithLogger()
    {
        $this->initWithLogger();
        $this->execTestLogArray();
    }

    /**
     * Test printing number.
     *
     * @return void
     */
    private function execTestLogNumber()
    {
        $var = 123;
        $this->assertRegExp($this->makePattern($var), $this->captureOutput($var));
    }

    /**
     * Test printing string.
     *
     * @return void
     */
    private function execTestLogString()
    {
        $var = 'some string';
        $this->assertRegExp($this->makePattern($var), $this->captureOutput($var));
    }

    /**
     * Test printing output.
     *
     * @return void
     */
    private function execTestLogArray()
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
        $this->assertRegExp($this->makePattern($expected), $this->captureOutput($var));
    }

    /**
     * Init debug settings with log file.
     *
     * @return void
     */
    private function initWithLogFile()
    {
        dbginit([
            'log_file' => 'php://output'
        ]);
    }

    /**
     * Init debug settings with logger.
     *
     * @return void
     */
    private function initWithLogger()
    {
        $Stream = new StreamHandler('php://output', Logger::DEBUG);
        $Stream->setFormatter(
            new LineFormatter(Debug::OUTPUT_FORMAT, Debug::DATE_FORMAT, true)
        );

        $Logger = new Logger(Debug::CHANNEL_NAME);
        $Logger->pushHandler($Stream);

        dbginit([
            'logger' => $Logger,
        ]);
    }

    /**
     * Capture and return output of function `dbglog`.
     *
     * @param mixed  $var    The variable to analyse.
     * @param int    $flags  Flags for tweaking the output.
     *
     * @return string
     */
    private function captureOutput($var, int $flags = null)
    {
        ob_start();
        dbglog($var, $flags);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Make regexp pattern for variable.
     *
     * @param mixed  $var  The variable to analyse.
     *
     * @return string
     */
    private function makePattern($var)
    {
        $search = [
            '%datetime%',
            '%message%'
        ];
        $replace = [
            '\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}',
            preg_quote($var, '/')
        ];
        return '/' . str_replace($search, $replace, Debug::OUTPUT_FORMAT) . '/';
    }
}
