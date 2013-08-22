<?php
namespace DPSFolioProducer;

require 'services/session.php';

class Client {
    protected $config = null;
    protected $download_ticket = null;
    protected $request_server = null;
    protected $ticket = null;

    public function __construct($config) {
        $this->config = $config;
        $this->session = new SessionService($config);
        $this->sync_to_session();
    }

    private function create_session() {
        if (!$this->ticket) {
            $this->session->create();
        }
    }

    protected function sync_to_session() {
        if (session_id()) {
            $_SESSION['download_ticket'] = $this->download_ticket;
            $_SESSION['request_server'] = $this->request_server;
            $_SESSION['ticket'] = $this->ticket;
        }
    }
}
