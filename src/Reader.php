<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\LocaleTranslator;

/**
 * Reads logs.
 */
class Reader
{

    /**
     * Language to use in locale translation.
     *
     * @var string
     */
    public $lang = 'en:us';

    /**
     * Reads log from given URI.
     *
     * @param string $uri Uri to read from.
     *
     * @return array
     */
    public function readFromUri(string $uri) : array
    {

        // Lvd.
        $result = [];
        $logs   = [];

        // Get file contents as array of logs.
        if (file_exists($uri) === true) {
            $logs = file($uri);
        }

        // Short lane.
        if (empty($logs) === true) {
            return [];
        }

        // Read every log.
        foreach ($logs as $rawLog) {

            // Lvd.
            $translated = null;

            // Read by preg.
            preg_match('/^([0-9-]{10})(-)([0-9-]{8}( ))([a-z]+)(: )(.+)$/', trim($rawLog), $rawLog);

            // Work on message.
            if (isset($rawLog[7]) === true && substr($rawLog[7], 0, 8) === '<locale ') {
                $translated = ( new LocaleTranslator($rawLog[7]) )->translate($this->lang);
            }

            // Add to result.
            $result[] = [
                'date' => $rawLog[1],
                'time' => str_replace('-', ':', $rawLog[3]),
                'level' => $rawLog[5],
                'original' => $rawLog[7],
                'translated' => ( ( $rawLog[7] === $translated ) ? '' : $translated ),
                'description' => ( $translated ?? $rawLog[7] ),
            ];
        }

        return $result;
    }
}
