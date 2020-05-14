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

use Fbn\Debug\Logger;
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

        $logger = new Logger($logfile);
        $logger->debug($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testReactionOnLogWithDebugLevel()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->log(LogLevel::DEBUG, $var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            file_get_contents($logfile)
        );
    }

    public function testReactionOnNonDebug()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->emergency($var);
        $logger->alert($var);
        $logger->critical($var);
        $logger->error($var);
        $logger->warning($var);
        $logger->notice($var);
        $logger->info($var);

        self::assertEmpty(file_get_contents($logfile));
    }

    public function testReactionOnLogWithNonDebugLevel()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->log(LogLevel::EMERGENCY, $var);
        $logger->log(LogLevel::ALERT, $var);
        $logger->log(LogLevel::CRITICAL, $var);
        $logger->log(LogLevel::ERROR, $var);
        $logger->log(LogLevel::WARNING, $var);
        $logger->log(LogLevel::NOTICE, $var);
        $logger->log(LogLevel::INFO, $var);

        self::assertEmpty(file_get_contents($logfile));
    }

    public function testWithNoLogFile()
    {
        $logfile = TestHelper::createTempFile();

        $var = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $logger = new Logger();
        $logger->debug($var);

        self::assertEmpty(file_get_contents($logfile));
    }
}
