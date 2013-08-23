<?php
namespace DPSFolioProducer;

require_once 'request.php';
require_once 'service.php';

class SessionService extends Service {
    public $config = null;

    public function __construct(&$config) {
        $this->config = &$config;
    }

    /* Establishes a session with the acrobat.com server. You must successfully
       establish a session before you can send any other requests to the server.

       To create a session, send an HTTP POST request with a valid user name
       and password (to be valid, a username must have been previously created
       and the user must have logged into the account and a terms of use).

       Alternatively, you could provide an authentication token previously
       eturned by t om server in the response to a previous successful create
       session request.
    */
    public function create() {
        $data = array(
            //'needToken' => false,
            'email' => $this->config['email'],
            'password' => $this->config['password'],
            //'authToken' => $this->config['auth_token'],
            //'sessionProps' => $this->config['session_props']
        );
        $timestamp = round(microtime(true));
        $nonce = $this->create_nonce($timestamp);
        $signature = $this->oauth_signature($timestamp);
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: OAuth oauth_consumer_key="' . $this->config['consumer_key'] . '", oauth_timestamp="' . $timestamp . '", oauth_signature_method="HMAC-SHA256", oauth_signature="' . $signature . '"'
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'POST',
                'content' => json_encode($data),
                //'proxy' => 'tcp://localhost:8888',
                'protocol_version' => 1.1
            )
        );
        $request = new Request($this->create_url('sessions'), $options);
        $response = $request->run();
        $this->api_server = $response->server;

        return $request;
    }

    /* Ends an acrobat.com session. A client should end a session when it is no
       longer needed. To end a session, apply the DELETE method to the session’s
       resource. No parameters are required for this call, and no results other
       than status are returned.

       Set `cancelToken` to false to allow future use of the passed token. It
       defaults to `true`.
    */
    public function delete($ticket, $server, $cancelToken=true) {
        $url = $server.'/webservices/sessions';
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        $data = array(
            //'needToken' => false
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'DELETE',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $response = json_decode($result);

        if ($response === null) {
            if (json_last_error() !== JSON_ERROR_NONE) {
                user_error(json_last_error());
            }
        }

        return $response;
    }

    /* Acrobat.com operates in a cluster environment and requests are handled
       by multiple servers. If a client receives an HTTP Service Unavailable
       (503) error response or a request times out, it may be because the
       addressed server is down or otherwise unavailable. In this case, you
       can apply the GET method to the session’s resource in an attempt to
       obtain a new server and download server.

       No parameters are required, but the request must be authenticated with
       the current session. This request returns two results in addition to
       the standard status results.

       If the HTTP status result is 503 (or the request times out), then it
       is likely that the entire Acrobat.com service is temporarily unavailable.
    */
    public function get($ticket) {
        $url = $this->create_url('sessions');
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'GET'
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $response = json_decode($result);

        if ($response === null) {
            if (json_last_error() !== JSON_ERROR_NONE) {
                user_error(json_last_error());
            }
        }

        return $response;
    }

    private function create_nonce($timestamp) {
        $sequence = array_merge(range(0,9),range('A','Z'),range('a','z'));
        $length = count($sequence);
        shuffle($sequence);
        return md5( substr($timestamp . implode('', $sequence), 0, $length ));
    }

    private function oauth_message($timestamp) {
        $query = http_build_query(array(
            'oauth_consumer_key' => $this->config['consumer_key'],
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_timestamp' => $timestamp
        ));
        return 'POST&'.urlencode($this->create_url('sessions')).'&'.urlencode($query);
    }

    private function oauth_signature($timestamp) {
        $message = $this->oauth_message($timestamp);
        $hash = hash_hmac('sha256', $message, $this->config['consumer_secret'] . '&', false);
        $bytes = pack('H*', $hash);
        $base = base64_encode($bytes);
        return urlencode($base);
    }
}
