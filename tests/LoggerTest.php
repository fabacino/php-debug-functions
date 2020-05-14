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

use Fabacino\Debug\Logger;
use Psr\Log\LogLevel;

/**
 * Tests for the simple logger.
 */
class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function testReactionOnDebug()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $Logger = new Logger($logfile);
        $Logger->debug($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testReactionOnLogWithDebugLevel()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $Logger = new Logger($logfile);
        $Logger->log(LogLevel::DEBUG, $var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testReactionOnNonDebug()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $Logger = new Logger($logfile);
        $Logger->emergency($var);
        $Logger->alert($var);
        $Logger->critical($var);
        $Logger->error($var);
        $Logger->warning($var);
        $Logger->notice($var);
        $Logger->info($var);

        self::assertEmpty(file_get_contents($logfile));
    }

    public function testReactionOnLogWithNonDebugLevel()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $Logger = new Logger($logfile);
        $Logger->log(LogLevel::EMERGENCY, $var);
        $Logger->log(LogLevel::ALERT, $var);
        $Logger->log(LogLevel::CRITICAL, $var);
        $Logger->log(LogLevel::ERROR, $var);
        $Logger->log(LogLevel::WARNING, $var);
        $Logger->log(LogLevel::NOTICE, $var);
        $Logger->log(LogLevel::INFO, $var);

        self::assertEmpty(file_get_contents($logfile));
    }

    public function testWithNoLogFile()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $Logger = new Logger();
        $Logger->debug($var);

        self::assertEmpty(file_get_contents($logfile));
    }
}
