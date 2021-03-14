<?php declare(strict_types=1);

namespace Przeslijmi\Silogger\Usage;

use Przeslijmi\Silogger\LocaleTranslator;
use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Usage;
use Przeslijmi\Silogger\Usage\FlowUsage\Stack;

/**
 * Works on FLOW usage of Log message.
 */
class FlowUsage extends Usage
{

    /**
     * Called by Usage constructor - have to make job done.
     *
     * @return self
     */
    public function use() : self
    {

        // Save to stack.
        Stack::add($this);

        return $this;
    }
}
