<?php
namespace DPSFolioProducer\Commands;

class GetFolioMetadata extends Command
{
    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID, $this->config,
            array(
                'type' => 'get'
            )
        );

        return $request->run();
    }
}
