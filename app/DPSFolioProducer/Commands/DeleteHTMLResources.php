<?php
namespace DPSFolioProducer\Commands;

class DeleteHTMLResources extends Command
{
    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/htmlresources', $this->config,
            array(
                'type' => 'delete'
            )
        );

        return $request->run();
    }
}
