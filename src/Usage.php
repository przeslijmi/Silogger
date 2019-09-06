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
     * Context of message.
     *
     * @var array
     */
    protected $context;

    /**
     * Hash of context of message (for reference).
     *
     * @var string
     */
    protected $contextHash;

    /**
     * Common creator for usages.
     *
     * @param Log    $log     Log object.
     * @param string $level   Level of message.
     * @param string $message Contents of message.
     * @param array  $context Extra array information on message.
     *
     * @since v1.0
     */
    public function __construct(
        Log $log,
        string $level,
        string $message,
        array $context = [],
        ?string $contextHash = null
    ) {

        // Define.
        $this->log         = $log;
        $this->level       = $level;
        $this->message     = $message;
        $this->context     = $context;
        $this->contextHash = $contextHash;

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

        // Lvd.
        $chars = [
            'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z', 'W', 'F', 'm', 'M', 'n',
            't', 'L', 'o', 'Y', 'y', 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i',
            's', 'u', 'v', 'e', 'I', 'O', 'P', 'T', 'Z', 'F', 'c', 'r', 'U'
        ];

        // Dates and times.
        foreach ($chars as $char) {
            $txt = str_replace('[' . $char . ']', date($char), $txt);
        }

        // Level and message.
        $txt = str_replace('[lvl]', $this->level, $txt);
        $txt = str_replace('[LVL]', mb_strtoupper($this->level), $txt);
        $txt = str_replace('[msg]', $this->message, $txt);
        $txt = str_replace('[ref]', $this->contextHash, $txt);

        // Others.
        $txt = str_replace('[ip]', ( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' ), $txt);

        return $txt;
    }
}
