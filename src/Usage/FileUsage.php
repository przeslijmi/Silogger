<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Usage;

use Exception;
use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Usage;

/**
 * Works on file usage of Log message.
 */
class FileUsage extends Usage
{

    /**
     * Called by Usage constructor - have to make job done.
     *
     * @since  v1.0
     * @throws Exception When file uri or message format are not defined.
     * @return self
     */
    protected function use() : self
    {

        // Lvd.
        $options = $this->log->isFor('file', $this->level);

        // Check.
        if (isset($options['uri']) === false || empty($options['uri']) === true) {
            $msg  = 'FileUsage is impossible because no file URI is given in ';
            $msg .= 'log config for logger >>' . $this->log->getName() . '<<.';
            throw new Exception($msg);
        }
        if (isset($options['format']) === false || empty($options['format']) === true) {
            $msg  = 'FileUsage is impossible because no message format is given in ';
            $msg .= 'log config for logger >>' . $this->log->getName() . '<<.';
            throw new Exception($msg);
        }

        // Format.
        $fileUri = $this->format($options['uri']);
        $message = $this->format(trim($options['format']) . PHP_EOL);

        // Save file.
        $file = fopen($fileUri, 'a');
        fwrite($file, $message);
        fclose($file);

        return $this;
    }
}
