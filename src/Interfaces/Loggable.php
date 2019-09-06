<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Interfaces;

/**
 * Interface for loggable elements.
 */
interface Loggable
{

    /**
     * Logs message.
     *
     * @param string $level   Name of level (see Silogger doc).
     * @param mixed  $message Message contents.
     *
     * @since  v1.0
     * @return void
     */
    public function log(string $level, $message) : void;
}
