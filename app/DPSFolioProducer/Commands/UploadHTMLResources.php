<?php
namespace DPSFolioProducer\Commands;

class UploadHTMLResources extends Command
{
    protected $requiredOptions = array('filepath', 'folio_id');

    public function execute()
    {
        $filepath = $this->options['filepath'];
        $folioID = $this->options['folio_id'];

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/htmlresources', $this->config,
            array(
                'file' => $filepath,
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
