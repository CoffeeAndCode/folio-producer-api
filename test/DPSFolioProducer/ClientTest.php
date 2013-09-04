<?php
use \Mockery as m;

/**
 * Inherit from class we are testing so we can reveal properties
 * in tests that are not accessible in the real class.
 */
class ClientTestWrapper extends DPSFolioProducer\Client
{
    public $config;
    public $download_ticket;
    public $request_server;
    public $session;
    public $ticket;
    public function getCommandClass($command_name)
    {
        parent::getCommandClass($command_name);
    }
}

class ClientTestCommand extends DPSFolioProducer\Commands\Command
{
    public function execute() {}
}

class ClientTest extends PHPUnit_Framework_TestCase
{
    private $test_config = array(
        'api_server' => 'https://dpsapi2.acrobat.com',
        'company' => '',
        'consumer_key' => '',
        'consumer_secret' => '',
        'email' => '',
        'password' => '',
        'session_props' => ''
    );

    public function tearDown()
    {
        m::close();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Missing argument 1 for DPSFolioProducer\Client::__construct()
     */
    public function test_throws_exception_if_config_not_passed()
    {
        $client = new ClientTestWrapper();
        $this->assertEquals($client->config, array());
    }

    public function test_stores_passed_config()
    {
        $client = new ClientTestWrapper($this->test_config);
        foreach ($this->test_config as $key => $value) {
            $this->assertEquals($client->config->$key, $this->test_config[$key]);
        }
    }

    public function test_initializes_with_null_download_ticket()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals($client->download_ticket, null);
    }

    public function test_initializes_with_null_request_server()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals($client->request_server, null);
    }

    public function test_initializes_with_null_ticket()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals($client->ticket, null);
    }

    public function test_creates_session_service()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals(get_class($client->session), 'DPSFolioProducer\Services\SessionService');
    }

    public function test_create_session_only_called_once()
    {
        $client = new ClientTestWrapper($this->test_config);
        $session = $this->getMock('Session', array('create'));
        $session->expects($this->once())
                ->method('create')
                ->will($this->returnValue(json_decode('{"response": {"ticket": "1234", "server": "http://example.com"}}')));
        $client->session = $session;
        $client->createSession();
        $client->createSession();
    }

    public function test_stores_ticket_after_create_session_call()
    {
        $client = new ClientTestWrapper($this->test_config);
        $session = $this->getMock('Session', array('create'));
        $session->expects($this->once())
                ->method('create')
                ->will($this->returnValue(json_decode('{"response": {"ticket": "1234", "server": "http://example.com"}}')));
        $client->session = $session;
        $client->createSession();
        $this->assertEquals($client->config->ticket, '1234');
    }

    public function test_stores_request_server_after_create_session_call()
    {
        $client = new ClientTestWrapper($this->test_config);
        $session = $this->getMock('Session', array('create'));
        $session->expects($this->once())
                ->method('create')
                ->will($this->returnValue(json_decode('{"response": {"ticket": "1234", "server": "http://example.com"}}')));
        $client->session = $session;
        $client->createSession();
        $this->assertEquals($client->config->request_server, 'http://example.com');
    }

    public function test_execute_will_call_create_session_if_ticket_does_not_exist() {}
    public function test_execute_will_not_call_create_session_if_ticket_exists() {}
    public function test_execute_will_retry_original_request_if_ticket_is_expired() {}
}
