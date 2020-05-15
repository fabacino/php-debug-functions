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
use Fbn\Debug\Logger;

class DbglogTest extends \PHPUnit\Framework\TestCase
{
    public function testLogNumberWithLogFile(): void
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => $logfile
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogStringWithLogFile(): void
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => $logfile
        ]);

        $var = TestHelper::randomString();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogArrayWithLogFile(): void
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
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogWithNoLogFile(): void
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'log_file' => null
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertEmpty(TestHelper::readLogfile($logfile));
    }

    public function testLogNumberWithLogger(): void
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogStringWithLogger(): void
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => new Logger($logfile)
        ]);

        $var = TestHelper::randomString();
        dbglog($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogArrayWithLogger(): void
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
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogWithLoggerAndCustomDateFormat(): void
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
            TestHelper::readLogfile($logfile)
        );
    }

    public function testLogWithNoLogger(): void
    {
        $logfile = TestHelper::createTempFile();
        dbginit([
            'logger' => null
        ]);

        $var = TestHelper::randomInt();
        dbglog($var);

        self::assertEmpty(TestHelper::readLogfile($logfile));
    }
}
