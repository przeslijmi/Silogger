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

    private $convert = [
        'from' => [ PHP_EOL, "\r", "\n" ],
        'to' => [ '<:n>', '<:r>', '<:n>' ],
    ];

    /**
     * Called by Usage constructor - have to make job done.
     *
     * @since  v1.0
     * @throws Exception When file uri or message format are not defined.
     * @return self
     */
    public function use() : self
    {

        // Ignore buffers at all.
        if ($this->isBuffer() === true) {
            return $this;
        }

        // Lvd.
        $options = $this->log->isFor('file', $this->level);

        // Check.
        if (isset($options['uri']) === false || empty($options['uri']) === true) {
            $msg  = 'FileUsage is impossible because no file URI is given in ';
            $msg .= 'log config for logger >>' . $this->log->getName() . '<<.';
            throw new Exception($msg);
        }
        if (isset($options['uriRef']) === false || empty($options['uriRef']) === true) {
            $msg  = 'FileUsage for Ref is impossible because no file URI Ref is given in ';
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
        $message = $this->format(trim($options['format']));
        $message = str_replace($this->convert['from'], $this->convert['to'], $message);

        // Save ref file.
        if ($this->contextHash !== null) {

            // Format.
            $fileRefUri = $this->format($options['uriRef']);

            // Add context hash (as ref) to message.
            $message .= ' [ref:' . $this->contextHash . ']';

            // Lvd.
            $fileRefUri  = $this->format($options['uriRef']);
            $messageFull = $message . "\n\n" . serialize($this->context);

            // Save file.
            $file = fopen($fileRefUri, 'a');
            fwrite($file, $messageFull);
            fclose($file);
        }

        // Save file.
        $file = fopen($fileUri, 'a');
        fwrite($file, $message . "\n");
        fclose($file);

        return $this;
    }
}
