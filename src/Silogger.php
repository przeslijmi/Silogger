<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

/**
 * Contains Log levels.
 */
class Silogger
{

    /**
     * Log levels.
     *
     * @var string
     */
    const ALL       = [
        'EMERGENCY',
        'ALERT',
        'CRITICAL',
        'ERROR',
        'WARNING',
        'NOTICE',
        'INFO',
        'DEBUG',
    ];
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    /**
     * Set of created Logs.
     *
     * @var Log[]
     */
    private static $logs = [];

    /**
     * Delivers Log of given name to add messages.
     *
     * @param string $name Optional. Name of Log. If not given - default Log is delivered.
     *
     * @return Log
     */
    public static function get(string $name = 'default') : Log
    {

        // Create if not exists.
        if (isset(self::$logs[$name]) === false) {
            self::$logs[$name] = new Log($name);
        }

        return self::$logs[$name];
    }

    /**
     * Declare new Log (mainly by configuration at startup).
     *
     * @param string $name       Name of Log.
     * @param array  $definition Definition of Log.
     *
     * @return Log
     */
    public static function declare(string $name, array $definition) : Log
    {

        $log = self::get($name);
        $log->setDefinition($definition);

        return $log;
    }
}
