<?php

/**
 * Debug functions.
 *
 * @copyright Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
