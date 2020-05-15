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

class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function testReactionOnDebug(): void
    {
        $logfile = TestHelper::createTempFile();

        $var = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->debug($var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            TestHelper::readLogfile($logfile)
        );
    }

    public function testReactionOnLogWithDebugLevel(): void
    {
        $logfile = TestHelper::createTempFile();

        $var = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->log(LogLevel::DEBUG, $var);

        self::assertRegExp(
            TestHelper::makePattern($var),
            TestHelper::readLogfile($logfile)
        );
    }

    public function testReactionOnNonDebug(): void
    {
        $logfile = TestHelper::createTempFile();

        $var = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->emergency($var);
        $logger->alert($var);
        $logger->critical($var);
        $logger->error($var);
        $logger->warning($var);
        $logger->notice($var);
        $logger->info($var);

        self::assertEmpty(TestHelper::readLogfile($logfile));
    }

    public function testReactionOnLogWithNonDebugLevel(): void
    {
        $logfile = TestHelper::createTempFile();

        $var = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger($logfile);
        $logger->log(LogLevel::EMERGENCY, $var);
        $logger->log(LogLevel::ALERT, $var);
        $logger->log(LogLevel::CRITICAL, $var);
        $logger->log(LogLevel::ERROR, $var);
        $logger->log(LogLevel::WARNING, $var);
        $logger->log(LogLevel::NOTICE, $var);
        $logger->log(LogLevel::INFO, $var);

        self::assertEmpty(TestHelper::readLogfile($logfile));
    }

    public function testWithNoLogFile(): void
    {
        $logfile = TestHelper::createTempFile();

        $var = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $logger = new Logger();
        $logger->debug($var);

        self::assertEmpty(TestHelper::readLogfile($logfile));
    }
}
