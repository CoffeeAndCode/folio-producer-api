<?php
require_once 'app/config.php';

class ConfigWrapper extends DPSFolioProducer\Config {
    public $data;
}

class ConfigTest extends PHPUnit_Framework_TestCase {
    public function test_defaults_to_empty_array() {
        $config = new ConfigWrapper();
        $this->assertEquals(count($config->data), 0);
    }

    public function test_can_retrieve_stored_configs() {
        $config = new DPSFolioProducer\Config();
        $config->hello = 'world';
        $this->assertEquals($config->hello, 'world');
    }

    public function test_can_overwrite_stored_configs() {
        $config = new DPSFolioProducer\Config();
        $config->hello = 'world';
        $config->hello = 'universe';
        $this->assertEquals($config->hello, 'universe');
    }

    public function test_retrieving_unset_property_is_undefined() {
        $config = new DPSFolioProducer\Config();
        $this->assertTrue(!isset($config->hello));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Undefined index: hello
     */
    public function test_retrieving_unset_property_throws_exception() {
        $config = new DPSFolioProducer\Config();
        $this->assertTrue($config->hello);
    }
}
