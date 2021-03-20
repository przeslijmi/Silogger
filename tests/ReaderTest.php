<?php declare(strict_types=1);

namespace Przeslijmi\Silogger;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Silogger\Log;
use Przeslijmi\Silogger\Reader;

/**
 * Methods for testing Reader.
 */
final class ReaderTest extends TestCase
{

    /**
     * Definition contents before temporary change.
     *
     * @var array
     */
    private $def = [];

    /**
     * Test if reading normal log (not localed) works.
     *
     * @return void
     */
    public function testIfReadingLogWorks() : void
    {

        // Prepare.
        $uri = $this->prepareStage();

        // Log sometghing.
        $message = 'Test.';
        $log     = Log::get('default');
        $log->notice($message);

        // Create reader.
        $reader = new Reader();
        $data   = $reader->readFromUri($uri);

        // Test.
        $this->assertTrue(is_array($data));
        $this->assertEquals(count($data), 1);
        $this->assertEquals(array_keys($data[0]), [ 'date', 'time', 'level', 'description' ]);
        $this->assertEquals($data[0]['level'], 'notice');
        $this->assertEquals($data[0]['description'], $message);

        // Restore.
        $this->deleteStage();
    }

    /**
     * Test if reading localed log works.
     *
     * @return void
     */
    public function testIfReadingLocaleLogWorks() : void
    {

        // Prepare.
        $uri = $this->prepareStage();

        // Log sometghing.
        $message = '<locale class="A\B" id="Cccc" args="{}" />';
        $log     = Log::get('default');
        $log->notice($message);

        // Create reader.
        $reader = new Reader();
        $data   = $reader->readFromUri($uri);

        // Test.
        $this->assertTrue(is_array($data));
        $this->assertEquals(count($data), 1);
        $this->assertEquals(array_keys($data[0]), [ 'date', 'time', 'level', 'description' ]);
        $this->assertEquals($data[0]['level'], 'notice');
        $this->assertEquals($data[0]['description'], $message);

        // Restore.
        $this->deleteStage();
    }

    /**
     * Test if reading empty log works - ie. return empty array.
     *
     * @return void
     */
    public function testIfReadingEmptyLogWorks() : void
    {

        // Prepare.
        $uri = $this->prepareStage();

        // Create reader.
        $reader = new Reader();
        $data   = $reader->readFromUri($uri);

        // Test.
        $this->assertTrue(is_array($data));
        $this->assertEquals(count($data), 0);

        // Restore.
        $this->deleteStage();
    }

    /**
     * Delivers uri to any log file to be read.
     *
     * @return string
     */
    private function prepareStage() : string
    {

        // Find uri.
        $log = Log::get('default');
        $uri = str_replace('\\', '/', $log->getDefinition()['file']['uri']);
        $ext = substr($uri, ( strrpos($uri, '.') + 1 ));

        // Cut to dir (if any dir exists in uri).
        if (( $pos = strrpos($uri, '/') ) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Add final slash.
        $uri = rtrim($uri, '/') . '/';

        // Save original definition.
        $this->def = $log->getDefinition();

        // Create new def and change uri.
        $newUri                = $uri . 'tempLog-' . rand(1111, 9999) . '.' . $ext;
        $newDef                = $this->def;
        $newDef['file']['uri'] = $newUri;

        // Set new definition.
        $log->setDefinition($newDef);

        // Get any file.
        return $newUri;
    }

    /**
     * Restore setting to original.
     *
     * @return void
     */
    private function deleteStage() : void
    {

        // Restore old definition.
        $log = Log::get('default');
        $log->setDefinition($this->def);
    }
}
