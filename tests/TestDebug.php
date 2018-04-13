<?php
/*
 * © Fabian Wiget <fabacino@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Fabacino\Debug\Test;

/**
 * Debug class for testing.
 */
class TestDebug extends \Fabacino\Debug\Debug
{
    /**
     * Check whether we are in a CLI environment.
     *
     * @return bool
     */
    protected function isCli(): bool
    {
        // PHPUnit is always executed through CLI. In order to be able to check
        // functionality which is working only in a non-CLI environment, we just
        // pretend that PHP is running in a non-CLI environment.
        return false;
    }
}
