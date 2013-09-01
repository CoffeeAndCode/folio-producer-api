<?php
namespace DPSFolioProducer;

class Client {
    protected $config = null;
    protected $folio = null;
    protected $session = null;

    public function __construct($config) {
        $this->config = new Config($config);
        $this->folio = new Services\FolioService($this->config);
        $this->session = new Services\SessionService($this->config);
        $this->sync_session();
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

    public function execute($command_name, $options=array()) {
        $command_class = $this->get_command_class($command_name);
        $command = new $command_class($options);

        if (!isset($this->config->ticket)) {
            $this->create_session();
        }

        $command->folio = $this->folio;
        $command->session = $this->session;
        $request = $command->execute();

        // if an InvalidTicket response is returned, reauthenticate and retry
        if ($request->get_response_code() === 200 &&
            property_exists($request->response, 'status') &&
            $request->response->status === 'InvalidTicket' &&
            !$request->is_retry) {

            $this->create_session();
            $request->retry();
        }

        return $request;
    }

    private function camelize($word) {
        $words = explode('_', $word);
        $words = array_map('ucfirst', $words);
        return implode('', $words);
    }

    private function get_command_class($command_name) {
        return '\\DPSFolioProducer\\Commands\\'.$this->camelize($command_name);
    }

    private function sync_session() {
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
