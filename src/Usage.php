<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\LocaleTranslator;
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
     * If this is buffer log or not.
     *
     * @var boolean
     */
    private $isBuffer = false;

    /**
     * Holds created LocaleTranslator object.
     *
     * @var LocaleTranslator
     */
    private $locale;

    /**
     * Common creator for usages.
     *
     * @param Log    $log         Log object.
     * @param string $level       Level of message.
     * @param string $message     Contents of message.
     * @param array  $context     Optional, empty array. Extra array information on message.
     * @param string $contextHash Optional, null. Hashed context.
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
    }

    /**
     * Setter for `isBuffer`.
     *
     * @param boolean $isBuffer If this is buffer log or not.
     *
     * @return self
     */
    public function setIsBuffer(bool $isBuffer) : self
    {

        $this->isBuffer = $isBuffer;

        return $this;
    }

    /**
     * Getter for `isBuffer`.
     *
     * @return boolean
     */
    protected function isBuffer() : bool
    {

        return $this->isBuffer;
    }

    /**
     * Converts preformatted strings into values.
     *
     * @param string $txt Text to formatted.
     *
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
        $txt = str_replace('[ref]', ( $this->contextHash ?? '' ), $txt);
        $txt = str_replace('[sessid]', session_id(), $txt);

        // Convert `env` if present.
        if (strpos($txt, '[env.') !== false) {
            preg_match_all('/(\[env\.)([A-Z_]*)(\])/', $txt, $found);
            if (isset($found[0]) === true && count($found[0]) > 0) {
                for ($f = 0; $f < count($found[0]); ++$f)  {
                    $txt = str_replace('[env.' . $found[2][$f] . ']', (string) ( $_ENV[$found[2][$f]] ?? '' ), $txt);
                }
            }
        }

        // Others.
        $txt = str_replace('[ip]', ( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' ), $txt);

        return $txt;
    }

    /**
     * Getter for message.
     *
     * @return string
     */
    public function getMessage() : string
    {

        return $this->message;
    }

    /**
     * Getter for level.
     *
     * @return string
     */
    public function getLevel() : string
    {

        return $this->level;
    }

    /**
     * Getter for `LocaleTranslator`.
     *
     * **BEWARE** Locale translator uses a lot of energy and it is not good to use it for different reasons
     * other than debugging, exceptions handler or error logging - but not in normal, proper workflow.
     *
     * @return LocaleTranslator
     */
    public function getLocale() : LocaleTranslator
    {

        // Create translator if needed.
        if ($this->locale === null) {
            $this->locale = new LocaleTranslator($this->message);
        }

        return $this->locale;
    }
}
