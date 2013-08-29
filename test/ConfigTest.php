<?php
class ConfigTest_Config {
    public $config = array();
}

class ConfigTest_ObjectA {
    public $config = null;

    public function __construct($config) {
        $this->config = $config;
    }

    public function change() {
        $this->config->config['hello'] = 'world';
    }
}

class ConfigTest extends PHPUnit_Framework_TestCase {
    public function test_instances_receive_same_object() {
        $config = new ConfigTest_Config();
        $a = new ConfigTest_ObjectA($config);
        $b = new ConfigTest_ObjectA($config);
        $this->assertEquals(spl_object_hash($a->config), spl_object_hash($b->config));
    }

    public function test_config_does_not_retain_outside_changes() {
        $config = new ConfigTest_Config();
        $a = new ConfigTest_ObjectA($config);
        $b = new ConfigTest_ObjectA($a->config);
        $b->change();
        $this->assertEquals(count($a->config), count($b->config));
    }
}
