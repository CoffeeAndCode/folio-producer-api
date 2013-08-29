<?php
namespace DPSFolioProducer;

require_once 'services/folio.php';
require_once 'services/session.php';

class Client {
    protected $config = null;
    protected $download_ticket = null;
    protected $folio = null;
    protected $request_server = null;
    protected $session = null;
    protected $ticket = null;

    public function __construct(&$config) {
        $this->config = &$config;
        $this->folio = new FolioService($config);
        $this->session = new SessionService($config);
        $this->sync_session();
    }

    public function create_folio($options) {
        $request = null;
        if ($this->ticket) {
            $request = $this->folio->create($options);
        }
        return $request;
    }

    public function create_session() {
        $request = null;
        if (!$this->ticket) {
            $request = $this->session->create();
            $this->request_server = $this->config['request_server'] = $this->folio->config['request_server'] = $request->response->server;
            $this->ticket = $this->config['ticket'] = $this->folio->config['ticket'] = $request->response->ticket;

            if (session_id()) {
                $_SESSION['request_server'] = $this->request_server;
                $_SESSION['ticket'] = $this->ticket;
            }
        }
        return $request;
    }

    public function get_folio_metadata() {
        $request = null;
        if ($this->ticket) {
            $request = $this->folio->get_folio_metadata();
        }
        return $request;
    }

    protected function sync_session() {
        if (session_id()) {
            if (isset($_SESSION) && isset($_SESSION['download_ticket'])) {
                $this->download_ticket = $_SESSION['download_ticket'];
            }

            if (isset($_SESSION) && isset($_SESSION['request_server'])) {
                $this->request_server = $this->config['request_server'] = $this->folio->config['request_server'] = $_SESSION['request_server'];
            }

            if (isset($_SESSION) && isset($_SESSION['ticket'])) {
                $this->ticket = $this->config['ticket'] = $this->folio->config['ticket'] = $_SESSION['ticket'];
            }
        }
    }
}
