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

use Fbn\Debug\Logger;
use Psr\Log\LoggerInterface;

class Debug
{
    /**
     * Available flags for tweaking the output.
     */
    public const USE_VARDUMP = 1;
    public const USE_HTMLENTITIES = 2;

    /**
     * Default flags for tweaking the output.
     *
     * @var int
     */
    private $defaultFlags;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Singleton instance.
     *
     * @var Debug
     */
    private static $instance;

    /**
     * @param int $defaultFlags Default flags for tweaking the output.
     * @param LoggerInterface $logger
     */
    final private function __construct(
        int $defaultFlags,
        LoggerInterface $logger
    ) {
        $this->defaultFlags = $defaultFlags;
        $this->logger = $logger;
    }

    /**
     * Get singleton instance.
     */
    public static function getInstance(): Debug
    {
        if (self::$instance === null) {
            static::init();
        }
        return self::$instance;
    }

    /**
     * Initialize singleton instance.
     *
     * The following settings are available:
     *
     *  - use_vardump: Use vardump for debug output? (boolean, default = false)
     *  - use_htmlentities: Use htmlentities for debug output? (boolean, default = false)
     *  - log_file: The log file. (string, default = null)
     *  - logger: The logger to use. (LoggerInterface, default = null)
     *
     * The settings `log_file` and `logger` are only used when calling `logValue()`.
     * If `logger` is specified, `log_file` will be ignored. If neither is present,
     * nothing will be logged.
     *
     * @param array<string,mixed> $settings
     */
    public static function init(array $settings = []): void
    {
        $defaultFlags = 0;
        if (
            isset($settings['use_vardump'])
            && $settings['use_vardump'] === true
        ) {
            $defaultFlags |= self::USE_VARDUMP;
        }
        if (
            isset($settings['use_htmlentities'])
            && $settings['use_htmlentities'] === true
        ) {
            $defaultFlags |= self::USE_HTMLENTITIES;
        }

        if (
            isset($settings['logger'])
            && $settings['logger'] instanceof LoggerInterface
        ) {
            $logger = $settings['logger'];
        } else {
            if (isset($settings['log_file'])) {
                $logger = new Logger($settings['log_file']);
            } else {
                // We didn't get any file to log.
                $logger = new Logger();
            }
        }

        self::$instance = new static($defaultFlags, $logger);
    }

    /**
     * Print debug value.
     *
     * @param mixed $var The variable to analyse.
     * @param int|null $flags Flags for tweaking the output.
     */
    public function printValue($var, ?int $flags = null): void
    {
        if ($flags === null) {
            $flags = $this->defaultFlags;
        }

        $output = static::debugValue($var, $flags);
        if (!$this->isCli()) {
            $output = "<pre>{$output}</pre>";
        }
        echo $output;
    }

    /**
     * Return debug value.
     *
     * @param mixed $var The variable to analyse.
     * @param int|null $flags Flags for tweaking the output.
     * @return mixed
     */
    public function debugValue($var, ?int $flags = null)
    {
        if ($flags === null) {
            $flags = $this->defaultFlags;
        }

        ob_start();
        if (($flags & self::USE_VARDUMP) === self::USE_VARDUMP) {
            var_dump($var);
        } elseif (is_array($var) || is_object($var)) {
            print_r($var);
        } else {
            echo $var;
        }
        $output = ob_get_contents();
        ob_end_clean();

        if ($output === false) {
            throw new \UnexpectedValueException(
                'Unable to get debug value of variable'
            );
        }

        if (($flags & self::USE_HTMLENTITIES) === self::USE_HTMLENTITIES) {
            $output = htmlentities($output, ENT_NOQUOTES);
        }

        return $output;
    }

    /**
     * Log debug value.
     *
     * @param mixed $var The variable to analyse.
     * @param int|null $flags Flags for tweaking the output.
     */
    public function logValue($var, ?int $flags = null): void
    {
        $this->logger->debug(static::debugValue($var, $flags));
    }

    /**
     * Check whether we are in a CLI environment.
     */
    protected function isCli(): bool
    {
        return substr(PHP_SAPI, 0, 3) === 'cli';
    }
}
