<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\Log\Definition;
use Przeslijmi\Silogger\Silogger;
use Przeslijmi\Silogger\Usage\CliUsage;
use Przeslijmi\Silogger\Usage\FileUsage;

/**
 * Logs application work.
 */
class Log extends Definition
{

    /**
     * Static Log factory.
     *
     * @param string $logName Name of Log. If not given - default Log is delivered.
     *
     * @since  v1.0
     * @return Log
     */
    public static function get(string $logName = 'default') : Log
    {

        return Silogger::get($logName);
    }

    /**
     * Logs emergency.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function emergency($message, array $context = []) : void
    {

        self::log('emergency', $message, $context);
    }

    /**
     * Logs alert.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function alert($message, array $context = []) : void
    {

        self::log('alert', $message, $context);
    }

    /**
     * Logs critical.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function critical($message, array $context = []) : void
    {

        self::log('critical', $message, $context);
    }

    /**
     * Logs error.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function error($message, array $context = []) : void
    {

        self::log('error', $message, $context);
    }

    /**
     * Logs warning.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function warning($message, array $context = []) : void
    {

        self::log('warning', $message, $context);
    }

    /**
     * Logs notice.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function notice($message, array $context = []) : void
    {

        self::log('notice', $message, $context);
    }

    /**
     * Logs info.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function info($message, array $context = []) : void
    {

        self::log('info', $message, $context);
    }

    /**
     * Logs info.
     *
     * @param mixed $message Message contents.
     * @param array $context Unused.
     *
     * @return void
     */
    public function debug($message, array $context = []) : void
    {

        self::log('debug', $message, $context);
    }

    /**
     * Logs any message.
     *
     * @param string $level   Name of level (see Silogger doc).
     * @param mixed  $message Message contents.
     * @param array  $context Unused.
     *
     * @return void
     *
     * @phpcs:disable Generic.NamingConventions.ConstructorName.OldStyle
     */
    public function log(string $level, $message, array $context = []) : void
    {

        // Ingore context.
        unset($context);

        // Convert message to final string.
        if (is_scalar($message) === false
            && ( is_object($message) === true && method_exists($message, '__toString') === false )
        ) {
            $message = 'Err: Sent message can not be converted to string.';
        } else {
            $message = (string) $message;
        }

        // Check usages.
        if ($this->isFor('cli', $level) !== null) {
            new CliUsage($this, $level, $message);
        }
        if ($this->isFor('file', $level) !== null) {
            new FileUsage($this, $level, $message);
        }
    }
}
