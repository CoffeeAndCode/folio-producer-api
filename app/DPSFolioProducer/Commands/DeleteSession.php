<?php
namespace DPSFolioProducer\Commands;

/* Ends an acrobat.com session. A client should end a session when it is no
   longer needed. To end a session, apply the DELETE method to the session’s
   resource. No parameters are required for this call, and no results other
   than status are returned.

   Set `cancelToken` to false to allow future use of the passed token. It
   defaults to `true`.
*/
class DeleteSession extends Command
{
    public function execute()
    {
        $data = array(
            'cancelToken' => true
        );
        $data = array_merge($data, $this->options);

        $request = new \DPSFolioProducer\APIRequest('sessions', $this->config,
            array(
                'data' => json_encode($data),
                'type' => 'delete'
            )
        );

        $request = $request->run();
        if ($request && $request->response) {
            $this->config->reset();
        }
        return $request;
    }
}
