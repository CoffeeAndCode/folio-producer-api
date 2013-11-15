<?php
namespace DPSFolioProducer\Commands;

class GetFoliosMetadata extends Command
{
    public function execute()
    {
        $request = new \DPSFolioProducer\APIRequest('folios', $this->config,
            array(
                'type' => 'get'
            )
        );

        return $request->run();
    }
}
