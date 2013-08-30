<?php
namespace DPSFolioProducer;

require_once 'config.php';
require_once 'services/folio.php';
require_once 'services/session.php';

class Client {
    protected $config = null;
    protected $folio = null;
    protected $session = null;

    public function __construct($config) {
        $this->config = new Config($config);
        $this->folio = new FolioService($this->config);
        $this->session = new SessionService($this->config);
        $this->sync_session();
    }

    public function create_folio($options) {
        $request = null;
        if (isset($this->config->ticket)) {
            $request = $this->folio->create($options);
        }
        return $request;
    }

    public function create_session() {
        $request = null;
        if (!isset($this->config->ticket) || !$this->config->ticket) {
            $request = $this->session->create();
            $this->config->request_server = $request->response->server;
            $this->config->ticket = $request->response->ticket;

            if (session_id()) {
                $_SESSION['request_server'] = $this->config->request_server;
                $_SESSION['ticket'] = $this->config->ticket;
            }
        }
        return $request;
    }

    public function get_folio_metadata() {
        $request = null;
        if (isset($this->config->ticket)) {
            $request = $this->folio->get_folio_metadata();
        }
        return $request;
    }

    protected function sync_session() {
        if (session_id()) {
            if (isset($_SESSION) && isset($_SESSION['download_ticket'])) {
                $this->config->download_ticket = $_SESSION['download_ticket'];
            }

            if (isset($_SESSION) && isset($_SESSION['request_server'])) {
                $this->config->request_server = $_SESSION['request_server'];
            }

            if (isset($_SESSION) && isset($_SESSION['ticket'])) {
                $this->config->ticket = $_SESSION['ticket'];
            }
        }
    }
}
