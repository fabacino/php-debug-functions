<?php

/**
 * Settings for use in debug functions.
 *
 * @copyright Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabacino\Debug;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

class Debug
{
    /**
     * Available flags for tweaking the output.
     */
    const USE_VARDUMP = 1;
    const USE_HTMLENTITIES = 2;

    /**
     * Default values for Monolog options.
     */
    const CHANNEL_NAME = 'fabacino/debug';
    const OUTPUT_FORMAT = "%datetime%: %message%\n";
    const DATE_FORMAT = null;

    /**
     * Default flags for tweaking the output.
     *
     * @var int
     */
    private $defaultFlags;

    /**
     * Monolog logger.
     *
     * @var Logger
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
     * @param int     $defaultFlags  Default flags for tweaking the output.
     * @param Logger  $Logger        The monolog logger.
     *
     * @return void
     */
    private function __construct(int $defaultFlags, Logger $Logger)
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
     *  - monolog: Settings for Monolog logger. (array, default = [])
     *      - channel_name: The name of the channel. (string, default = self::CHANNEL_NAME)
     *      - output_format: The output format. (string, default = self::OUTPUT_FORMAT)
     *      - date_format: The date format. (string, default = self::DATE_FORMAT)
     *
     * The settings `log_file` and `monolog` are only used when calling `logValue()`.
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

        self::$Instance = new static($defaultFlags, self::initLogger($settings));
    }

    /**
     * Create Monolog logger.
     *
     * @param array  $settings  Settings.
     *
     * @return Logger
     * @see    init()
     */
    private static function initLogger(array $settings = []): Logger
    {
        if (isset($settings['monolog']) && is_array($settings['monolog'])) {
            $monologSettings = $settings['monolog'];
        } else {
            $monologSettings = [];
        }

        if (isset($settings['log_file'])) {
            // Use default values if not specified otherwise.
            if (array_key_exists('output_format', $monologSettings)) {
                $output = $monologSettings['output_format'];
            } else {
                $output = self::OUTPUT_FORMAT;
            }
            $dateFormat = $monologSettings['date_format'] ?? self::DATE_FORMAT;

            $Stream = new StreamHandler($settings['log_file'], Logger::DEBUG);
            $Stream->setFormatter(new LineFormatter($output, $dateFormat, true));
        } else {
            // We didn't get any file to log.
            $Stream = new NullHandler(Logger::DEBUG);
        }

        $Logger = new Logger($monologSettings['channel_name'] ?? self::CHANNEL_NAME);
        $Logger->pushHandler($Stream);
        return $Logger;
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
        if (PHP_SAPI !== 'cli') {
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
        $this->Logger->addDebug(static::debugValue($var, $flags));
    }
}
