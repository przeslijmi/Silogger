<?php declare(strict_types=1);
namespace Przeslijmi\Silogger;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Silogger;
use Przeslijmi\Silogger\Usage\CliUsage;
use stdClass;

/**
 * Methods for testing Logs.
 */
final class LogTest extends TestCase
{

    /**
     * Provides list of message levels to test properly.
     *
     * @return array
     */
    public function levelsDataProvider() : array
    {

        // Lvd.
        $data = [];

        // Creata data format proper for test.
        foreach (Silogger::ALL as $level) {
            $data[] = [ 0 => $level ];
        }

        return $data;
    }

    /**
     * Test if calling direct methods, eg. `->info(...` works.
     *
     * @param string $level Level of log message, eg. INFO, NOTICE.
     *
     * @return void
     *
     * @dataProvider levelsDataProvider
     */
    public function testIfDirectLogMethodWorks(string $level) : void
    {

        // Lvd.
        $message = 'Test';
        $logger  = 'default';
        $color  = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method = constant('\Przeslijmi\Silogger\Silogger::' . $level);

        // What to expect.
        $showLog  = "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $logger . '] ' . $method . ': ' . $message . "\e[0m" . PHP_EOL;
        $this->expectOutputString($showLog);

        // Call log.
        $log = Log::get($logger);
        $log->$method($message);
    }

    /**
     * Test if calling standard method, eg. `->log('info', ...` works.
     *
     * @param string $level Level of log message, eg. INFO, NOTICE.
     *
     * @return void
     *
     * @dataProvider levelsDataProvider
     */
    public function testIfStandardLogMethodWorks(string $level) : void
    {

        // Lvd.
        $message = 'Test';
        $logger  = 'default';
        $color  = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method = constant('\Przeslijmi\Silogger\Silogger::' . $level);

        // What to expect.
        $showLog  = "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $logger . '] ' . $method . ': ' . $message . "\e[0m" . PHP_EOL;
        $this->expectOutputString($showLog);

        // Call log.
        $log = Log::get($logger);
        $log->log($method, $message);
    }

    /**
     * Test if logging nonstring message with log replaced message.
     *
     * @return void
     */
    public function testIfLogingNonStringMessageThrows() : void
    {

        // Lvd.
        $message = new stdClass();
        $error   = 'Err: Sent message can not be converted to string.';
        $logger  = 'default';
        $level   = 'INFO';
        $color   = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method  = constant('\Przeslijmi\Silogger\Silogger::' . $level);

        // What to expect.
        $showLog  = "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $logger . '] ' . $method . ': ' . $error . "\e[0m" . PHP_EOL;
        $this->expectOutputString($showLog);

        // Call log.
        $log = Log::get($logger);
        $log->log($method, $message);
    }
}
