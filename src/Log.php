<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\Log\Definition;
use Przeslijmi\Silogger\Silogger;
use Przeslijmi\Silogger\Usage\CliUsage;
use Przeslijmi\Silogger\Usage\FileUsage;
use Przeslijmi\Silogger\Usage\FlowUsage;

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

    public function localeLog(string $level, string $class, string $id, array $fields = [], array $context = []) : void
    {

        // Convert args.
        $args = htmlspecialchars((string) json_encode($fields));

        $this->log($level, '<locale class="' . $class . '" id="' . $id . '" args="' . $args . '" />', $context);
    }

    /**
     * Logs any message.
     *
     * @param string $level   Name of level (see Silogger doc).
     * @param mixed  $message Message contents.
     * @param array  $context Extra information to save to log.
     *
     * @return void
     *
     * @phpcs:disable Generic.NamingConventions.ConstructorName.OldStyle
     * @phpcs:disable Squiz.PHP.DiscouragedFunctions
     */
    public function log(string $level, $message, array $context = []) : void
    {

        // Lvd.
        $contextHash = null;
        $level       = strtolower($level);

        // Convert message to final string.
        if (is_scalar($message) === false
            && ( is_object($message) === true && method_exists($message, '__toString') === false )
        ) {
            $message = 'Err: Sent message can not be converted to string.';
        } else {
            $message = (string) $message;
        }

        // Define context.
        if (empty($context) === false) {
            $contextHash = (string) crc32(microtime());
        }

        // Check if this is just buffered.
        $thisIsBuffer = false;
        if (substr($level, -6) === 'buffer') {
            $level        = substr($level, 0, -6);
            $thisIsBuffer = true;
        }

        // Check usages.
        if ($this->isFor('cli', $level) !== null) {
            $usage = new CliUsage($this, $level, $message, $context, $contextHash);
            $usage->setIsBuffer($thisIsBuffer);
            $usage->use();
        }
        if ($this->isFor('file', $level) !== null) {
            $usage = new FileUsage($this, $level, $message, $context, $contextHash);
            $usage->setIsBuffer($thisIsBuffer);
            $usage->use();
        }
        if ($this->isFor('flow', $level) !== null) {
            $usage = new FlowUsage($this, $level, $message, $context, $contextHash);
            $usage->setIsBuffer($thisIsBuffer);
            $usage->use();
        }
    }

    /**
     * Logs counter message using buffer.
     *
     * @param string  $level   Name of level (see Silogger doc).
     * @param integer $current Current value of counter.
     * @param integer $target  Final value of counter.
     * @param string  $prefix  What prefix use before counter.
     *
     * @return void
     */
    public function logCounter(string $level, int $current, int $target, string $prefix) : void
    {

        // Lvd.
        $level = strtolower($level);

        // Count level.
        if ($current !== $target) {
            $level .= 'buffer';
        }

        // Count message.
        $message = $prefix . ': ' . $current . ' / ' . $target . '';

        $this->log($level, $message);
    }
}
