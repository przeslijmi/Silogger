<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Usage\FlowUsage;

use Przeslijmi\Silogger\Usage\FlowUsage;

/**
 * Works on FLOW usage of Log message.
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

    public static function clear() : void
    {

        // Lvd.
        $ssid = session_id();

        // Clear.
        unset(self::$stack[$ssid]);
        self::$stack[$ssid] = [];
    }

    /**
     * Returns last element from stack.
     *
     * @return null|FlowUsage
     */
    public static function getLast(int $laterThan = 0, ?string $level = null) : ?FlowUsage
    {

        // Lvd.
        $ssid = session_id();

        // Return null.
        if (isset(self::$stack[$ssid]) === false) {
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

    public static function getAll() : array
    {

        // Lvd.
        $ssid = session_id();

        return self::$stack[$ssid];
    }

    public static function isErrorIdExisting(string $errorId) : bool
    {

        // Lvd.
        $ssid = session_id();

        foreach (self::$stack[$ssid] as $element) {
            if ($element->getLocale()->getClid()[1] === $errorId) {
                return true;
            }
        }

        return false;
    }
}
