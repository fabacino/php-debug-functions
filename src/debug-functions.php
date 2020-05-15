<?php

/*
 * This file is part of the fabacino/debug-functions package.
 *
 * (c) Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Fbn\Debug\Debug;

/**
 * Initialize singleton instance.
 *
 * @param array<string,mixed> $settings
 * @see Debug::init()
 */
function dbginit(array $settings = []): void
{
    Debug::init($settings);
}

/**
 * Print debug value.
 *
 * @param mixed $var The variable to analyse.
 * @param int|null $flags Flags for tweaking the output.
 */
function dbg($var, ?int $flags = null): void
{
    Debug::getInstance()->printValue($var, $flags);
}

/**
 * Return debug value.
 *
 * @param mixed $var The variable to analyse.
 * @param int|null $flags Flags for tweaking the output.
 * @return mixed
 */
function dbgr($var, ?int $flags = null)
{
    return Debug::getInstance()->debugValue($var, $flags);
}

/**
 * Log debug value.
 *
 * @param mixed $var The variable to analyse.
 * @param int|null $flags Flags for tweaking the output.
 */
function dbglog($var, int $flags = null): void
{
    Debug::getInstance()->logValue($var, $flags);
}

/**
 * Print debug value and die.
 *
 * @param mixed $var The variable to analyse.
 * @param int|null $flags Flags for tweaking the output.
 * @codeCoverageIgnore
 */
function dbgdie($var, ?int $flags = null): void
{
    dbg($var, $flags);
    exit;
}

/**
 * Throw exception with debug value.
 *
 * @param mixed $var The variable to analyse.
 * @param int|null $flags Flags for tweaking the output.
 */
function dbgthrow($var, int $flags = null): void
{
    throw new \Exception(dbgr($var, $flags));
}
