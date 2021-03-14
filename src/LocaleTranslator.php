<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

/**
 * Translates locale into language.
 */
class LocaleTranslator
{

    /**
     * Original message in string.
     *
     * @var string
     */
    private $message = '';

    /**
     * Parsed message.
     *
     * @var array
     */
    private $parsed = [
        'class' => null,
        'id' => null,
        'args' => [],
    ];

    /**
     * Setter for language.
     *
     * @param string $lang Two letter language cod.
     *
     * @return self
     */
    public function __construct(string $message)
    {

        // Save message.
        $this->message = $message;

        // Regex that message.
        preg_match('/(<locale)((( )+(class=\\")([a-zA-Z0-9_\\\\]+)("))|(( )+(id=\\")([a-zA-Z0-9_\\\\]+)("))(( )+(args=\\")(.+)(")))+(( )*(\\/>))/', $this->message, $regex);

        // Parse it.
        $this->parsed = [
            'class' => ( $regex[6] ?? null ),
            'id' => ( $regex[11] ?? null),
            'args' => json_decode(htmlspecialchars_decode(( $regex[16] ?? '[]' ))),
        ];
    }

    /**
     * Logs can have contents of two types.
     *
     * @return string
     */
    public function translate(string $lang) : string
    {

        // Ignore inproper.
        if ($this->parsed['class'] === null || $this->parsed['id'] === null) {
            return $this->message;
        }

        // Get uri for this class messeges.
        $uri = ( PRZESLIJMI_SILOGGER_LOCALE_URIS[$lang][$this->parsed['class']] ?? null );

        // If no uri - return message.
        if ($uri === null) {
            return $this->message;
        }

        // Get locales.
        $locales = include $uri;

        // Get text.
        $translated = ( $locales[$this->parsed['id']]['txt'] ?? null );

        // If no uri - return message.
        if ($translated === null) {
            return $this->message;
        }

        // Add variables.
        foreach ($this->parsed['args'] as $key => $value) {
            $translated = str_replace('<v:' . $key . '>', $value, $translated);
        }

        return $translated;
    }

    public function getClass() : ?string
    {

        return $this->parsed['class'];
    }

    public function getId() : ?string
    {

        return $this->parsed['id'];
    }

    public function getClid() : array
    {

        return [ $this->parsed['class'], $this->parsed['id'] ];
    }
}
