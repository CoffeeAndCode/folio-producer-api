<?php
namespace DPSFolioProducer\Commands;

class UploadHTMLResources extends Command
{
    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/htmlresources', $this->config,
            array(
                'file' => $this->options['filepath'],
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
