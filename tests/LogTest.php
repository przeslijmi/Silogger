<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Silogger\LocaleTranslator;
use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Silogger;
use Przeslijmi\Silogger\Usage\CliUsage;
use Przeslijmi\Silogger\Usage\FlowUsage;
use Przeslijmi\Silogger\Usage\FlowUsage\Stack;
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
        $color   = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method  = constant('\Przeslijmi\Silogger\Silogger::' . $level);

        // What to expect.
        $showLog  = "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $logger . '] ' . str_pad($method, 9, ' ', STR_PAD_RIGHT) . ': ';
        $showLog .= $message . "\e[0m" . PHP_EOL;
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
        $color   = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method  = constant('\Przeslijmi\Silogger\Silogger::' . $level);

        // What to expect.
        $showLog  = "\e[" . $color . 'm';
        $showLog .= 'LOG[' . $logger . '] ' . str_pad($method, 9, ' ', STR_PAD_RIGHT) . ': ';
        $showLog .= $message . "\e[0m" . PHP_EOL;
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
        $showLog .= 'LOG[' . $logger . '] ' . str_pad($method, 9, ' ', STR_PAD_RIGHT) . ': ';
        $showLog .= $error . "\e[0m" . PHP_EOL;
        $this->expectOutputString($showLog);

        // Call log.
        $log = Log::get($logger);
        $log->log($method, $message);
    }

    /**
     * Test if logging context works.
     *
     * @return void
     */
    public function testIfMessageWithContextWorks() : void
    {

        // Lvd.
        $message = 'Contents of log.';
        $logger  = 'default';
        $level   = 'INFO';
        $method  = constant('\Przeslijmi\Silogger\Silogger::' . $level);
        $context = [ 'thisIs' => 'context' ];

        // What to expect.
        $showRegex = '/^(.){25,}( \[ref:)(\d){9,10}(\])(\\e\[0m)(\R)$/';
        $this->expectOutputRegex($showRegex);

        // Call log.
        $log = Log::get($logger);
        $log->log($method, $message, $context);
    }

    /**
     * Test if using Buffer works.
     *
     * @return void
     */
    public function testIfBufferWorks() : void
    {

        // Lvd.
        $logger = 'default';
        $level  = 'INFO';
        $color  = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method = constant('\Przeslijmi\Silogger\Silogger::' . $level);

        // What to expect.
        $start    = "\e[" . $color . 'm';
        $start   .= 'LOG[' . $logger . '] ' . str_pad($method, 9, ' ', STR_PAD_RIGHT) . ': ';
        $end      = "\e[0m";
        $showLog  = $start . 'Will start buffering:' . $end . PHP_EOL;
        $showLog .= $start . '1' . $end . "\r";
        $showLog .= $start . '2' . $end . "\r";
        $showLog .= $start . '3' . $end . "\r";
        $showLog .= $start . '4' . $end . "\r";
        $showLog .= $start . '5' . $end . "\r";
        $showLog .= $start . 'Buffer stopped.' . $end . PHP_EOL;
        $this->expectOutputString($showLog);

        // Call log.
        $log = Log::get($logger);
        $log->log($method, 'Will start buffering:');

        // Add steps.
        for ($i = 1; $i <= 5; ++$i) {
            $log->log($method . 'Buffer', (string) $i);
        }

        // Add finishing.
        $log->log($method, 'Buffer stopped.');
    }

    /**
     * Test if using counter works.
     *
     * @return void
     */
    public function testIfCounterWorks() : void
    {

        // Lvd.
        $logger = 'default';
        $level  = 'INFO';
        $color  = constant('\Przeslijmi\Silogger\Usage\CliUsage::' . $level . '_COLOR');
        $method = constant('\Przeslijmi\Silogger\Silogger::' . $level);
        $count  = 5;

        // What to expect.
        $start    = "\e[" . $color . 'm';
        $start   .= 'LOG[' . $logger . '] ' . str_pad($method, 9, ' ', STR_PAD_RIGHT) . ': ';
        $end      = "\e[0m";
        $showLog  = $start . 'Elements served: 1 / 5' . $end . "\r";
        $showLog .= $start . 'Elements served: 2 / 5' . $end . "\r";
        $showLog .= $start . 'Elements served: 3 / 5' . $end . "\r";
        $showLog .= $start . 'Elements served: 4 / 5' . $end . "\r";
        $showLog .= $start . 'Elements served: 5 / 5' . $end . PHP_EOL;
        $this->expectOutputString($showLog);

        // Call log.
        $log = Log::get($logger);
        for ($i = 1; $i <= 5; ++$i) {
            $log->logCounter($method, $i, $count, 'Elements served');
        }
    }

    /**
     * Test if logging to flow and reading from stack works.
     *
     * @return void
     */
    public function testIfFlowLoggingWorks() : void
    {

        // Clear stack.
        Stack::clear();
        Stack::clear();

        // Test.
        $this->assertEquals(count(Stack::getAll()), 0);
        $this->assertEquals(Stack::getLast(), null);

        // Lvd.
        $logger  = 'default';
        $level   = 'debug';
        $message = 'Test stack.';

        // Call log.
        $log = Log::get($logger);
        $log->log($level, $message);

        // Test.
        $this->assertEquals(count(Stack::getAll()), 1);
        $this->assertInstanceOf(FlowUsage::class, Stack::getLast());
        $this->assertEquals(null, Stack::getLast(1));
        $this->assertEquals(Stack::getLast(0, 'debug'), Stack::getLast());
        $this->assertEquals(null, Stack::getLast(0, 'warning'));

        // Check stack.
        $log = Stack::getLast();
        $this->assertEquals($message, $log->getMessage());
        $this->assertEquals($level, $log->getLevel());
    }

    /**
     * Test if reading localized message works.
     *
     * @return void
     */
    public function testIfLocaleReadingWorks() : void
    {

        // Lvd.
        $logger  = 'default';
        $level   = 'debug';
        $class   = 'Class\Test';
        $messId  = 'SomeMessageHapened';
        $fields  = [
            'A field',
            'a' => 'B field',
        ];
        $expMess = 'Translated message with value `A field` and `B field`.';

        // Clear stack.
        Stack::clear();

        // Call log.
        $log = Log::get($logger);
        $log->localeLog($level, $class, $messId, $fields);

        // Get log.
        $log = Stack::getLast();
        $this->assertEquals($level, $log->getLevel());
        $this->assertTrue(Stack::isErrorIdExisting('SomeMessageHapened'));
        $this->assertFalse(Stack::isErrorIdExisting('OtherMessage'));

        // Get locale translator.
        $locale = $log->getLocale();

        // Test.
        $this->assertEquals($locale->translate('en:us'), $expMess);
        $this->assertEquals($locale->getClass(), $class);
        $this->assertEquals($locale->getId(), $messId);
        $this->assertEquals($locale->getClid(), [ $class, $messId ]);
    }

    /**
     * Test if nonlocalized message return unchanged itself instead of throwing.
     *
     * @return void
     */
    public function testIfTranslationOfNonLocaleWorks() : void
    {

        // Prepare.
        $message = 'nonLocaleMessage';
        $locale  = new LocaleTranslator($message);

        // Test.
        $this->assertEquals($locale->translate('en:us'), $message);
    }

    /**
     * Test if missing language will leave message untranslated instead of throwing.
     *
     * @return void
     */
    public function testIfTranslationWithMissingLanguageWorks() : void
    {

        // Prepare.
        $message = '<locale class="A\B" id="B" args="{}" />';
        $locale  = new LocaleTranslator($message);

        // Test.
        $this->assertEquals($locale->translate('xx:xx'), $message);
    }

    /**
     * Test id missing locale will leave message untranslated instead of throwing.
     *
     * @return void
     */
    public function testIfTranslationWithMissingMessageWorks() : void
    {

        // Prepare.
        $message = '<locale class="Class\Test" id="B" args="{}" />';
        $locale  = new LocaleTranslator($message);

        // Test.
        $this->assertEquals($locale->translate('en:us'), $message);
    }
}
