<?php
namespace DPSFolioProducer\Commands;

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
class GetNewServers extends Command
{
    public function execute()
    {
        $request = new \DPSFolioProducer\APIRequest('sessions', $this->config,
            array(
                'type' => 'get'
            )
        );

        $request = $request->run();
        if ($request && $request->response) {
            $this->config->download_server = $request->response->downloadServer;
            $this->config->request_server = $request->response->server;
        }
        return $request;
    }
}
