<?php
namespace DPSFolioProducer;

require 'config.php';

$assetFormat = array('Auto', 'JPEG', 'PNG', 'PDF');
$jpegQuality = array('Minimum', 'Low', 'Medium', 'High', 'Maximum');
$folioIntent = array('LandscapeOnly', 'PortraitOnly', 'Both');
$protectedAccess = array('Closed', 'Open', 'Free');
$downloadPriority = array('Low', 'Medium', 'High');
$orientation = array('Landscape', 'Portrait', 'Both');
$smoothScrolling = array('Never', 'Landscape', 'Portrait', 'Always');
$viewer = array('web', '');

class FolioProducer {
    public function create_session() {
        $request = new Request;
        $this->create_timestamp();
        $this->create_nonce();
        $this->sig = $this->oauth_signature();
        $this->url = $this->create_url($this->config->host,$url);

        $credentials = array(
            'email' => $EMAIL,
            'password'  => $PASSWORD
        );

        if (!isset($credentials['email']) || !isset($credentials['password'] ))
        {
            throw new Exception("Email and password are required");
        }
        $request->params = json_encode($credentials);
        $request->headers[] = 'Authorization: OAuth oauth_consumer_key="' . $this->config->consumer_key . '", oauth_timestamp="' . $this->config->timestamp . '", oauth_signature_method="HMAC-SHA256", oauth_signature="' . $this->sig . '"';

        $this->oauth = $this->curl(false);
        $_SESSION['ticket'] = $this->oauth['ticket'];
        $_SESSION['server'] = $this->oauth['server'];
        $_SESSION['downloadTicket'] = $this->oauth['downloadTicket'];
        $_SESSION['downloadServer'] = $this->oauth['downloadServer'];
        return $this->oauth;
    }
}

class Request {
    private $headers;
    private $params;

    public function __construct() {
        $this->headers = array(
            'Content-Type: application/json; charset=utf-8'
        );

        $credentials = array(
            'email' => '',
            'password' => ''
        );
        $this->params = array();
    }
}
