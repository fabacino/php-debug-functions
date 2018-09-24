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

use Fabacino\Debug\Logger;
use Psr\Log\LoggerInterface;

/**
 * Main class.
 */
class Debug
{
    /**
     * Available flags for tweaking the output.
     */
    const USE_VARDUMP = 1;
    const USE_HTMLENTITIES = 2;

    /**
     * Default flags for tweaking the output.
     *
     * @var int
     */
    private $defaultFlags;

    /**
     * The logger instance.
     *
     * @var LoggerInterface
     */
    private $Logger;

    /**
     * Singleton instance.
     *
     * @var Debug
     */
    private static $Instance;

    /**
     * Constructor.
     *
     * @param int              $defaultFlags  Default flags for tweaking the output.
     * @param LoggerInterface  $Logger        The logger instance.
     *
     * @return void
     */
    private function __construct(int $defaultFlags, LoggerInterface $Logger)
    {
        $this->defaultFlags = $defaultFlags;
        $this->Logger = $Logger;
    }

    /**
     * Get singleton instance.
     *
     * @return Debug
     */
    public static function getInstance(): Debug
    {
        if (self::$Instance === null) {
            static::init();
        }
        return self::$Instance;
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
     * @param array  $settings  Settings.
     *
     * @return void
     */
    public static function init(array $settings = [])
    {
        $defaultFlags = 0;

        if (isset($settings['use_vardump']) && $settings['use_vardump']) {
            $defaultFlags |= self::USE_VARDUMP;
        }
        if (isset($settings['use_htmlentities']) && $settings['use_htmlentities']) {
            $defaultFlags |= self::USE_HTMLENTITIES;
        }

        if (isset($settings['logger']) && $settings['logger'] instanceof LoggerInterface) {
            $Logger = $settings['logger'];
        } else {
            if (isset($settings['log_file'])) {
                $Logger = new Logger($settings['log_file']);
            } else {
                // We didn't get any file to log.
                $Logger = new Logger();
            }
        }

        self::$Instance = new static($defaultFlags, $Logger);
    }

    /**
     * Print debug value
     *
     * @param mixed     $var    The variable to analyse.
     * @param int|null  $flags  Flags for tweaking the output.
     *
     * @return void
     */
    public function printValue($var, int $flags = null)
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
     * @param mixed     $var    The variable to analyse.
     * @param int|null  $flags  Flags for tweaking the output.
     *
     * @return mixed
     */
    public function debugValue($var, int $flags = null)
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

        if (($flags & self::USE_HTMLENTITIES) === self::USE_HTMLENTITIES) {
            $output = htmlentities($output, ENT_NOQUOTES);
        }

        return $output;
    }

    /**
     * Log debug value.
     *
     * @param mixed     $var    The variable to analyse.
     * @param int|null  $flags  Flags for tweaking the output.
     *
     * @return mixed
     */
    public function logValue($var, int $flags = null)
    {
        $this->Logger->debug(static::debugValue($var, $flags));
    }

    /**
     * Check whether we are in a CLI environment.
     *
     * @return bool
     */
    protected function isCli(): bool
    {
        return substr(PHP_SAPI, 0, 3) === 'cli';
    }
}
