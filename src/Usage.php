<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\Log;

/**
 * Parent for usages.
 */
abstract class Usage
{

    /**
     * Log object for usage.
     *
     * @var Log
     */
    protected $log;

    /**
     * Level of message.
     *
     * @var string
     */
    protected $level;

    /**
     * Contents of message.
     *
     * @var string
     */
    protected $message;

    /**
     * Common creator for usages.
     *
     * @param Log    $log     Log object.
     * @param string $level   Level of message.
     * @param string $message Contents of message.
     *
     * @since v1.0
     */
    public function __construct(Log $log, string $level, string $message)
    {

        // Define.
        $this->log     = $log;
        $this->level   = $level;
        $this->message = $message;

        // Call to start.
        $this->use();
    }

    /**
     * Converts preformatted strings into values.
     *
     * @param string $txt Text to formatted.
     *
     * @since  v1.0
     * @return Formatted text.
     */
    protected function format(string $txt) : string
    {

        // Dates and times.
        $txt = str_replace('[Y]', date('Y'), $txt);
        $txt = str_replace('[m]', date('m'), $txt);
        $txt = str_replace('[d]', date('d'), $txt);
        $txt = str_replace('[H]', date('H'), $txt);
        $txt = str_replace('[i]', date('i'), $txt);
        $txt = str_replace('[s]', date('s'), $txt);

        // Level and message.
        $txt = str_replace('[lvl]', $this->level, $txt);
        $txt = str_replace('[LVL]', mb_strtoupper($this->level), $txt);
        $txt = str_replace('[msg]', $this->message, $txt);

        // Others.
        $txt = str_replace('[ip]', ( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' ), $txt);

        return $txt;
    }
}
