<?php declare(strict_types=1);
namespace Przeslijmi\Silogger;

use Exception;
use PHPUnit\Framework\TestCase;
use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Silogger;
use Przeslijmi\Silogger\Usage\CliUsage;

/**
 * Methods for testing Silogger.
 */
final class SiloggerTest extends TestCase
{

    /**
     * Test if declaring log works.
     *
     * @return void
     */
    public function testDeclaringProper() : void
    {

        // Lvd.
        $logName    = 'test' . rand(1000, 9999);
        $definition = [
            'cli' => [
            ],
            'file' => [
                'uri'    => 'logs/[ip].log',
                'format' => '[Y]-[m]-[d]-[H]-[i]-[s] [lvl]: [msg]',
                'levels' => [
                    Silogger::EMERGENCY,
                    Silogger::ALERT,
                    Silogger::CRITICAL,
                    Silogger::ERROR,
                    Silogger::WARNING,
                ]
            ],
        ];

        // Declare.
        Silogger::declare($logName, $definition);

        // Get.
        $log = Silogger::get($logName);

        // Test.
        $this->assertEquals($logName, $log->getName());
        $this->assertEquals($definition, $log->getDefinition());
        $this->assertEquals(null, $log->isFor('nonExistingUsage', 'info'));
        $this->assertEquals($definition['cli'], $log->isFor('cli', 'info'));
        $this->assertEquals(null, $log->isFor('file', 'info'));
    }

    /**
     * Test if declaring log with FileUsage defined badly (no uri) calls exceptions.
     *
     * @return void
     */
    public function testDeclaringInproperFileUsageInUri() : void
    {

        // Lvd.
        $logName    = 'test' . rand(1000, 9999);
        $definition = [
            'file' => [
                'uri'    => null,
                'format' => '[Y]-[m]-[d]-[H]-[i]-[s] [lvl]: [msg]'
            ],
        ];

        // Declare.
        Silogger::declare($logName, $definition);

        // Test.
        $this->expectException(Exception::class);

        // Get.
        $log = Silogger::get($logName);
        $log->info('Test.');
    }

    /**
     * Test if declaring log with FileUsage defined badly (no format) calls exceptions.
     *
     * @return void
     */
    public function testDeclaringInproperFileUsageInFormat() : void
    {

        // Lvd.
        $logName    = 'test' . rand(1000, 9999);
        $definition = [
            'file' => [
                'uri'    => 'logs/[ip].log',
                'format' => null
            ],
        ];

        // Declare.
        Silogger::declare($logName, $definition);

        // Test.
        $this->expectException(Exception::class);

        // Get.
        $log = Silogger::get($logName);
        $log->info('Test.');
    }
}
