<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Usage;

use Przeslijmi\Silogger\LocaleTranslator;
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
     * @return self
     */
    public function use() : self
    {

        // Lvd.
        $color   = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . strtoupper($this->level) . '_COLOR');
        $showLog = '';

        // Define log to be showed.
        // Translation of LogLocale is not done on CliUsage (it was turned on as standard) due to efficiency reasons.
        // Maybe in future releases there will be found a way to reconcile the parties, but for now it is turned off.
        $showLog .= "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $this->log->getName() . '] ' . str_pad($this->level, 9, ' ', STR_PAD_RIGHT) . ': ';
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
