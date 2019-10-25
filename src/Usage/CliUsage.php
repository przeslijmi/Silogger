<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Usage;

use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Usage;

/**
 * Works on CLI usage of Log message.
 */
class CliUsage extends Usage
{

    /**
     * Log levels colors.
     *
     * @var string
     */
    const EMERGENCY_COLOR = '1;33;41';
    const ALERT_COLOR     = '1;37;41';
    const CRITICAL_COLOR  = '0;30;41';
    const ERROR_COLOR     = '0;31;43';
    const WARNING_COLOR   = '0;30;43';
    const NOTICE_COLOR    = '1;34;40';
    const INFO_COLOR      = '1;32;40';
    const DEBUG_COLOR     = '1;37;40';

    /**
     * Called by Usage constructor - have to make job done.
     *
     * @since  v1.0
     * @return self
     */
    public function use() : self
    {

        // Lvd.
        $color   = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . strtoupper($this->level) . '_COLOR');
        $showLog = '';

        // Define log to be showed.
        $showLog .= "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $this->log->getName() . '] ' . $this->level . ': ';
        $showLog .= $this->message;
        $showLog .= ( ( $this->contextHash === null ) ? '' : ' [ref:' . $this->contextHash . ']' );
        $showLog .= "\e[0m";

        // Add new line only if this is not buffer.
        if ($this->isBuffer() === false) {
            $showLog .= PHP_EOL;
        }

        // Add carret comeback if this is a buffer.
        if ($this->isBuffer() === true) {
            $showLog .= "\r";
        }

        // Show log.
        echo $showLog;

        return $this;
    }
}
