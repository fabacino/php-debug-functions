<?php
/*
 * © Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

/**
 * Debug functions.
 */

use Fabacino\Debug\Debug;

/**
 * Initialize singleton instance.
 *
 * @param array  $settings  Settings.
 *
 * @return void
 * @see    Debug::init()
 */
function dbginit(array $settings = [])
{
    Debug::init($settings);
}

/**
 * Print debug value
 *
 * @param mixed     $var    The variable to analyse.
 * @param int|null  $flags  Flags for tweaking the output.
 *
 * @return void
 */
function dbg($var, int $flags = null)
{
    Debug::getInstance()->printValue($var, $flags);
}

/**
 * Return debug value.
 *
 * @param mixed     $var    The variable to analyse.
 * @param int|null  $flags  Flags for tweaking the output.
 *
 * @return mixed
 */
function dbgr($var, int $flags = null)
{
    return Debug::getInstance()->debugValue($var, $flags);
}

/**
 * Log debug value.
 *
 * @param mixed     $var    The variable to analyse.
 * @param int|null  $flags  Flags for tweaking the output.
 *
 * @return void
 */
function dbglog($var, int $flags = null)
{
    Debug::getInstance()->logValue($var, $flags);
}

/**
 * Print debug value and die.
 *
 * @param mixed     $var    The variable to analyse.
 * @param int|null  $flags  Flags for tweaking the output.
 *
 * @return void
 * @codeCoverageIgnore
 */
function dbgdie($var, int $flags = null)
{
    dbg($var, $flags);
    exit;
}

/**
 * Throw exception with debug value.
 *
 * @param mixed     $var    The variable to analyse.
 * @param int|null  $flags  Flags for tweaking the output.
 *
 * @return void
 */
function dbgthrow($var, int $flags = null)
{
    throw new \Exception(dbgr($var, $flags));
}
