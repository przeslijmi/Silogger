<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Usage\FlowUsage;

use Przeslijmi\Silogger\Usage\FlowUsage;

/**
 * This is current stack of logs (kept in memory) - good to use while testing.
 */
class Stack
{

    /**
     * Stack.
     *
     * @var FlowUsage[]
     */
    private static $stack = [];

    /**
     * Adds to stack.
     *
     * @param FlowUsage $log Log to be added.
     *
     * @return void
     */
    public static function add(FlowUsage $log) : void
    {

        // Lvd.
        $ssid = session_id();

        // Create stack.
        if (isset(self::$stack[$ssid]) === false) {
            self::$stack[$ssid] = [];
        }

        // Save to stack.
        self::$stack[$ssid][] = $log;
    }

    /**
     * Claers the stack.
     *
     * @return void
     */
    public static function clear() : void
    {

        // Lvd.
        $ssid = session_id();

        // Fast track.
        if (isset(self::$stack[$ssid]) === false || empty(self::$stack[$ssid]) === true) {
            return;
        }

        // Clear.
        unset(self::$stack[$ssid]);
        self::$stack[$ssid] = [];
    }

    /**
     * Returns last element from stack.
     *
     * @param integer     $laterThan Opt., 0. Ignore first `n` logs on stack.
     * @param string|null $level     Opt., null. Look only for logs with this level.
     *
     * @return null|FlowUsage
     */
    public static function getLast(int $laterThan = 0, ?string $level = null) : ?FlowUsage
    {

        // Lvd.
        $ssid = session_id();

        // Fast track.
        if (isset(self::$stack[$ssid]) === false || empty(self::$stack[$ssid]) === true) {
            return null;
        }

        // Get keys.
        $keys = array_reverse(array_keys(self::$stack[$ssid]));

        foreach ($keys as $key) {

            // Nothing has been found.
            if ($key < $laterThan) {
                return null;
            }

            if ($level !== null && $level === self::$stack[$ssid][$key]->getLevel()) {
                return self::$stack[$ssid][$key];
            } elseif ($level === null) {
                return self::$stack[$ssid][$key];
            }
        }

        return null;
    }

    /**
     * Getter for whole stack.
     *
     * @return FlowUsage[]
     */
    public static function getAll() : array
    {

        // Lvd.
        $ssid = session_id();

        // Fast track.
        if (isset(self::$stack[$ssid]) === false || empty(self::$stack[$ssid]) === true) {
            return [];
        }

        return self::$stack[$ssid];
    }

    /**
     * Checks if given error id exists in any of stacked logs.
     *
     * @param string $errorId Id of stacked log (ie. message **id**entifier).
     *
     * @return boolean
     */
    public static function isErrorIdExisting(string $errorId) : bool
    {

        // Lvd.
        $ssid = session_id();

        // Look until first has been found.
        foreach (self::$stack[$ssid] as $element) {
            if ($element->getLocale()->getClid()[1] === $errorId) {
                return true;
            }
        }

        return false;
    }
}
