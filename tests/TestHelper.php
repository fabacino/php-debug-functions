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

/**
 * Some helpger functions for tests.
 */
class TestHelper
{
    /**
     * File pointer to the last temporary file.
     *
     * @var resource
     */
    private static $fp;

    /**
     * Create temporary file.
     *
     * @return string
     */
    public static function createTempFile(): string
    {
        // Keep the file pointer around, otherwise the temp file gets deleted too soon.
        self::$fp = tmpfile();
        return stream_get_meta_data(self::$fp)['uri'];
    }

    /**
     * Make regexp pattern for variable.
     *
     * @param mixed  $var  The variable to analyse.
     *
     * @return string
     */
    public static function makePattern($var, string $dateFormat = Logger::DATE_FORMAT): string
    {
        $replace = [
            'd' => '\d{2}',
            'm' => '\d{2}',
            'Y' => '\d{4}',
            'H' => '\d{2}',
            'i' => '\d{2}',
            's' => '\d{2}',
            'u' => '\d+',
        ];
        $datePattern = str_replace(
            array_keys($replace),
            array_values($replace),
            preg_quote($dateFormat, '/')
        );
        return '/' . $datePattern . ': ' . preg_quote($var, '/') . '/';
    }
}
