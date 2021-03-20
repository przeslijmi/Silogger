<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Log;

use Przeslijmi\Silogger\Log;

/**
 * Child class of Log with definitions of this Log.
 */
abstract class Definition
{

    /**
     * Name of this Log.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Definition of this Log.
     *
     * @var string
     */
    protected $definition = [
        'cli' => [
        ],
    ];

    /**
     * Log definition constructor.
     *
     * @param string $name Name of Log.
     */
    public function __construct(string $name = 'default')
    {

        $this->name = $name;
    }

    /**
     * Sets full definition of this log.
     *
     * @param array $definition Definition of this log.
     *
     * @return self
     */
    public function setDefinition(array $definition) : self
    {

        $this->definition = $definition;

        return $this;
    }

    /**
     * Getter for name of Log.
     *
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Getter for definition of Log.
     *
     * @return array
     */
    public function getDefinition() : array
    {

        return $this->definition;
    }

    /**
     * Checks if this message in this log should be sent to Cli.
     *
     * Returns array with CLI definition for this log - or null.
     *
     * @param string $usage Name of usage (cli, file, mail, etc.).
     * @param string $level Name of level (see Silogger doc).
     *
     * @return null|array
     */
    public function isFor(string $usage, string $level) : ?array
    {

        if (isset($this->definition[$usage]) === false) {
            return null;
        }

        if (isset($this->definition[$usage]['levels']) === false) {
            return $this->definition[$usage];
        }

        if (in_array($level, $this->definition[$usage]['levels']) === false) {
            return null;
        }

        return $this->definition[$usage];
    }
}
