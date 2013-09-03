<?php
/**
 * Inherit from class we are testing so we can reveal properties
 * in tests that are not accessible in the real class and test
 * abstract class.
 */
class CommandTestExample extends DPSFolioProducer\Commands\Command
{
    public $options;
    public function execute() {}
}

class CommandTest extends PHPUnit_Framework_TestCase
{
    public function test_command_class_impliments_icommand()
    {
        $class = new CommandTestExample();
        $this->assertTrue($class instanceof DPSFolioProducer\Commands\ICommand);
    }

    public function test_stores_passed_options()
    {
        $options = array('hello' => 'world');
        $class = new CommandTestExample($options);
        $this->assertEquals($class->options, $options);
        $options['hello'] = 'universe';
        $this->assertNotEquals($class->options, $options);
    }
}
