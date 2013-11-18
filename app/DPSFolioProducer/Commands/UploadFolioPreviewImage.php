<?php
namespace DPSFolioProducer\Commands;

class UploadFolioPreviewImage extends Command
{
    protected $requiredOptions = array('filepath', 'folio_id', 'orientation');

    public function execute()
    {
        $filepath = $this->options['filepath'];
        $folioID = $this->options['folio_id'];
        $orientation = $this->options['orientation'];

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/previews/'.$orientation, $this->config,
            array(
                'data' => json_encode(array('fileName' => pathinfo($filepath, PATHINFO_BASENAME))),
                'file' => $filepath,
                'type' => 'post'
            )
        );
        return $request->run();
    }
}
