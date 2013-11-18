<?php
namespace DPSFolioProducer\Commands;

class DownloadFolioPreviewImage extends Command
{
    protected $requiredOptions = array('folio_id', 'orientation');

    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $orientation = $this->options['orientation'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/previews/'.$orientation, $this->config,
            array(
                'type' => 'get',
                'urlType' => 'download'
            )
        );

        return $request->run();
    }
}
