<?php
class ConfigWrapper extends DPSFolioProducer\Config
{
    public $data;
}

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function test_defaults_to_empty_array()
    {
        $config = new ConfigWrapper();
        $this->assertEquals(count($config->data), 0);
    }

    public function test_can_retrieve_stored_configs()
    {
        $config = new DPSFolioProducer\Config();
        $config->email = 'email@example.com';
        $this->assertEquals($config->email, 'email@example.com');
    }

    public function test_can_overwrite_stored_configs()
    {
        $config = new DPSFolioProducer\Config();
        $config->email = 'email@example.com';
        $config->email = 'new.email@example.com';
        $this->assertEquals($config->email, 'new.email@example.com');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Undefined index: hello
     */
    public function test_retrieving_unset_property_throws_exception()
    {
        $config = new DPSFolioProducer\Config();
        $this->assertTrue($config->hello);
    }

    public function test_initalize_config_with_data()
    {
        $config = new DPSFolioProducer\Config(array(
            'email' => 'email@example.com',
            'password' => 'pass'
        ));
        $this->assertEquals($config->email, 'email@example.com');
        $this->assertEquals($config->password, 'pass');
    }

    public function test_isset_returns_true_for_existing_value()
    {
        $config = new DPSFolioProducer\Config(array(
            'email' => 'email@example.com'
        ));
        $this->assertTrue(isset($config->email));
    }

    public function test_isset_returns_false_for_nonexistant_value()
    {
        $config = new DPSFolioProducer\Config();
        $this->assertTrue(!isset($config->email));
    }
}
