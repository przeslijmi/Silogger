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
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

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
    const NOTICE_COLOR    = '1;37;45';
    const INFO_COLOR      = '0;32;40';
    const DEBUG_COLOR     = '1;37;40';
}
