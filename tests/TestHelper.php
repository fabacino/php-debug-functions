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
     * Generate a random int.
     *
     * @return int
     */
    public static function randomInt(): int
    {
        return random_int(PHP_INT_MIN, PHP_INT_MAX);
    }

    /**
     * Generate a random string.
     *
     * @return string
     */
    public static function randomString(): string
    {
        $numChars = random_int(1, 340);
        return substr(base64_encode(random_bytes(256)), 0, $numChars);
    }

    /**
     * Generate a random array.
     *
     * @return array
     */
    public static function randomArray(): array
    {
        $entries = [];
        $numEntries = random_int(5, 25);
        while ($numEntries-- > 0) {
            $entries[self::randomInt()] = self::randomString();
        }
        return $entries;
    }

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
     * Create regexp pattern for variable.
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

    /**
     * Create expected output for an array.
     *
     * @param array  $entries  The array to analyse.
     *
     * @return string
     */
    public static function makeArrayOutput(array $entries): string
    {
        $output = '';
        foreach ($entries as $key => $value) {
            $output .= <<<EOT
    [{$key}] => {$value}

EOT;
        }
        $output = rtrim($output);

        return <<<EOT
Array
(
{$output}
)

EOT;
    }
}
