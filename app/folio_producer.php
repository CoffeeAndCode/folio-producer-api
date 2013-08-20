<?php
namespace DPSFolioProducer;

class FolioProducerAPI {
    public $config;
    private $timestamp;

    public function __construct($config) {
        $this->config = $config;
    }

    public function create_session() {
        $this->timestamp = $this->create_timestamp();

        $header = array(
            'Authorization' => 'OAuth',
            'oauth_consumer_key' => $this->config['key'],
            'oauth_timestamp' => $this->timestamp,
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_signature' => ''
        );
        print_r($this->oauth_signature());
    }

    /**
     * Get the timestamp and set in config
     */
    private function create_timestamp() {
        return round(microtime(true));
    }

    /**
     * Message to be encrypted for oauth
     */
    private function oauth_message() {
        $url = urlencode($this->create_url($this->config->host, 'sessions'));
        $params = '&oauth_consumer_key%3D' . $this->config['consumer_key'] .
            '%26oauth_signature_method%3DHMAC-SHA256' .
            '%26oauth_timestamp%3D' . $this->timestamp;
        return 'POST&' . $url . $params;
    }

    /**
     * Generate the oauth signature
     */
    private function oauth_signature() {
        $message = $this->oauth_message();
        $hash = hash_hmac('sha256', $message, $this->config['consumer_secret'] . '&', false);
        $bytes = pack('H*', $hash);
        $base = base64_encode($bytes);
        return urlencode($base);
    }
}
