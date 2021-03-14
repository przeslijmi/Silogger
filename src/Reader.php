<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use Przeslijmi\Silogger\LocaleTranslator;

/**
 * Reads logs.
 */
class Reader
{

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

        // Get file contents as array of logs.
        $logs = file($uri);

        // Short lane.
        if (empty($logs) === true) {
            return [];
        }

        // Read every log.
        foreach ($logs as $rawLog) {

            // Read by preg.
            preg_match('/^([0-9-]{10})(-)([0-9-]{8}( ))([a-z]+)(: )(.+)$/', trim($rawLog), $rawLog);

            // Work on message.
            if (isset($rawLog[7]) === true && substr($rawLog[7], 0, 8) === '<locale ') {
                $rawLog[7] = ( new LocaleTranslator($rawLog[7]) )->translate('pl:pl');
            }

            // Add to result.
            $result[] = [
                'date' => $rawLog[1],
                'time' => str_replace('-', ':', $rawLog[3]),
                'level' => $rawLog[5],
                'description' => $rawLog[7],
            ];
        }

        return $result;
    }
}
