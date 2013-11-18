<?php
namespace DPSFolioProducer\Commands;

/* Establishes a session with the acrobat.com server. You must successfully
   establish a session before you can send any other requests to the server.

   To create a session, send an HTTP POST request with a valid user name
   and password (to be valid, a username must have been previously created
   and the user must have logged into the account and a terms of use).

   Alternatively, you could provide an authentication token previously
   eturned by t om server in the response to a previous successful create
   session request.
*/
class CreateSession extends Command
{
    public function execute()
    {
        $data = array(
            'email' => $this->config->email,
            'password' => $this->config->password
        );

        $request = new \DPSFolioProducer\APIRequest('sessions', $this->config,
            array(
                'data' => json_encode($data),
                'headers' => array(
                    'Authorization' => $this->authorizationHeader()
                ),
                'type' => 'post'
            )
        );

        $request = $request->run();
        if ($request && $request->response) {
            $this->config->download_server = $request->response->downloadServer;
            $this->config->download_ticket = $request->response->downloadTicket;
            $this->config->request_server = $request->response->server;
            $this->config->ticket = $request->response->ticket;
        }
        return $request;
    }

    private function authorizationHeader()
    {
        $timestamp = round(microtime(true));
        $nonce = $this->createNonce($timestamp);
        $signature = $this->oauthSignature($timestamp);
        return 'OAuth oauth_consumer_key="'.$this->config->consumer_key.'", oauth_timestamp="'.$timestamp .'", oauth_signature_method="HMAC-SHA256", oauth_signature="'.$signature.'"';
    }

    private function createNonce($timestamp)
    {
        $sequence = array_merge(range(0,9),range('A','Z'),range('a','z'));
        $length = count($sequence);
        shuffle($sequence);

        return md5( substr($timestamp . implode('', $sequence), 0, $length ));
    }

    private function oauthMessage($timestamp)
    {
        $query = http_build_query(array(
            'oauth_consumer_key' => $this->config->consumer_key,
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_timestamp' => $timestamp
        ));

        return 'POST&'.urlencode($this->config->api_server.'/webservices/sessions').'&'.urlencode($query);
    }

    private function oauthSignature($timestamp)
    {
        $message = $this->oauthMessage($timestamp);
        $hash = hash_hmac('sha256', $message, $this->config->consumer_secret . '&', false);
        $bytes = pack('H*', $hash);
        $base = base64_encode($bytes);

        return urlencode($base);
    }
}
