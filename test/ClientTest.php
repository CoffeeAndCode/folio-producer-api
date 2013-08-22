<?php
require 'app/client.php';

/* Inherit from class we are testing so we can reveal properties
   in tests that are not accessible in the real class. */
class ClientTestWrapper extends DPSFolioProducer\Client {
    public $config;
    public $download_ticket;
    public $request_server;
    public $ticket;
}

class ClientTest extends PHPUnit_Framework_TestCase {
    public function test_defaults_to_empty_config() {
        $client = new ClientTestWrapper();
        $this->assertEquals($client->config, array());
    }

    public function test_stores_passed_config() {
        $config = array('email' => 'email@example.com');
        $client = new ClientTestWrapper($config);
        $this->assertEquals($client->config, $config);
    }

    public function test_initializes_with_null_download_ticket() {
        $client = new ClientTestWrapper();
        $this->assertEquals($client->download_ticket, null);
    }

    public function test_initializes_with_null_request_server() {
        $client = new ClientTestWrapper();
        $this->assertEquals($client->request_server, null);
    }

    public function test_initializes_with_null_ticket() {
        $client = new ClientTestWrapper();
        $this->assertEquals($client->ticket, null);
    }

    public function test_creates_session_service() {
        $client = new ClientTestWrapper();
        $this->assertEquals(get_class($client->session), 'DPSFolioProducer\SessionService');
    }
}
