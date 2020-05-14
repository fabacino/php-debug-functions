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

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertRegExp(
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

        $var = TestHelper::randomString();
        dbglog($var);

        self::assertRegExp(
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

        $var = TestHelper::randomArray();
        dbglog($var);

        $expected = TestHelper::makeArrayOutput($var);
        self::assertRegExp(
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

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertEmpty(file_get_contents($logfile));
    }

    public function testLogNumberWithLogger()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testLogStringWithLogger()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = TestHelper::randomString();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testLogArrayWithLogger()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = TestHelper::randomArray();
        dbglog($var);

        $expected = TestHelper::makeArrayOutput($var);

        self::assertRegExp(
            TestHelper::makePattern($expected),
            file_get_contents($logfile)
        );
    }

    public function testLogWithLoggerAndCustomDateFormat()
    {
        $logfile = TestHelper::createTempFile();
        $dateFormat = 'Y/m/d H/i/s';
        dbginit([
            'logger' => new Logger($logfile, $dateFormat)
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var, $dateFormat),
            file_get_contents($logfile)
        );
    }

    public function testLogWithNoLogger()
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => null
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertEmpty(file_get_contents($logfile));
    }
}
