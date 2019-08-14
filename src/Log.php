<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\Silogger;

/**
 * Logs application work.
 */
class Log
{

    /**
     * Logs notice.
     *
     * @param string $message Message contents.
     * @param array  $context Unused.
     *
     * @return void
     */
    public static function notice(string $message, array $context = []) : void
    {

        self::log('notice', $message, $context);
    }

    /**
     * Logs info.
     *
     * @param string $message Message contents.
     * @param array  $context Unused.
     *
     * @return void
     */
    public static function info(string $message, array $context = []) : void
    {

        self::log('info', $message, $context);
    }

    /**
     * Logs any message.
     *
     * @param string $level   Name of level (see Silogger doc)..
     * @param string $message Message contents.
     * @param array  $context Unused.
     *
     * @return void
     *
     * @phpcs:disable Generic.NamingConventions.ConstructorName.OldStyle
     */
    public static function log(string $level, string $message, array $context) : void
    {

        // Lvd.
        $color = constant('\Przeslijmi\Silogger\Silogger::' . strtoupper($level) . '_COLOR');

        // Define log to be showed.
        $showLog  = "\e[" . $color . 'm';
        $showLog .= $level . ': ';
        $showLog .= $message;
        $showLog .= "\e[0m";
        $showLog .= PHP_EOL;

        echo $showLog;
    }
}
