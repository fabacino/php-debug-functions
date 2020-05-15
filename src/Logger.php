<?php

/*
 * This file is part of the fabacino/debug-functions package.
 *
 * (c) Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fbn\Debug;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Very simple logger.
 */
final class Logger extends AbstractLogger
{
    /**
     * Default date format.
     */
    public const DATE_FORMAT = 'Y-m-d H:i:s.u';

    /**
     * @var string|null
     */
    private $logfile;

    /**
     * @var string
     * @see DATE_FORMAT
     */
    private $dateFormat;

    /**
     * @param string|null $logfile
     * @param string $dateFormat
     */
    public function __construct(
        string $logfile = null,
        string $dateFormat = self::DATE_FORMAT
    ) {
        $this->logfile = $logfile;
        $this->dateFormat = $dateFormat;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param mixed[] $context
     */
    public function log($level, $message, array $context = []): void
    {
        if ($level !== LogLevel::DEBUG || $this->logfile == null) {
            return;
        }

        $timestamp = (new \DateTime())->format($this->dateFormat);
        file_put_contents(
            $this->logfile,
            "{$timestamp}: {$message}" . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}
