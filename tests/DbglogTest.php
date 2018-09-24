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
use Fabacino\Debug\Logger;

/**
 * Tests for function `dbglog`.
 */
class DbglogTest extends \PHPUnit\Framework\TestCase
{
    public function testLogNumberWithLogFile()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => $logfile
        ]);

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        dbglog($var);

        $this->assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testLogStringWithLogFile()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => $logfile
        ]);

        $var = base64_encode(random_bytes(24));
        dbglog($var);

        $this->assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testLogArrayWithLogFile()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => $logfile
        ]);

        $var = ['first', 'second', 'third'];
        dbglog($var);

        $expected = <<<'EOT'
Array
(
    [0] => first
    [1] => second
    [2] => third
)

EOT;
        $this->assertRegExp(
            TestHelper::makePattern($expected),
            file_get_contents($logfile)
        );
    }

    public function testLogWithNoLogFile()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => null
        ]);

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        dbglog($var);

        $this->assertEmpty(file_get_contents($logfile));
    }

    public function testLogNumberWithLogger()
    {
        $fp = tmpfile();
        $logfile = stream_get_meta_data($fp)['uri'];
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        dbglog($var);

        $this->assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testLogStringWithLogger()
    {
        $fp = tmpfile();
        $logfile = stream_get_meta_data($fp)['uri'];
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = base64_encode(random_bytes(24));
        dbglog($var);

        $this->assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testLogArrayWithLogger()
    {
        $fp = tmpfile();
        $logfile = stream_get_meta_data($fp)['uri'];
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = ['first', 'second', 'third'];
        dbglog($var);

        $expected = <<<'EOT'
Array
(
    [0] => first
    [1] => second
    [2] => third
)

EOT;
        $this->assertRegExp(
            TestHelper::makePattern($expected),
            file_get_contents($logfile)
        );
    }

    public function testLogWithLoggerAndCustomDateFormat()
    {
        $fp = tmpfile();
        $logfile = stream_get_meta_data($fp)['uri'];
        $dateFormat = 'Y/m/d H/i/s';
        dbginit([
            'logger' => new Logger($logfile, $dateFormat)
        ]);

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        dbglog($var);

        $this->assertRegExp(
            TestHelper::makePattern($var, $dateFormat),
            file_get_contents($logfile)
        );
    }

    public function testLogWithNoLogger()
    {
        $fp = tmpfile();
        $logfile = stream_get_meta_data($fp)['uri'];
        dbginit([
            'logger' => null
        ]);

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        dbglog($var);

        $this->assertEmpty(file_get_contents($logfile));
    }
}
