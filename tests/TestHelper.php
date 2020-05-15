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
     */
    public static function randomInt(): int
    {
        return random_int(PHP_INT_MIN, PHP_INT_MAX);
    }

    /**
     * Generate a random string.
     */
    public static function randomString(): string
    {
        $numChars = random_int(1, 340);
        return substr(base64_encode(random_bytes(256)), 0, $numChars);
    }

    /**
     * Generate a random array.
     *
     * @return array<int,string>
     */
    public static function randomArray(): array
    {
        $numEntries = random_int(5, 25);

        $entries = [];
        do {
            $entries[self::randomInt()] = self::randomString();
        } while (--$numEntries > 0);

        // Probably a bug in phpstan, the following error is reported:
        // Unreachable statement - code above always terminates.
        /** @phpstan-ignore-next-line */
        return $entries;
    }

    /**
     * Create temporary file.
     */
    public static function createTempFile(): string
    {
        // Keep the file pointer around, otherwise the temp file gets
        // deleted too soon.
        $fp = tmpfile();
        if ($fp === false) {
            throw new \UnexpectedValueException(
                'Unable to generate temporary file'
            );
        }

        self::$fp = $fp;
        return stream_get_meta_data(self::$fp)['uri'];
    }

    /**
     * Create regexp pattern for variable.
     *
     * @param mixed $var The variable to analyse.
     */
    public static function makePattern(
        $var,
        string $dateFormat = Logger::DATE_FORMAT
    ): string {
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

        return "/{$datePattern}: " . preg_quote($var, '/') . '/';
    }

    /**
     * Create expected output for an array.
     *
     * @param mixed[] $entries The array to analyse.
     */
    public static function makeArrayOutput(array $entries): string
    {
        $output = preg_replace('/\R/', "\n", str_replace(
            '{{entries}}',
            implode(PHP_EOL, array_map(
                function ($key, $value): string {
                    return "    [{$key}] => {$value}";
                },
                array_keys($entries),
                array_values($entries)
            )),
            <<<'EOT'
Array
(
{{entries}}
)

EOT
        ));

        if ($output === null) {
            throw new \UnexpectedValueException(
                'Error while replacing newlines in array output'
            );
        }

        return $output;
    }

    /**
     * Read logfile contents.
     */
    public static function readLogfile(string $logfile): string
    {
        $contents = file_get_contents($logfile);

        if ($contents === false) {
            throw new \UnexpectedValueException(
                "Unable to read log file: `{$logfile}`"
            );
        }

        return $contents;
    }
}
