<?php

/*
 * This file is part of the php-debug-functions package.
 *
 * (c) Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabacino\Debug;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Very simple logger.
 */
class Logger implements LoggerInterface
{
    /**
     * Default date format.
     */
    const DATE_FORMAT = 'Y-m-d H:i:s.u';

    /**
     * Logfile.
     *
     * @var string|null
     */
    private $logfile;

    /**
     * Date format.
     *
     * @var string
     * @see DATE_FORMAT
     */
    private $dateFormat;

    /**
     * Constructor.
     *
     * @param string|null  $logfile     The log file.
     * @param string       $dateFormat  The date format.
     *
     * @return void
     */
    public function __construct(
        string $logfile = null,
        string $dateFormat = self::DATE_FORMAT
    ) {
        $this->logfile = $logfile;
        $this->dateFormat = $dateFormat;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        if ($this->logfile == null) {
            return;
        }

        $timestamp = (new \DateTime())->format($this->dateFormat);
        file_put_contents(
            $this->logfile,
            "{$timestamp}: {$message}" . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if ($level === LogLevel::DEBUG) {
            $this->debug($message, $context);
        }
    }
}
